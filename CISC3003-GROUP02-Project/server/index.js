require('dotenv').config();
const path = require('path');
const express = require('express');
const session = require('express-session');

require('./db'); // initialise DB + seed on boot

const authRoutes = require('./routes/auth');
const programmesRoutes = require('./routes/programmes');
const jobsRoutes = require('./routes/jobs');
const shortlistRoutes = require('./routes/shortlist');
const historyRoutes = require('./routes/history');

const app = express();
const PORT = parseInt(process.env.PORT || '3000', 10);
const IS_PROD = process.env.NODE_ENV === 'production';

app.set('trust proxy', 1);
app.use(express.json({ limit: '64kb' }));
app.use(express.urlencoded({ extended: false }));

app.use(
  session({
    name: 'cornerstone.sid',
    secret: process.env.SESSION_SECRET || 'dev-secret-change-me',
    resave: false,
    saveUninitialized: false,
    cookie: {
      httpOnly: true,
      sameSite: 'lax',
      secure: IS_PROD,
      maxAge: 1000 * 60 * 60 * 24 * 14
    }
  })
);

app.get('/api/healthz', (req, res) => res.json({ ok: true }));

app.use('/api/auth', authRoutes);
app.use('/api/programmes', programmesRoutes);
app.use('/api/jobs', jobsRoutes);
app.use('/api/shortlist', shortlistRoutes);
app.use('/api/history', historyRoutes);

const publicPath = path.join(__dirname, '..', 'public');
app.use(express.static(publicPath, { extensions: ['html'] }));

app.get('/', (req, res) => {
  res.sendFile(path.join(publicPath, 'cisc3003-IndAssgn.html'));
});

app.use((req, res) => {
  if (req.path.startsWith('/api/')) {
    return res.status(404).json({ error: 'Not found' });
  }
  res.status(404).sendFile(path.join(publicPath, 'cisc3003-IndAssgn.html'));
});

app.use((err, req, res, next) => {
  console.error(err);
  res.status(500).json({ error: 'Server error' });
});

app.listen(PORT, () => {
  console.log(`Cornerstone server listening on http://localhost:${PORT}`);
});
