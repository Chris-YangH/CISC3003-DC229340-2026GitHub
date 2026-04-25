const nodemailer = require('nodemailer');

let transporter = null;

function getTransporter() {
  if (transporter) return transporter;
  if (!process.env.SMTP_HOST) return null;
  transporter = nodemailer.createTransport({
    host: process.env.SMTP_HOST,
    port: parseInt(process.env.SMTP_PORT || '587', 10),
    secure: parseInt(process.env.SMTP_PORT || '587', 10) === 465,
    auth: process.env.SMTP_USER
      ? { user: process.env.SMTP_USER, pass: process.env.SMTP_PASS }
      : undefined
  });
  return transporter;
}

async function sendMail({ to, subject, html, text }) {
  const from = process.env.MAIL_FROM || 'Cornerstone <no-reply@cornerstone.local>';
  const t = getTransporter();
  if (!t) {
    console.log('\n========= MOCK EMAIL =========');
    console.log(`From: ${from}`);
    console.log(`To: ${to}`);
    console.log(`Subject: ${subject}`);
    console.log('------------------------------');
    console.log(text);
    console.log('==============================\n');
    return { mocked: true };
  }
  return t.sendMail({ from, to, subject, html, text });
}

async function sendVerificationEmail(to, displayName, link) {
  const name = displayName || 'there';
  return sendMail({
    to,
    subject: 'Verify your Cornerstone account',
    text: `Hi ${name},\n\nWelcome to Cornerstone. Please verify your email by opening the link below:\n${link}\n\nThis link expires in 24 hours.\n\n— Cornerstone`,
    html: `<p>Hi ${name},</p><p>Welcome to Cornerstone. Please verify your email by clicking the link below:</p><p><a href="${link}">${link}</a></p><p>This link expires in 24 hours.</p><p>— Cornerstone</p>`
  });
}

async function sendPasswordResetEmail(to, displayName, link) {
  const name = displayName || 'there';
  return sendMail({
    to,
    subject: 'Reset your Cornerstone password',
    text: `Hi ${name},\n\nYou requested to reset your password. Set a new one using the link below:\n${link}\n\nIf you did not request this, ignore this email. This link expires in 24 hours.\n\n— Cornerstone`,
    html: `<p>Hi ${name},</p><p>You requested to reset your password. Set a new one using the link below:</p><p><a href="${link}">${link}</a></p><p>If you did not request this, ignore this email. This link expires in 24 hours.</p><p>— Cornerstone</p>`
  });
}

module.exports = { sendVerificationEmail, sendPasswordResetEmail };
