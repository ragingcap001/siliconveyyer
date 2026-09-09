# base-systems.md — HyipRio / Siliconveyyer Platform Overview

A complete map of the total functionality implemented in this codebase, derived from a
read-through of the routes, controllers, models, providers, middleware, payment modules,
cron engine, views, and the seeded database schema.

Last reviewed against commit `76844de` on branch `arena/01a08764-siliconveyyer`.

---

## 1. What this codebase is

This is **HyipRio**, a commercial Laravel application for running a
**High-Yield Investment Program (HYIP) / crypto-investment platform**. It is a
multi-sided financial web app with three surfaces:

| Surface | Namespace | Entry point |
| --- | --- | --- |
| Public marketing site + static pages | `frontend::*` | `/` |
| Customer dashboard (investor portal) | `Frontend\*` controllers, `/user/*` | `/user/dashboard` |
| Admin / staff control panel | `Backend\*` controllers | `/{site_admin_prefix}` (default `admin`) |

Out of the box it lets an operator sell "investment plans", take deposits in fiat and
crypto through ~25 payment gateways, pay scheduled returns (ROI) to investors, pay
multi-level referral commissions, run a user ranking/badge system, and pay withdrawals
back out — with a full KYC, ticketing, notification, and CMS layer around it.

**Stack:** PHP ≥ 8.1, Laravel 9, MySQL, Blade, jQuery/Bootstrap (admin), Alpine +
Tailwind + Vite (frontend assets), Pusher (broadcasting), Laravel Scout (transaction
search), Spatie Laravel-Permission (RBAC), Yajra DataTables (admin tables).

---

## 2. Architecture at a glance

```
app/
├─ Console/Kernel.php            Artisan scheduler (currently empty)
├─ Enums/                        7 typed enums (TxnType, TxnStatus, InvestStatus,
│                                KYCStatus, GatewayType, ReferralType, NavigationType)
├─ Events/                       UserReferred; 3 broadcast events
├─ Facades/Txn/                  Txn facade — the single ledger writer
├─ Http/
│  ├─ Controllers/               Backend/ (33), Frontend/ (17), Auth/ (8)
│  ├─ Middleware/                17 middleware classes
│  └─ Kernel.php                 global + route middleware map
├─ Jobs/                         IpnJob, WithdrawUpdateJob
├─ Listeners/RewardUser.php      referral attribution on signup
├─ Mail/MailSend.php             templated transactional mail
├─ Models/                       42 Eloquent models
├─ Providers/                    11 service providers
├─ Traits/                       ImageUpload, NotifyTrait, Payment, SmsTrait
├─ Rules/                        MatchOldPassword, Recaptcha
└─ helpers.php                   ~25 global helpers
modules/Payment/                 25 gateway driver classes + BaseTxn
routes/                          web.php, admin.php, auth.php, api.php,
                                 channels.php, console.php
resources/views/                 backend/, frontend/{4 themes}/, gateway/, errors/
database/migrations/             19 incremental migrations (2023-05 → 2023-07)
DB/hyiprio.sql                   full seeded schema + seed data (55 tables)
```

### Boot sequence (what each provider does)

| Provider | Responsibility |
| --- | --- |
| `AppServiceProvider` | Sets app timezone/locale/debug from DB settings; registers custom Blade directives (`@lasset`, `@removeimg`); pagination view |
| `AuthServiceProvider` | Super-Admin gate bypass; swaps the verification mail for a templated one |
| `EventServiceProvider` | `Registered` → email verification, `UserReferred` → `RewardUser` |
| `RouteServiceProvider` | Loads the 4 route files, applies middleware groups, rate limiting |
| `BroadcastServiceProvider` | Registers broadcast routes and loads `routes/channels.php` |
| `TxnProvider` | Binds the `Txn` facade to the ledger writer |
| `SettingServiceProvider` | Pushes DB-stored SMTP credentials into `config('mail')` at runtime |
| `GatewayServiceProvider` | Pushes DB-stored credentials into `config()` for PayPal, Paystack, PerfectMoney, Coinbase, CoinRemitter, etc. |
| `PluginServiceProvider` | Pushes DB-stored plugin credentials into `config('sms')` and `config('broadcasting')`; sets the default SMS driver |
| `ThemeServiceProvider` | Resolves the active site theme and registers `resources/views/frontend/{theme}` as the `frontend::` view namespace |
| `ViewServiceProvider` | View composers for nav, footer, socials, landing sections, currency, mobile detection |

