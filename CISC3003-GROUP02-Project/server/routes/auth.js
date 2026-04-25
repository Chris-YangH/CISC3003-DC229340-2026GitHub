const express = require('express');
const bcrypt = require('bcrypt');
const db = require('../db');
const { generateToken, tokenExpiry, isExpired } = require('../utils/tokens');
const { sendVerificationEmail, sendPasswordResetEmail } = require('../utils/mailer');
const { authLimiter } = require('../middleware/rateLimit');

const router = express.Router();
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function appUrl() {
  return process.env.APP_URL || `http://localhost:${process.env.PORT || 3000}`;
}

router.post('/signup', authLimiter, async (req, res) => {
  const { email, password, displayName } = req.body || {};
  if (!email || !EMAIL_RE.test(email)) {
    return res.status(400).json({ error: 'A valid email is required.' });
  }
  if (!password || password.length < 8) {
    return res.status(400).json({ error: 'Password must be at least 8 characters.' });
  }
  const normalized = email.toLowerCase().trim();
  const existing = db.prepare('SELECT id FROM users WHERE email = ?').get(normalized);
  if (existing) {
    return res.status(409).json({ error: 'This email is already registered.' });
  }

  const hash = await bcrypt.hash(password, 12);
  const result = db
    .prepare('INSERT INTO users (email, password_hash, display_name) VALUES (?, ?, ?)')
    .run(normalized, hash, displayName ? String(displayName).trim().slice(0, 80) : null);

  const token = generateToken();
  db.prepare('INSERT INTO verifications (user_id, token, expires_at) VALUES (?, ?, ?)').run(
    result.lastInsertRowid,
    token,
    tokenExpiry(24)
  );

  const link = `${appUrl()}/api/auth/verify?token=${token}`;
  try {
    await sendVerificationEmail(normalized, displayName, link);
  } catch (err) {
    console.error('Failed to send verification email:', err.message);
  }

  res.json({
    ok: true,
    message: 'Account created. Check your email to verify your account before logging in.'
  });
});

router.get('/verify', (req, res) => {
  const { token } = req.query;
  if (!token) return res.redirect('/verify.html?status=error');

  const record = db.prepare('SELECT * FROM verifications WHERE token = ?').get(token);
  if (!record) return res.redirect('/verify.html?status=error');
  if (record.used_at) return res.redirect('/verify.html?status=used');
  if (isExpired(record.expires_at)) return res.redirect('/verify.html?status=expired');

  db.prepare('UPDATE users SET is_verified = 1 WHERE id = ?').run(record.user_id);
  db.prepare('UPDATE verifications SET used_at = CURRENT_TIMESTAMP WHERE id = ?').run(record.id);
  res.redirect('/verify.html?status=success');
});

router.post('/login', authLimiter, async (req, res) => {
  const { email, password } = req.body || {};
  if (!email || !password) {
    return res.status(400).json({ error: 'Email and password are required.' });
  }
  const user = db.prepare('SELECT * FROM users WHERE email = ?').get(String(email).toLowerCase().trim());
  if (!user) return res.status(401).json({ error: 'Invalid credentials.' });

  const match = await bcrypt.compare(password, user.password_hash);
  if (!match) return res.status(401).json({ error: 'Invalid credentials.' });

  if (!user.is_verified) {
    return res.status(403).json({ error: 'Please verify your email before logging in.' });
  }

  req.session.userId = user.id;
  res.json({
    ok: true,
    user: { id: user.id, email: user.email, displayName: user.display_name }
  });
});

router.post('/logout', (req, res) => {
  req.session.destroy(() => {
    res.clearCookie('cornerstone.sid');
    res.json({ ok: true });
  });
});

router.get('/me', (req, res) => {
  if (!req.session.userId) return res.json({ user: null });
  const user = db
    .prepare('SELECT id, email, display_name FROM users WHERE id = ?')
    .get(req.session.userId);
  if (!user) return res.json({ user: null });
  res.json({ user: { id: user.id, email: user.email, displayName: user.display_name } });
});

router.post('/forgot', authLimiter, async (req, res) => {
  const { email } = req.body || {};
  if (!email) return res.status(400).json({ error: 'Email required.' });

  const user = db.prepare('SELECT * FROM users WHERE email = ?').get(String(email).toLowerCase().trim());
  if (user) {
    const token = generateToken();
    db.prepare('INSERT INTO resets (user_id, token, expires_at) VALUES (?, ?, ?)').run(
      user.id,
      token,
      tokenExpiry(24)
    );
    const link = `${appUrl()}/reset.html?token=${token}`;
    try {
      await sendPasswordResetEmail(user.email, user.display_name, link);
    } catch (err) {
      console.error('Failed to send reset email:', err.message);
    }
  }
  // Always return success to avoid revealing account existence.
  res.json({
    ok: true,
    message: 'If that email has an account, a password reset link has been sent.'
  });
});

router.post('/reset', authLimiter, async (req, res) => {
  const { token, newPassword } = req.body || {};
  if (!token || !newPassword) {
    return res.status(400).json({ error: 'Token and new password are required.' });
  }
  if (newPassword.length < 8) {
    return res.status(400).json({ error: 'Password must be at least 8 characters.' });
  }
  const record = db.prepare('SELECT * FROM resets WHERE token = ?').get(token);
  if (!record) return res.status(400).json({ error: 'Invalid or expired token.' });
  if (record.used_at) return res.status(400).json({ error: 'This token has already been used.' });
  if (isExpired(record.expires_at)) return res.status(400).json({ error: 'This token has expired.' });

  const hash = await bcrypt.hash(newPassword, 12);
  db.prepare('UPDATE users SET password_hash = ? WHERE id = ?').run(hash, record.user_id);
  db.prepare('UPDATE resets SET used_at = CURRENT_TIMESTAMP WHERE id = ?').run(record.id);

  res.json({ ok: true, message: 'Password updated. Please log in with your new password.' });
});

module.exports = router;
