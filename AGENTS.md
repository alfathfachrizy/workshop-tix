# AGENTS.md — WorkshopTIX

Paid IT workshop registration + digital ticket app. Solo vocational-school project (LKPD XI RPL, SMK PESAT). Graded on: Git/PR evidence, working payment flow, authorization, N+1 comparison, tests, README.

## Working Style
- Work autonomously, phase by phase (see **Phases**). Be terse: one-line status per phase, no long explanations.
- Batch file creation. Don't re-read files unnecessarily. Don't ask questions unless blocked.
- Ask the user ONLY for: DB credentials, Midtrans Sandbox keys, GitHub auth.
- UI text: **Indonesian**. Code, comments, commits: English.

## Stack
Laravel 11+, PHP 8.2+, MySQL, Blade + Tailwind (Breeze Blade), Alpine.js, `midtrans/midtrans-php`, `simplesoftwareio/simple-qrcode`, Laravel Debugbar (dev only), Pest or PHPUnit.

## Git Rules (mandatory)
- Never commit features directly to `main`.
- One branch per feature: `feature/layout`, `feature/database`, `feature/auth`, `feature/authorization`, `feature/workshop-crud`, `feature/registration`, `feature/midtrans`, `feature/ticket`, `feature/n1-before`, `feature/n1-after`, `docs/readme`.
- Small conventional commits: `feat:`, `fix:`, `docs:`, `test:`, `refactor:`.
- Per branch: push → `gh pr create` (title + 2-line body) → self-review → `gh pr merge --merge`.
- If `gh` is unavailable/unauthenticated: tell the user once, continue with local `git merge --no-ff`.
- Never commit `.env` or keys. Keep `.env.example` updated.

## Data Model
- **users**: Breeze default + `role` enum(`panitia`,`peserta`) default `peserta`.
- **workshops**: id, title, slug (unique), description, speaker, location, starts_at, price (unsigned int, IDR), quota, timestamps.
- **registrations**: id, user_id FK, workshop_id FK (cascade), order_id (unique), status enum(`pending`,`paid`,`failed`,`expired`) default `pending`, snap_token (nullable), ticket_code (nullable, unique), paid_at (nullable), timestamps. **UNIQUE(user_id, workshop_id)**.
- Models: relations, `$fillable`, casts.
- Factories + seeders: 1 panitia (`panitia@workshoptix.test` / `password`), 5 peserta, 8 workshops, 25+ registrations in mixed statuses (needed for N+1 demo). List demo accounts in README.

## Features

### Auth & Authorization
- Register / login / logout. Role is **forced to `peserta`** on register, never read from input.
- Middleware `role:panitia` on all `/panitia/*` routes.
- `RegistrationPolicy`: view / pay / ticket only if `registration.user_id === auth()->id()`, else 403.

### Peserta
- `/` landing + workshop list: cards, search by title, price, date, remaining quota, "Penuh" badge when full.
- `/workshops/{slug}`: detail + "Daftar" button (login required).
- `POST` register: FormRequest validation; reject duplicate (code check + DB unique, friendly flash error; if a pending one exists, redirect to its payment page); reject if quota full; create `pending` registration with `order_id` = `WTX-{id}-{time}`; redirect to payment page.
- `/my-registrations`: own registrations, status badges (pending=yellow, paid=green, failed/expired=red), actions: Bayar / Cek status / Lihat tiket.
- `/registrations/{id}`: payment page with Snap popup button + "Cek status" button.

### Panitia
- `/panitia`: dashboard stat cards (total workshop, pendaftar, paid, pending, revenue).
- Workshop CRUD: FormRequest, inline validation errors, delete confirmation. Quota cannot be lowered below registered count.
- `/panitia/workshops/{id}/registrants`: registrants per workshop.
- `/panitia/registrations`: all registrants (participant name + workshop name + status) with N+1 comparison (below).