Every provider that touches the DB is guarded by `Remotelywork\Installer\Repository\App::dbConnectionCheck()`
so the app boots cleanly before installation.

---

## 3. Request lifecycle & middleware

**Global stack** (`app/Http/Kernel.php`): TrustProxies → HandleCors → PreventRequestsDuringMaintenance
→ ValidatePostSize → TrimStrings → ConvertEmptyStringsToNull → `SecureHeaders`.

**`web` group:** EncryptCookies → StartSession → ShareErrorsFromSession → VerifyCsrfToken →
SubstituteBindings → `StoreReferralCode`.

**Route middleware aliases:**

| Alias | Class | Purpose |
| --- | --- | --- |
| `auth` | `Authenticate` | session guard |
| `XSS` | `XSS` | recursive `strip_tags()` on all request input |
| `KYC` | `KYC` | blocks withdraw / send-money until KYC verified |
| `2fa` | `TwoFaCheck` | Google2FA one-time-password gate |
| `isActive` | `CheckDeactivate` | blocks banned/deactivated accounts |
| `translate` | `Localization` | applies session locale |
| `isDemo` | `DemoMode` | blocks all POST/PUT/DELETE when `APP_DEMO=true` |
| `role` / `permission` / `role_or_permission` | Spatie | admin RBAC |

**Route groups applied by `RouteServiceProvider`:**

- `web.php` + `auth.php` → `web, XSS, trans, translate, install_check`
- `admin.php` → `web, auth:admin, XSS, trans, isDemo, translate, install_check`,
  prefix = `setting('site_admin_prefix')` (so **the admin URL is itself configurable**)

Note: the `trans` and `install_check` aliases are not registered in
`app/Http/Kernel.php`; they are expected to be supplied by the vendor packages
(`remotelywork/installer` and `joedixon/laravel-translation`). `vendor/` is not
committed here, so this could not be verified from the repository alone.

Secure headers added to every response: `Referrer-Policy`, `X-Content-Type-Options`,
`X-XSS-Protection`, `X-Frame-Options: DENY`, `Strict-Transport-Security`.

---

## 4. Core domain systems

### 4.1 Identity & onboarding

| Capability | Where |
| --- | --- |
| Registration with configurable fields (username / country / phone toggles) | `Auth\RegisteredUserController`, `page_settings` table |
| Geo-IP enrichment on signup (country + dial code via `ip-api.com`) | `getLocation()` in `helpers.php` |
| Email verification (optional, per setting) | Laravel `MustVerifyEmail` + `AuthServiceProvider` |
| Login / logout / password reset / confirm password | `Auth\*` controllers |
| Google Authenticator 2FA (TOTP), secret encrypted at rest | `two_fa` column, `User::google2faSecret()` cast, `TwoFaCheck` |
| Login activity log (IP, location, user-agent → browser + platform) | `LoginActivities::add()`, `jenssegers/agent` |
| Google reCaptcha on registration (plugin-gated) | `Rules\Recaptcha` |
| Session-based light/dark mode toggle | `HomeController@themeMode` |
| Referral capture before signup | `StoreReferralCode` middleware stores an `invite` cookie with the referral link's lifetime |
| Sign-up bonus credited to profit wallet | `RegisteredUserController@store` (`TxnType::SignupBonus`) |
| First-rank bonus credited on registration | `Ranking::find(1)->bonus` |

### 4.2 KYC (Know Your Customer)

- Admin builds **unlimited KYC form templates** with dynamic field definitions
  (`kycs` table, `Backend\KycController` full resource).
- Users submit a form; file uploads are stored via `ImageUpload` trait and old
  submissions are deleted.
- Three states via `KYCStatus` enum: `Verified(1) / Pending(2) / Failed(3)`.
- Admin queues: **pending**, **rejected**, **all**; approve/reject action fires
  `kyc_action` notifications.
- `KYC` middleware gates withdrawal and send-money routes.

### 4.3 Money, wallets, and the ledger

Every monetary movement goes through the **`Txn` facade** (`app/Facades/Txn/Txn.php`),
which creates a `transactions` row with `TRX{10 random}` reference. There is no
double-entry bookkeeping — the ledger is a single append-only transaction table plus
mutable user balance columns.

