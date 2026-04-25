# Cornerstone — CISC3003 Group 02 Project

A full-stack academic and career handbook for CS undergraduates, built for the University of Macau's CISC3003 Web Programming course.

## Stack

- **Frontend:** vanilla HTML / CSS / JavaScript (no framework, no build tool)
- **Backend:** Node.js + Express
- **Database:** SQLite (`better-sqlite3`), file-based
- **Auth:** session cookies + bcrypt + email verification + password reset (nodemailer)
- **Deployment target:** Render.com (free Web Service + persistent disk)

## Features

1. **Seven content chapters** — Home, About, Skills, Master's Preparation, Careers, Jobs, Readiness, Reflection.
2. **Full auth flow** — signup with email verification, login, logout, forgot / reset password.
3. **Search** — server-side search + region/tier/role filters for 37 Master's programmes and 43 job openings.
4. **Shortlist** (= shopping-cart equivalent) — save programmes and jobs to your personal list.
5. **Browsing history** — automatic per-page tracking, viewable in your account.
6. **Responsive design** — mobile / tablet / desktop layouts with hamburger menu, tabs, accordion, timeline.
7. **Dark mode** — persisted in `localStorage`, respects `prefers-color-scheme` on first load.
8. **Accessibility** — skip link, semantic landmarks, `aria-*` on tabs/filters, keyboard-navigable menu, focus-visible rings, `prefers-reduced-motion` support.

## Project layout

```
CISC3003-GROUP02-Project/
├── server/                         # Express backend
│   ├── index.js                    # App entry
│   ├── db.js                       # SQLite setup + seed
│   ├── routes/                     # auth, programmes, jobs, shortlist, history
│   ├── middleware/                 # requireLogin, rate-limit
│   ├── utils/                      # tokens, mailer
│   ├── data/                       # programmes.seed.json, jobs.seed.json
│   ├── package.json
│   └── .env.example
└── public/                         # Static frontend, served by Express
    ├── cisc3003-IndAssgn.html      # Home
    ├── about.html / skills.html / masters.html / careers.html / jobs.html
    ├── readiness.html / reflection.html / account.html
    ├── signup.html / login.html / verify.html / forgot.html / reset.html
    ├── css/                        # base / layout / components / pages
    └── js/                         # api / session / nav / ui / theme / auth / search / shortlist / filter / account / history-log
```

## Running locally

### Prerequisites
- Node.js 18 or later
- npm

### Setup

```bash
cd server
cp .env.example .env
# Edit .env — set a SESSION_SECRET. Leave SMTP_HOST blank to log emails to console.
npm install
npm start
```

Open http://localhost:3000 in a browser.

### Development mode (auto-reload)

```bash
cd server
npm run dev
```

### Environment variables

| Variable | Purpose |
| -------- | ------- |
| `PORT` | Server port (default 3000) |
| `NODE_ENV` | `production` enables secure cookies |
| `SESSION_SECRET` | 32+ character random string used by `express-session` |
| `APP_URL` | Full public URL, used in verification / reset emails |
| `SMTP_HOST` / `SMTP_PORT` / `SMTP_USER` / `SMTP_PASS` | Email delivery (leave `SMTP_HOST` blank to mock-log emails to console) |
| `MAIL_FROM` | "From" header of outgoing mail |

### Verifying the app works locally

A suggested end-to-end smoke test once the server is running:

1. Visit `http://localhost:3000/signup.html` — create an account.
2. Check the server's console — a mock verification email link is printed.
3. Paste the verify link into the browser — you should land on `verify.html?status=success`.
4. Sign in at `http://localhost:3000/login.html`.
5. Go to `/masters.html` — search "NUS", filter region to Singapore, tap the ♡ on a programme.
6. Go to `/jobs.html` — filter role to MLE, tap the ♡ on Anthropic.
7. Go to `/account.html` — both saves appear in the Shortlist tab; the History tab shows the pages you visited.
8. Sign out and sign back in. Your shortlist persists.
9. Use "Forgot password" with your email, grab the reset link from the console, set a new password.

## Deployment (Render.com)

1. Push the repo to GitHub.
2. On Render, create a new **Web Service**, point it at the repo.
3. Settings:
   - **Build command:** `cd server && npm install`
   - **Start command:** `cd server && node index.js`
4. Add a Persistent Disk (1 GB is plenty), mount path `/opt/render/project/src/server/`, so the SQLite file survives deploys.
5. Set environment variables in Render's dashboard (match the table above). For email delivery, the simplest production option is Resend (free 3k/month); Gmail App Passwords also work.
6. Once deployed, the URL will be `https://<your-service-name>.onrender.com`. That URL is the deliverable required by the course rubric.

## Database

On first boot, `server/db.js`:

- creates all tables if they don't exist,
- seeds `programmes` from `server/data/programmes.seed.json` (37 rows) if that table is empty,
- seeds `jobs` from `server/data/jobs.seed.json` (43 rows) if that table is empty.

Subsequent boots are no-ops. The SQLite file lives at `server/database.sqlite` and is gitignored.

## API reference (summary)

Auth: `POST /api/auth/signup`, `GET /api/auth/verify`, `POST /api/auth/login`, `POST /api/auth/logout`, `GET /api/auth/me`, `POST /api/auth/forgot`, `POST /api/auth/reset`.

Content: `GET /api/programmes?q=&region=&tier=`, `GET /api/programmes/:id`, `GET /api/jobs?q=&role=&company=&location=`, `GET /api/jobs/:id`.

Shortlist (auth): `GET /api/shortlist`, `GET /api/shortlist/ids`, `POST /api/shortlist`, `DELETE /api/shortlist`.

History (auth): `GET /api/history?limit=50`, `POST /api/history`, `DELETE /api/history`.

## Course-requirement compliance

| Course requirement | How Cornerstone implements it |
| --- | --- |
| Responsive design | Mobile-first CSS, three breakpoints, hamburger + tab + accordion patterns |
| User signup | `POST /api/auth/signup` → `signup.html` |
| Email verification | `verifications` table + `sendVerificationEmail` + `verify.html` |
| Login | `POST /api/auth/login` + session cookie |
| Password reset | `POST /api/auth/forgot` + `POST /api/auth/reset` |
| Search service | `/api/programmes` + `/api/jobs` with server-side filtering |
| Shopping cart / history | `shortlists` and `history` tables, `/account.html` dashboard |
| Deployment URL | Render.com Web Service (public URL) |

## License

Academic project submitted for CISC3003 (University of Macau, 2026). Not licensed for commercial use.