## Midtrans (Sandbox)
- Keys in `.env` → `config/services.php`. Never hardcode.
- Create Snap token **server-side**. `gross_amount` comes from `workshops.price` in DB, **never from request**. Include customer details + item details. Save `snap_token`, keep status `pending`.
- All logic in one `MidtransService` class (token creation, status mapping, applying result).
- Verification only via backend:
  - **Webhook** `POST /midtrans/notification` (CSRF-exempt): verify `signature_key = sha512(order_id + status_code + gross_amount + server_key)`; reject if invalid.
  - **"Cek status" button**: call `\Midtrans\Transaction::status(order_id)`, apply the same mapper.
- Status mapping: `settlement` / `capture` (fraud accept) → `paid`; `deny` / `cancel` / `failure` → `failed`; `expire` → `expired`.
- Updates must be **idempotent**. On `paid`: set `paid_at`, generate unique `ticket_code` (`WTX-XXXXXXXX`).
- Frontend Snap success callback must **NOT** mark paid; it only reloads / triggers backend status check.

## Ticket
- `/tickets/{registration}`: owner AND `paid` only. Not owner → 403. Unpaid → redirect back with message.
- Ticket-style card: ticket_code, participant name, workshop title, schedule, location, QR code of ticket_code, print button (print CSS).

## N+1 Demo (`/panitia/registrations`)
- `?mode=lazy` → deliberately lazy: `Registration::all()` + `$r->user->name` / `$r->workshop->title` in loop.
- `?mode=eager` (default) → `Registration::with(['user','workshop'])->get()`.
- Count queries with `DB::listen`; show footer badge "N query • X ms".
- Small panel compares both modes on the same data (e.g. 51 vs 3).
- No pagination on this page so counts stay clear.
- Branch order: merge `feature/n1-before` (lazy) first, then `feature/n1-after` (eager).

## UI/UX (must look polished, not default)
- Palette: navy `#1e3a8a` primary, orange `#f97316` accent, `slate-50` background. Font: Plus Jakarta Sans or Inter.
- One layout `layouts/app.blade.php` + reusable Blade components: navbar (role-aware, responsive hamburger), flash alert, status-badge, workshop-card, stat-card, empty-state, button, form inputs with error messages, footer "SMK PESAT • RPL".
- Mobile-first responsive, hover/focus states, consistent spacing, tables scroll horizontally on mobile, confirm dialogs for delete.
- Custom 403 / 404 pages in Indonesian.

## Tests (Midtrans mocked / `Http::fake`)
- Duplicate registration rejected.
- Peserta cannot open another user's registration or ticket (403).
- Unpaid ticket denied.
- Peserta cannot access `/panitia` (403).
- Panitia CRUD works.
- Webhook with invalid signature rejected.
- Webhook `settlement` → `paid` + `ticket_code`.
- Amount comes from DB, not request.
- Run `php artisan test`; fix until green.

## README.md (branch `docs/readme`)
Must contain:
1. Project summary.
2. Setup: clone, `composer install`, `npm install && npm run build`, `cp .env.example .env`, `php artisan key:generate`, DB config, `php artisan migrate --seed`, `php artisan serve`.
3. Midtrans Sandbox setup, how to simulate payment, ngrok note for webhook.
4. Demo accounts.
5. ERD (mermaid).
6. Backlog table with Status column (all Done).
7. Feature → branch → PR table.
8. Test scenario table (alur utama, daftar ganda, tiket akun lain, belum lunas, webhook invalid) with expected/actual.
9. Query comparison table (lazy vs eager, same data).
10. Screenshot placeholders section.

## Phases (execute in order)
1. Init repo + layout + UI skeleton
2. Database, models, seeders
3. Auth + roles + policy
4. Workshop CRUD + public pages
5. Registration flow
6. Midtrans + ticket
7. N+1 (lazy → eager)
8. Tests
9. README
10. Final check: `migrate:fresh --seed`, tests green, `npm run build` OK

## Definition of Done
- Demo flow works: login → pick workshop → register → pay in Sandbox → check status → open ticket.
- All branches merged to `main` via PR; no secrets in repo.
- Tests green; README complete.

## Final Report (when finished)
Print: repo summary, list of PR links, and the exact manual steps still needed from the user (keys, screenshots).   