**Two wallets per user:**

| Wallet | Column | Funded by |
| --- | --- | --- |
| Main | `users.balance` | deposits, transfers received, refunds, capital-back, admin adjustments |
| Profit | `users.profit_balance` | ROI interest, referral commissions, signup/ranking bonuses, wallet exchange |

**Transaction types** (`TxnType` enum, 14 values): `deposit`, `subtract`, `manual_deposit`,
`send_money`, `exchange`, `referral`, `signup_bonus`, `bonus`, `withdraw`, `withdraw_auto`,
`receive_money`, `investment`, `interest`, `refund`.

**Transaction statuses** (`TxnStatus`): `success`, `pending`, `failed`.

Supporting behaviour:
- `Txn::update($tnx, $status)` credits `balance` when a deposit flips to `success`.
- `Transaction` is **Laravel Scout searchable** on amount/tnx/type/method/description/status —
  powering the search box on every user log page.
- Aggregations live on both models: `totalDeposit()`, `totalInvestment()`,
  `totalProfit($days)`, `totalWithdraw()`, `totalTransfer()`, `totalReferralProfit()`,
  `totalDepositBonus()`, `totalInvestBonus()`, `totalRoiProfit()`.

### 4.4 Investment plans ("Schemes") engine

`SchemaController` (admin) + `InvestController` (user).

A **Schema** (plan) defines:
- `type`: `range` (min/max) or `fixed` (exact amount)
- `return_interest` + `interest_type`: `percentage` or `fixed`
- `return_type`: `period` (N payouts) or `lifetime` (never ends)
- `number_of_period`, and a `schedules` lookup (`return_period`) that supplies the
  **payout interval in hours**
- `capital_back` flag — return the principal at maturity
- `off_days` (JSON) — weekdays on which no profit is paid
- `featured` + `badge`, `is_trending`, `icon`, `status`
- `schema_cancel` + `expiry_minute` — lets a user cancel an investment for a full
  refund within a window after purchase (also capped by a daily cancellation limit)

**Investing:** wallet selection `main`, `profit`, or `gateway`. The gateway path creates a
pending `investment` transaction and routes to the payment driver; the investment only
becomes `ongoing` in `Payment::paymentSuccess()`.

**Investment lifecycle** (`InvestStatus`): `pending → ongoing → completed`, or `canceled`.
`Invest` exposes a computed `is_cancel` attribute used by the cancel endpoint.

### 4.5 The ROI / profit engine (the heart of the system)

`CronJobController@investmentCronJob`, hit at `GET /cron-job/investment`.

For each `ongoing` investment whose `next_profit_time <= now`:
1. Skip if today is in the plan's `off_days`.
2. Compute interest: percentage → `(interest × invest_amount) / 100`, else flat.
3. Advance `next_profit_time += period_hours`, decrement `number_of_period`,
   increment `already_return_profit` and `total_profit_amount`.
4. Credit `profit_balance` and write an `interest` transaction.
5. **Lifetime plans** repeat forever; **period plans** complete on the last payout and,
   if `capital_back`, refund the principal with a `refund` transaction.
6. If `site_referral == 'level'` and `profit_level` is on, walk the upline paying
   `creditReferralBonus(..., 'profit', ...)`.
7. Fire `invest_roi` / `investment_end` mail + SMS + push notifications.

### 4.6 Deposits

- `DepositController@depositNow` creates a `deposit` (automatic) or `manual_deposit`
  (manual gateway) transaction with `pending` status.
- Charge model: `charge_type` of `percentage` or `fixed`; `pay_amount = final_amount × rate`.
- Manual gateways carry admin-defined `field_options`; uploaded proofs are stored via
  `ImageUpload`, and the request notifies the admin (`manual_deposit_request`).
- `Payment::depositAutoGateway()` maps the gateway code to a driver class and calls
  `->deposit()`.
- Admin: manual-pending queue with approve/reject (`depositAction` / `actionNow`) and a
  full deposit history.
- Deposit log for users, searchable and date-filterable.

### 4.7 Withdrawals

- Users first create **withdraw accounts** (saved payout credentials per method):
  `WithdrawAccount` CRUD (`frontend::withdraw.account.*`).
- `withdrawNow` enforces, in order:
  1. global + per-user withdraw permission,
  2. **withdrawal off-days** (`withdrawal_schedules` table — admin-managed weekday calendar),
  3. daily withdraw count limit,
  4. method min/max range,
  5. sufficient main-wallet balance (amount + charge).
