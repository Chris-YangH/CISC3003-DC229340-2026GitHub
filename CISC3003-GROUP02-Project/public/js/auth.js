// Handles signup/login/forgot/reset forms: validation, submit, feedback.
(function () {
  const ns = (window.cornerstone = window.cornerstone || {});
  const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function showFeedback(form, message, kind) {
    let box = form.querySelector('.form-feedback');
    if (!box) {
      box = document.createElement('div');
      box.className = 'form-feedback';
      form.insertBefore(box, form.firstChild);
    }
    box.textContent = message;
    box.className = 'form-feedback is-' + kind;
  }

  function lock(form, locked) {
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = locked;
      btn.textContent = locked ? 'Working…' : btn.dataset.label || btn.textContent;
      if (!btn.dataset.label) btn.dataset.label = btn.textContent;
    }
  }

  function qs(key) {
    return new URLSearchParams(window.location.search).get(key);
  }

  async function handleSignup(form) {
    const email = form.querySelector('[name="email"]').value.trim();
    const password = form.querySelector('[name="password"]').value;
    const confirm = form.querySelector('[name="confirm"]').value;
    const displayName = form.querySelector('[name="displayName"]').value.trim();
    if (!EMAIL_RE.test(email)) return showFeedback(form, 'Please enter a valid email.', 'error');
    if (password.length < 8) return showFeedback(form, 'Password must be at least 8 characters.', 'error');
    if (password !== confirm) return showFeedback(form, 'Passwords do not match.', 'error');

    lock(form, true);
    try {
      const res = await ns.api.post('/api/auth/signup', { email, password, displayName });
      showFeedback(form, res.message || 'Check your email for a verification link.', 'success');
      form.reset();
    } catch (err) {
      showFeedback(form, err.message, 'error');
    } finally {
      lock(form, false);
    }
  }

  async function handleLogin(form) {
    const email = form.querySelector('[name="email"]').value.trim();
    const password = form.querySelector('[name="password"]').value;
    if (!EMAIL_RE.test(email)) return showFeedback(form, 'Please enter a valid email.', 'error');
    if (!password) return showFeedback(form, 'Password is required.', 'error');

    lock(form, true);
    try {
      await ns.api.post('/api/auth/login', { email, password });
      showFeedback(form, 'Signed in. Redirecting…', 'success');
      const redirect = qs('redirect') || '/account.html';
      setTimeout(() => (window.location.href = redirect), 500);
    } catch (err) {
      showFeedback(form, err.message, 'error');
    } finally {
      lock(form, false);
    }
  }

  async function handleForgot(form) {
    const email = form.querySelector('[name="email"]').value.trim();
    if (!EMAIL_RE.test(email)) return showFeedback(form, 'Please enter a valid email.', 'error');

    lock(form, true);
    try {
      const res = await ns.api.post('/api/auth/forgot', { email });
      showFeedback(form, res.message || 'If the email exists, a reset link has been sent.', 'success');
      form.reset();
    } catch (err) {
      showFeedback(form, err.message, 'error');
    } finally {
      lock(form, false);
    }
  }

  async function handleReset(form) {
    const token = qs('token');
    const newPassword = form.querySelector('[name="newPassword"]').value;
    const confirm = form.querySelector('[name="confirm"]').value;
    if (!token) return showFeedback(form, 'Reset token missing from URL.', 'error');
    if (newPassword.length < 8) return showFeedback(form, 'Password must be at least 8 characters.', 'error');
    if (newPassword !== confirm) return showFeedback(form, 'Passwords do not match.', 'error');

    lock(form, true);
    try {
      const res = await ns.api.post('/api/auth/reset', { token, newPassword });
      showFeedback(form, res.message || 'Password updated. Redirecting to login…', 'success');
      setTimeout(() => (window.location.href = '/login.html'), 900);
    } catch (err) {
      showFeedback(form, err.message, 'error');
    } finally {
      lock(form, false);
    }
  }

  const handlers = {
    signup: handleSignup,
    login: handleLogin,
    forgot: handleForgot,
    reset: handleReset
  };

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-auth-form]').forEach((form) => {
      const kind = form.getAttribute('data-auth-form');
      const handler = handlers[kind];
      if (!handler) return;
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        handler(form);
      });
    });
  });
})();
