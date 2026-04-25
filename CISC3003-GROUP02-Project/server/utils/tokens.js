const crypto = require('crypto');

function generateToken() {
  return crypto.randomBytes(32).toString('hex');
}

function tokenExpiry(hours = 24) {
  const d = new Date();
  d.setHours(d.getHours() + hours);
  return d.toISOString();
}

function isExpired(expiresAt) {
  return new Date(expiresAt) < new Date();
}

module.exports = { generateToken, tokenExpiry, isExpired };