- Debits `balance` immediately, writes `withdraw` (manual) or `withdraw_auto` transaction.
- Automatic methods dispatch to the gateway driver via `withdrawAutoGateway()`.
- Admin: pending queue + history, approve/reject (`withdrawAction` / `actionNow`), plus
  a **withdraw schedule editor** for off-days.
- Admin also manages **withdraw methods**: min/max, charge (fixed/percentage), rate,
  currency, processing time, custom credential fields, `auto` vs `manual` type.

### 4.8 Peer-to-peer transfer ("Send Money")

`SendMoneyController`. Gated by global setting and a per-user `transfer_status` flag, and
by KYC. Enforces daily count limit, min/max amount, and a percentage-or-fixed fee.
Writes **two** transactions: `send_money` (sender, debited amount + charge) and
`receive_money` (recipient, credited). Recipient is looked up by email; a live
`GET /user/exist/{email}` endpoint shows the recipient name before submitting.

### 4.9 Wallet exchange

`UserController@walletExchange*` — move funds between the main and profit wallet in
either direction, with a configurable charge, daily limit, and balance checks. Recorded
as `exchange` transactions.

### 4.10 Referral system (two mutually exclusive modes)

Selected by `setting('site_referral', 'global')`.

**A. Multi-level mode (`level`)** — `LevelReferral` table with per-depth `bounty`
(percentage) and `activation` (require an active referral) for each of
`deposit | investment | profit`. `creditReferralBonus()` in `helpers.php` walks up the
`ref_id` chain depth-by-depth, writing a `referral` transaction per level and crediting
each ancestor's `profit_balance`. Triggered from: signup, deposit success, investment
success, and every ROI payout.

**B. Target/milestone mode** — `referral_programs`, `referral_links`,
`referral_relationships`, `referral_targets`, `referrals` tables. Admin sets milestones
("when a referred user has deposited ≥ X, pay their referrer Y%"). `referralCronJob()`
evaluates every relationship against each user's lifetime deposit/invest totals, skipping
milestones already paid, and credits the bounty.

Both modes pay into `profit_balance` and are deduplicated via `target_id` / `is_level`
columns on the transaction.

### 4.11 Ranking / badge system

`Ranking` records define thresholds (`minimum_earnings`, `minimum_deposit`,
`minimum_invest`, `minimum_referral`, `minimum_referral_deposit`,
`minimum_referral_invest`) plus an icon, name, and one-time `bonus`.
`userRanking()` cron evaluates every active user, grants any newly-qualified ranks,
credits the bonus to `profit_balance`, and promotes the user's `ranking_id` to the
highest rank achieved. `users.rankings` is a JSON array of all ranks ever earned; the
frontend exposes a **ranking badge gallery** showing progress.

### 4.12 Support tickets

Built on `coderflex/laravel-ticket`. Users open tickets, reply, and close them; admin has
a parallel queue with reply and close. Ticket notification templates fire on new
messages.

### 4.13 Notifications (4 channels, all template-driven)

`Traits\NotifyTrait` is used everywhere. Each channel reads a DB row, substitutes
`[[shortcode]]` placeholders, and dispatches:

| Channel | Table | Delivery | Driver selection |
| --- | --- | --- | --- |
| Email | `email_templates` | `MailSend` mailable with banner/salutation/button/footer blocks | SMTP from DB settings |
| SMS | `sms_templates` | `SmsTrait` → **Nexmo** or **Twilio** | `config('sms.default')`, set by plugin |
| Push / in-app | `push_notification_templates` | Pusher broadcast `NotificationEvent` **and** a persisted `notifications` row | Pusher credentials from plugin |
| Admin broadcast | — | `PushNotificationEvent`, `UserNotificationEvent` channels | Pusher |

**Template codes seeded:** `new_user`, `user_investment`, `user_account_disabled`,
`manual_deposit_request`, `kyc_request`, `kyc_action`, `invest_roi`, `investment_end`,
`withdraw_request_user`, `user_manual_deposit_request`, `email_verification`,
`user_password_change`, `admin_forget_password`, `contact_mail`, `user_support_ticket`,
`admin_support_ticket`, `user_mail`, `subscriber_mail`.

