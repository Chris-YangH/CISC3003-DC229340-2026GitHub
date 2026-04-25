// Uses the built-in node:sqlite module (Node.js 22.5+).
// No native compilation required, no external SQLite dependency.
const { DatabaseSync } = require('node:sqlite');
const path = require('path');
const fs = require('fs');

const dbPath = process.env.DB_PATH || path.join(__dirname, 'database.sqlite');
const db = new DatabaseSync(dbPath);
db.exec('PRAGMA journal_mode = WAL');
db.exec('PRAGMA foreign_keys = ON');

function createTables() {
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      email TEXT UNIQUE NOT NULL,
      password_hash TEXT NOT NULL,
      display_name TEXT,
      is_verified INTEGER DEFAULT 0,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS verifications (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER NOT NULL,
      token TEXT UNIQUE NOT NULL,
      expires_at DATETIME NOT NULL,
      used_at DATETIME,
      FOREIGN KEY (user_id) REFERENCES users(id)
    );

    CREATE TABLE IF NOT EXISTS resets (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER NOT NULL,
      token TEXT UNIQUE NOT NULL,
      expires_at DATETIME NOT NULL,
      used_at DATETIME,
      FOREIGN KEY (user_id) REFERENCES users(id)
    );

    CREATE TABLE IF NOT EXISTS programmes (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      university TEXT NOT NULL,
      region TEXT NOT NULL,
      tier TEXT NOT NULL,
      duration TEXT,
      tuition TEXT,
      highlight TEXT,
      url TEXT
    );

    CREATE TABLE IF NOT EXISTS jobs (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      title TEXT NOT NULL,
      company TEXT NOT NULL,
      role_type TEXT NOT NULL,
      location TEXT,
      level TEXT,
      highlight TEXT,
      url TEXT
    );

    CREATE TABLE IF NOT EXISTS shortlists (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER NOT NULL,
      item_type TEXT NOT NULL,
      item_id INTEGER NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      UNIQUE(user_id, item_type, item_id),
      FOREIGN KEY (user_id) REFERENCES users(id)
    );

    CREATE TABLE IF NOT EXISTS history (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER NOT NULL,
      item_type TEXT NOT NULL,
      item_id INTEGER,
      page_path TEXT,
      viewed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES users(id)
    );
  `);
}

function seedFromJson(table, columns, file) {
  const count = db.prepare(`SELECT COUNT(*) AS c FROM ${table}`).get().c;
  if (count > 0) return 0;
  const filePath = path.join(__dirname, 'data', file);
  if (!fs.existsSync(filePath)) return 0;
  const items = JSON.parse(fs.readFileSync(filePath, 'utf-8'));
  const placeholders = columns.map((c) => '@' + c).join(', ');
  const insert = db.prepare(
    `INSERT INTO ${table} (${columns.join(', ')}) VALUES (${placeholders})`
  );
  db.exec('BEGIN');
  try {
    for (const row of items) {
      const bound = {};
      for (const col of columns) bound[col] = row[col] ?? null;
      insert.run(bound);
    }
    db.exec('COMMIT');
  } catch (err) {
    db.exec('ROLLBACK');
    throw err;
  }
  return items.length;
}

createTables();
const seededProgrammes = seedFromJson(
  'programmes',
  ['name', 'university', 'region', 'tier', 'duration', 'tuition', 'highlight', 'url'],
  'programmes.seed.json'
);
const seededJobs = seedFromJson(
  'jobs',
  ['title', 'company', 'role_type', 'location', 'level', 'highlight', 'url'],
  'jobs.seed.json'
);
if (seededProgrammes) console.log(`Seeded ${seededProgrammes} programmes.`);
if (seededJobs) console.log(`Seeded ${seededJobs} jobs.`);

module.exports = db;