Users additionally get a notification centre (`allNotification`, `latestNotification`,
`readNotification`) and an admin-side equivalent, plus selectable **notification sound
tunes** (`set_tunes` table).

---

## 5. Payment gateway ecosystem

**25 gateway drivers** under `modules/Payment/` (autoloaded via `composer.json`
`classmap`), each extending `BaseTxn`:

> Binance · BlockIo · Blockchain · BTCPay Server · Cashmaal · Coinbase · CoinGate ·
> CoinPayments · Coinremitter · Cryptomus · Flutterwave · Instamojo · Mollie · Monnify ·
> NOWPayments · Paymongo · PayPal · Paystack · Paytm · PerfectMoney · Razorpay ·
> SecurionPay · Stripe · TwoCheckout · VoguePay

`Traits\Payment::gatewayMap()` resolves a `gateway_code` string to a driver class and
calls `->deposit()` or `->withdraw()`. Gateways are modelled as:

- `gateways` — the gateway definition + JSON credentials (`type`: `auto` / `manual`)
- `deposit_methods` — a per-currency instance of a gateway with min/max deposit, charge,
  rate, and (for manual) custom form fields + payment instructions

**19 IPN / webhook endpoints** under `GET|POST /ipn/*` (`IpnController`):
coinpayments, nowpayments, cryptomus, paypal, mollie, perfectmoney, paystack,
flutterwave, coingate, monnify, non-hosted-securionpay, coinremitter, btcpay, binance,
blockchain, instamojo, paytm, razorpay, twocheckout.

Three generic return handlers under `/status/{success|cancel|pending}` normalise the
redirect flow for hosted gateways.

Two gateways need **asynchronous confirmation** and use queued jobs:
- `IpnJob` — polls BlockIo for address balance and promotes the transaction.
- `WithdrawUpdateJob` — polls PayPal payout batch status, then confirms or fails the
  withdrawal (and refunds on failure).

Custom-rate handling: `is_custom_rate()` forces USD pricing for nowpayments,
coinremitter, and blockchain.

---

## 6. Scheduled / background work

There is **no Laravel scheduler configuration** — `Console\Kernel::schedule()` is empty.
Instead, everything runs over **HTTP cron endpoints** (documented in `README.txt`):

| Endpoint | Action |
| --- | --- |
| `GET /cron-job/investment` | Pay all due ROI, complete matured plans, refund capital |
| `GET /cron-job/referral` | Evaluate target-based referral milestones |
| `GET /cron-job/user-ranking` | Re-evaluate and grant user ranks + bonuses |
| `GET /cron-job/queue` | Runs `artisan queue:work` (for the two async jobs) |

A `scheduled_task` table exists (migration `2023_07_23_081311_create_scheduled_task.php`)
and a `ScheduledTask` model, but no controller logic is wired to it — `ScheduledTaskController`
is an empty shell.

**Events & jobs:** `UserReferred → RewardUser` (sync), `NotificationEvent` /
`PushNotificationEvent` / `UserNotificationEvent` (broadcast), `IpnJob`, `WithdrawUpdateJob`.

---

## 7. Admin control panel

Loaded from `routes/admin.php` under a configurable prefix. Every controller declares
`permission:` middleware, and `Gate::before` grants the `Super-Admin` role everything.

### 7.1 Dashboard & analytics
Counters for deposits / withdrawals / investments / transfers / referrals / tickets /
staff / gateways / pending KYC / pending manual deposits / pending withdrawals;
5 latest users and 5 latest investments; **date-range-filtered time series** for
deposit, investment, withdraw, and profit (AJAX endpoint returns JSON);
scheme distribution chart (investments grouped by plan); browser, platform, and
top-5 country breakdowns from login activity.

### 7.2 People
- **Customers** — DataTables listing (all / active / disabled), edit profile,
  status toggle, password reset, **add/subtract balance on a chosen wallet**,
  **login as customer**, per-user transaction view, and bulk or single email
  broadcast. Demo mode masks emails/usernames via the `safe()` helper.
- **Staff** — admin user CRUD (no create view; seeded/added otherwise).
- **Roles & permissions** — Spatie role CRUD with permission assignment.

### 7.3 Money operations
- **Schemas** (investment plans) — full CRUD, see §4.4.
- **Schedules** — payout intervals (hours) referenced by schemas.
- **Transactions** — all transactions, optionally filtered by user.
- **Investments** — all investments, optionally filtered by user.
- **Profits** — all `interest` transactions.
- **Deposits** — gateway method CRUD per type (auto/manual), pending manual queue,
  full history, approve/reject.
- **Withdrawals** — method CRUD, off-day schedule, pending queue, history, approve/reject.
- **Gateways** — enable/disable automatic gateways, edit credentials per gateway,
  list supported currencies.
- **Referrals** — referral program CRUD, referral targets, and the multi-level
  commission ladder (deposit / investment / profit).
- **Rankings** — rank CRUD with thresholds, icon, and bonus.

### 7.4 Content management
- **Pages** — create/edit/delete dynamic pages; each page is stored per-locale.
- **Landing sections** — the homepage is assembled from `landing_pages` +
  `landing_contents` records, one per block: `hero`, `about`, `howitworks`,
  `calculation`, `schema`, `gateway`, `counter`, `recent`, `whychooseus`, `faq`, `cta`,
  `blog`, `newsletter`, and a separately-managed `footer`. Each is orderable via `short`
  and has per-locale content CRUD.
- **Page settings** — JSON-driven toggles (e.g. whether registration asks for
  username / country / phone).
- **Blog** — full CRUD (categories + posts), public blog index and detail pages.
- **Navigation** — menu builder with header/footer/both placement, custom URLs or
  page links, position reordering, and **per-locale menu label translation**.
- **Footer content** — editable footer block.
- **Social links** — CRUD + position ordering.
- **Subscribers** — newsletter list from the homepage form, with bulk mail send.

### 7.5 Presentation
- **Site themes** — one active at a time out of the shipped set
  (`default`, `investify`, `mining_invest`, `money_invest`, plus a `hardrock` row in
  seed data). Switching rebinds the `frontend::` view namespace.
- **Dynamic landing themes** — upload a **ZIP theme** (max ~30 MB) which is extracted
  and served as a separate `landing_theme.{name}` view namespace, entirely bypassing the
  section builder when active. Supports activate / status toggle / delete.
- **Custom CSS** — admin-editable stylesheet stored in `custom_css` and injected site-wide.
- **GDPR cookie consent** — toggle, text, button label, and URL (all from settings).

### 7.6 Configuration
Settings are schema-driven from `config/setting.php`, which declares element type,
data type, validation rule, and default per field. Six sections:

| Section | Controls |
| --- | --- |
| `global` | logo, favicon, admin-login cover, **admin URL prefix**, currency type/currency/symbol, timezone, **referral mode**, referral code length, home redirect, site title, site + support email |
| `permission` | email verification, KYC verification, 2FA, account creation, user deposit, user withdraw, send money, sign-up referral, referral-signup bonus, deposit/investment referral bounty, debug mode, site animation, back-to-top |
| `fee` | signup bonus, referral bonus, send-money charge + min/max, wallet-exchange charge, **daily limits** for send money, wallet exchange, withdraw, and investment cancellation |
| `mail` | from name/address, driver, SMTP host/port/encryption/username/password, with a live **SMTP connection test** |
| `site_maintenance` | maintenance mode toggle, secret key bypass, title, and message |
| `gdpr` | consent banner status, text, button label and URL |

A `Setting` model provides `add/set/get/has/remove` with **forever-cached** lookups
(`Cache::rememberForever('settings.all')`) and type-casting by declared data type.

### 7.7 Plugins
`plugins` table + `PluginServiceProvider`. Types:
- **SMS** — Nexmo, Twilio (mutually exclusive: activating one deactivates the other;
  the winner becomes `config('sms.default')`).
- **Notification** — Pusher (credentials injected into `broadcasting.connections.pusher`).
- **System** — Google reCaptcha, Google Analytics, Facebook Pixel, Tawk.to
  (rendered as `frontend::{theme}/plugin/*` partials).

### 7.8 Localisation
- `languages` table with a single default locale; `LanguageController` CRUD.
- `joedixon/laravel-translation` integration: **keyword translation editor** per
  language, and a `syncMissing` action that runs the package's
  `SynchroniseMissingTranslationKeys` command.
- Navigation labels and landing content are translated per-locale as JSON columns.
- Frontend language switcher writes the session locale.

### 7.9 Admin housekeeping
Profile edit, password change, **application info** screen (PHP/Laravel versions,
debug mode, site mode, DB port), **clear cache** (`artisan optimize:clear`),
subscriber broadcast, and logout (exempt from demo lock).

---

## 8. Public site

- Homepage: either the **section builder** output, an **uploaded landing theme**, or a
  **redirect** to another URL (`home_redirect` setting).
- Section partials include an **investment profit calculator**, live counters,
  schema/plan showcase, supported-gateway strip, recent transactions, why-choose-us,
  FAQ, CTA, blog teaser, and newsletter signup.
- Static pages served by a single invokable controller with a route whitelist:
  `schema`, `how-it-works`, `about-us`, `faq`, `rankings`, `blog`, `contact`,
  `privacy-policy`, `terms-and-conditions`.
- Dynamic pages at `/page/{section}`; blog detail at `/blog/{id}`.
- Contact form → `contact_mail` template.
- Newsletter subscription with unique-email validation.
- Public ranking badge page.
- GDPR cookie banner.

---

## 9. Data model (55 tables)

**Identity & access:** `users`, `admins`, `roles`, `permissions`, `model_has_roles`,
`model_has_permissions`, `role_has_permissions`, `password_resets`,
`personal_access_tokens`, `login_activities`

**Money:** `transactions`, `invests`, `schemas`, `schedules`, `deposit_methods`,
`withdraw_methods`, `withdraw_accounts`, `withdrawal_schedules`

**Referral & rewards:** `referrals`, `referral_programs`, `referral_links`,
`referral_relationships`, `referral_targets`, `level_referrals`, `rankings`

**Payments:** `gateways`

**Content:** `pages`, `page_settings`, `landing_pages`, `landing_contents`, `blogs`,
`categories`, `labels`, `navigations`, `socials`, `custom_css`, `themes`

**Comms:** `email_templates`, `sms_templates`, `push_notification_templates`,
`notifications`, `messages`, `subscriptions`, `set_tunes`

**Ops:** `settings`, `plugins`, `languages`, `kycs`, `tickets`, `jobs`, `failed_jobs`,
`cache`, `cache_locks`, `migrations`, `scheduled_task`

The 19 migrations (dated `2023_05_10` → `2023_07_27`) layer incremental changes on top
of the `DB/hyiprio.sql` baseline — adding SMS/push templates, notifications, notification
tunes, login activities, deposit methods, themes, jobs, and scheduled tasks.

---

## 10. Security model

| Layer | Implementation |
| --- | --- |
| XSS input filtering | `XSS` middleware (`strip_tags` recursive), disabled selectively on admin content routes via `->withoutMiddleware('XSS')` |
| HTML sanitisation for rich text | `mews/purifier` |
| SQL injection | Eloquent / query builder throughout |
| CSRF | `VerifyCsrfToken` on the web group |
| Mass assignment | `$fillable` / `$guarded` on all models |
| Secret storage | `google2fa_secret` encrypted via Eloquent `Attribute` cast |
| Admin RBAC | Spatie permissions on every admin controller action |
| Superuser bypass | `Gate::before` for `Super-Admin` |
| Signed URLs | temporary signed route for deposit success and email verification |
| Demo mode | `APP_DEMO` env blocks mutations and masks PII |
| Security headers | `SecureHeaders` middleware |
| File access | `.htaccess` denies `.env` and `laravel.log` |
| Rate limiting | `throttle:api` (60/min) on the API group |

---

## 11. Frontend build & developer tooling

- **Vite** + Tailwind + Alpine (`package.json`); `npm run dev` / `npm run build`.
- Admin assets are pre-compiled under `assets/backend/` (Bootstrap, DataTables,
  Chart.js, Summernote, CodeMirror, Choices, jQuery UI, animate.css).
- **Pusher** + Laravel Echo for live notifications.
- **Testing:** PHPUnit (`phpunit.xml`) with Feature tests covering authentication,
  registration, email verification, password reset, and password confirmation. No
  financial/domain tests.
- **Static analysis:** Larastan/PHPStan at `phpstan.neon` with a committed
  `phpstan-baseline.neon`.
- **Style:** Laravel Pint (`pint.json`).
- **Updater:** `laraupdater` config is present for over-the-air self-updating.
- **Installer:** `remotelywork/installer` provides a license-gated web installer
  (referenced by `README.txt` and the `install_check` middleware alias).

---

## 12. Operational notes

From `README.txt`, a production deployment requires:

```
GET /cron-job/investment     # pay ROI, mature plans, refund capital
GET /cron-job/referral       # milestone referral payouts
GET /cron-job/user-ranking   # rank evaluation + bonuses
artisan queue:work --daemon  # async BlockIo + PayPal payout confirmations
```

PHP ≥ 8.1; the installer asks for a license key before it will provision the database.
The installer also underpins the `dbConnectionCheck()` guards that keep every provider
from touching the database before installation completes.

---

## 13. Observations / rough edges found while mapping

These are notes on the current state of the code, not part of the intended feature set:

1. **`trans` and `install_check` middleware aliases** are applied to every route group in
   `RouteServiceProvider` but are not defined in `app/Http/Kernel.php:: $routeMiddleware`.
   They must be registered by a vendor package (vendor/ is not committed), otherwise the
   app cannot route.
2. **`gatewayMap()` maps `voguepay` to `BinanceTxn`** — VoguePay has a directory under
   `modules/Payment/` but no driver of its own. Conversely, five gateways with drivers
   (`Cashmaal`, `Coinbase`, `Paymongo`, `Stripe`, `VoguePay`) have **no `/ipn/*`
   endpoint**, so they settle only through the synchronous `/status/*` return handlers.
3. **`ScheduledTaskController` is empty** and the `scheduled_task` table is unused, even
   though a migration exists for it.
4. **All scheduling is HTTP-based**, so the cron endpoints are unauthenticated GET
   routes — worth rate-limiting or token-protecting in production.
5. **`InvestController@investCancel` reads the send-money day limit**
   (`setting('send_money_day_limit', 'fee')`) instead of the dedicated
   `investment_cancellation_daily_limit` setting that exists in config.
6. **The withdraw daily-limit query is not scoped to the user.** In
   `WithdrawController@withdrawNow` the chain is
   `->where('user_id', ...)->where('type', Withdraw)->orWhere('type', WithdrawAuto)->whereDate(...)`,
   so the `orWhere` escapes the `user_id` constraint and the count includes other users'
   auto-withdrawals. The same `orWhere` pattern is safe in `walletExchangeNow` only
   because there is a single type.
7. **Balance mutation is not transactional** in the send-money, wallet-exchange, and
   withdraw paths — balances are decremented and transactions written as separate
   statements with no DB transaction (or row lock) wrapping them.
8. `Backend\SettingController::siteSetting()` and `mailSetting()` are declared `static`,
   unlike every other admin controller method, even though they are routed as normal
   controller actions.
9. `Txn::new()` takes a 15-parameter positional argument list, and the gateway helpers
   (`gateway_info`, `is_custom_rate`) hard-code gateway-code strings rather than reading
   a column from the `gateways` table.
10. The admin `applicationInfo()` screen **hard-codes** `'PHP Version' => 8.1` and
    `'Laravel Version' => 9.3` instead of reading `PHP_VERSION` and `App::version()`.

---

## 14. Feature inventory (quick index)

**Public:** home (3 render modes) · 9 static pages · dynamic pages · blog + detail ·
contact form · newsletter · ranking gallery · language switcher · light/dark mode ·
GDPR banner

**User account:** register · login · email verify · password reset & change · 2FA ·
profile edit · dashboard with 13 metrics · notifications centre · ranking badges ·
login activity

**User money:** deposit (auto + manual) · withdraw (auto + manual + saved accounts +
off-days) · invest (3 funding sources) · investment logs · investment cancellation ·
send money P2P · wallet exchange · full transaction history with search

**Admin:** analytics dashboard · customer management (incl. login-as and balance
adjust) · staff & roles/permissions · KYC forms + review queue · investment plans +
schedules · transactions / investments / profits · deposit & withdraw methods + queues
+ schedules · gateway credentials · referral programs, targets, level ladder · rankings
· pages, landing sections, blog, navigation (+translation), socials, footer · site &
landing themes (ZIP upload) · custom CSS · 6 setting sections · plugin management ·
language + keyword translation · email/SMS/push template editors · notification tunes ·
subscriber broadcast · system info · cache clear

**Platform:** 25 payment gateways · 19 IPN endpoints · 3 queued cron jobs · 2 async
confirmation jobs · multi-channel templated notifications · multi-level and
milestone referral engines · rank engine · KYC engine · configurable limits and fees
