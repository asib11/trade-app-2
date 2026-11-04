# Bicrypto Exchange Platform

Bicrypto is a modular cryptocurrency trading and digital-finance platform built
for operators who want to run their own exchange, wallet, investment, payment,
content, and customer-management services.

This repository contains the web application, API server, relational data
models, asynchronous workers, exchange integrations, and Docker development
environment. It can be operated as:

- A spot-trading exchange connected to a supported liquidity provider.
- A controlled internal marketplace with operator-managed currencies and pairs.
- A broader digital-finance platform with wallets, deposits, withdrawals,
  transfers, investments, staking, P2P, futures, binary products, and optional
  ecosystem modules.

> **Project status:** This is an application codebase, not a turnkey
> production deployment. Payment providers, blockchain nodes, exchange
> credentials, email delivery, compliance workflows, security hardening, and
> operational monitoring must be configured before real users or funds are
> introduced.

## Table of contents

- [Business overview](#business-overview)
- [Core user journeys](#core-user-journeys)
- [Operator and admin workflows](#operator-and-admin-workflows)
- [Feature catalogue](#feature-catalogue)
- [System architecture](#system-architecture)
- [Data and business domains](#data-and-business-domains)
- [Authentication and authorization](#authentication-and-authorization)
- [Exchange and market lifecycle](#exchange-and-market-lifecycle)
- [Local Docker setup](#local-docker-setup)
- [Environment configuration](#environment-configuration)
- [Common operational tasks](#common-operational-tasks)
- [Development workflow](#development-workflow)
- [Production readiness](#production-readiness)
- [Known limitations and troubleshooting](#known-limitations-and-troubleshooting)
- [Repository map](#repository-map)
- [License and customization](#license-and-customization)

## Business overview

### Business purpose

The platform provides the operational foundation for a branded crypto-finance
business. An operator can configure tradable assets, connect liquidity or
exchange providers, manage user accounts and compliance, process financial
transactions, publish content, and expose APIs to customers or partner
applications.

The business model is intentionally configurable. Depending on enabled
modules, revenue may come from:

- Trading fees on spot, futures, or other order products.
- Deposit, withdrawal, transfer, or payment processing fees.
- Investment, staking, binary, forex, or P2P product commissions.
- Spread or markup on operator-controlled markets and currencies.
- Subscription, affiliate, referral, or partner programmes.
- E-commerce and payment products.

The code does not by itself establish a legal business model, custody policy,
licensing position, consumer-protection policy, or financial control framework.
Those are operator responsibilities.

### How the owner can benefit and earn revenue

The owner benefits by operating the platform as a financial-product business,
not merely by installing the software. Revenue depends on customer activity,
liquidity, trust, reliable operations, and compliance. The platform can support
several earning models:

#### 1. Trading fees

Charge a maker/taker or transaction fee when customers place and fill spot,
futures, binary, or other supported orders. A simple model is:

```text
gross trading revenue = traded volume x fee rate
net trading revenue   = gross trading revenue - liquidity, provider, and
                         operating costs
```

Fees should be transparent, configurable, recorded in the transaction or
profit ledgers, and reconciled against provider and wallet balances. Do not
assume that every submitted order produces revenue; cancelled and rejected
orders normally do not generate a completed-trade fee.

#### 2. Deposit, withdrawal, and payment fees

The owner may charge a fixed fee, percentage fee, or provider-cost markup for
fiat deposits, crypto deposits, withdrawals, payment intents, and internal
transfers. The fee must cover actual network, gateway, compliance, and
operational costs without misleading customers.

#### 3. Spread and liquidity revenue

For owner-controlled markets, revenue can come from the spread between buy and
sell prices or from a controlled market-making operation. This requires:

- A reliable price-discovery method.
- Sufficient inventory on both sides of the market.
- Clear disclosure that the operator provides liquidity or is a
  counterparty.
- Controls against wash trading, artificial pricing, and conflicts of
  interest.

The existing market table can list a pair, but it does not automatically
create liquidity, a matching engine, or a profitable spread strategy.

#### 4. Investment, staking, and product commissions

Investment plans, staking pools, forex, binary, futures, P2P, and related
products can generate management fees, performance fees, commissions, or
spreads. Each product needs explicit terms covering risk, calculation method,
settlement, losses, withdrawal restrictions, and customer eligibility.

#### 5. P2P and marketplace commissions

The owner can charge fees on completed P2P trades, promoted offers, escrow,
payment-method usage, affiliate referrals, or marketplace services. Revenue
should be charged only according to published rules and should account for
disputes, refunds, fraud losses, and support costs.

#### 6. Subscription and account tiers

Premium customers or business accounts can be charged recurring fees for
lower trading rates, higher limits, reporting, API access, institutional
features, or priority support. The entitlement and billing state must be
enforced consistently in both the frontend and backend.

#### 7. Partner and affiliate revenue

Referral programmes can reward customers or partners for bringing qualified
users and activity. The owner may earn from the resulting trading or product
volume, but referral terms, attribution, abuse controls, and payout limits
must be documented.

#### 8. White-label or platform services

An operator can provide branded deployments, API access, hosted market
services, or operational services to other businesses. This changes the
business from a single exchange into a software or managed-service provider
and requires tenant isolation, billing, support, and data-protection design
that are not supplied automatically by this repository.

### Revenue example

For illustration only, if customers complete `1,000,000` units of monthly
trading volume at an average fee of `0.10%`:

```text
gross trading fees = 1,000,000 x 0.001 = 1,000 units
```

The owner must subtract provider fees, rebates, market-making losses, payment
costs, infrastructure, staff, support, compliance, taxes, refunds, fraud, and
losses from outages or bad debt. This is not a guaranteed return or a forecast.

### Owner responsibilities and costs

The owner is responsible for more than collecting fees:

- Acquire and retain customers through product quality and support.
- Provide real liquidity or connect a trustworthy liquidity source.
- Maintain accurate balances, order state, and financial reconciliation.
- Secure hot wallets, API keys, admin accounts, and customer data.
- Operate KYC/AML, sanctions screening, transaction monitoring, and fraud
  controls where required.
- Fund blockchain fees, payment-provider charges, infrastructure, staff,
  audits, legal work, insurance, and taxes.
- Publish fees, risks, privacy terms, user agreements, and dispute procedures.
- Maintain backups, monitoring, incident response, and customer communication.

The software can automate parts of these workflows, but the owner remains
economically and legally responsible for the service.

### Operating roles

The main business actors are:

1. **Visitor** — views public pages, markets, blog content, and product
   information.
2. **Customer** — registers, authenticates, completes profile/KYC steps, funds
   wallets, trades, transfers value, and manages orders.
3. **Support or compliance staff** — reviews tickets, KYC applications,
   comments, users, and operational exceptions according to assigned
   permissions.
4. **Finance or operations staff** — manages currencies, providers, wallets,
   deposits, withdrawals, fees, investments, and orders.
5. **Super administrator** — controls configuration, roles, permissions,
   integrations, content, system operations, and the admin dashboard.
6. **External providers** — exchanges, payment gateways, blockchain nodes,
   fiat-rate services, mail/SMS providers, and optional AI or social-login
   services.

## Core user journeys

### Customer onboarding

1. The visitor opens the public site and selects registration.
2. The account is created with the configured default user role.
3. Optional email verification, Google login, wallet login, or two-factor
   authentication can be enabled.
4. The customer enters the user area, reviews the profile, and completes KYC
   when required.
5. The customer receives access to wallets, markets, orders, investments, and
   other enabled products according to account status and permissions.

The standard email/password login endpoint is:

```http
POST /api/auth/login
```

The browser login page is `/login`. Sessions use access and refresh tokens,
session storage, and HTTP cookies. The frontend middleware validates the access
token and enforces route permissions.

### Wallet funding and financial activity

1. The customer selects a wallet and currency.
2. A deposit method or gateway creates the required deposit instructions or
   payment intent.
3. The transaction is recorded and associated with the customer wallet.
4. Deposits may require confirmation, review, or provider callbacks.
5. Withdrawals follow a similar workflow with method selection, fee
   calculation, validation, optional KYC/2FA checks, and operational approval.
6. Internal transfers move value between supported wallets or users without
   requiring an external blockchain transaction.

### Spot trading

1. The operator configures a provider and imports its available markets, or
   creates a controlled internal market.
2. Active market rows become visible to the trading interface.
3. The customer selects a pair, reviews candles, ticker, order book, and
   balances, then submits an order.
4. The exchange order service validates balances, price, amount, fees, and
   market status.
5. Orders are stored and updated as open, partially filled, completed,
   cancelled, or rejected.
6. Background processes synchronize prices, provider state, balances, and
   related financial records.

An empty `exchange_market` table produces an empty market page. Provider
records alone do not create visible trading pairs.

### Investments and yield products

Operators can define plans and durations, then customers can allocate funds
into eligible investment products. The platform records plans, user
investments, durations, returns, status changes, and related profit data.
Scheduled jobs process pending activities and calculate or distribute results.

### P2P, staking, futures, and optional products

The repository includes separate modules for:

- P2P offers, payment methods, trades, escrow, disputes, commissions, and
  reviews.
- Staking pools, durations, logs, and reward processing.
- Futures markets, positions, and orders.
- Binary practice and real orders.
- Forex accounts, plans, signals, and investments.
- ICO projects, phases, allocations, contributions, and tokens.
- NFT assets, collections, auctions, bids, comments, likes, and transactions.
- E-commerce products, orders, discounts, shipping, reviews, and wishlists.
- Affiliate/referral programmes and rewards.

These modules may depend on extensions, permissions, provider configuration,
or additional operational data. Enabling a menu item does not automatically
make a product production-ready.

## Operator and admin workflows

The admin area is available at:

```text
http://localhost:3000/admin
```

`/admin` redirects to `/admin/dashboard`.

### CRM

- Manage users and account status.
- Create roles and assign permissions.
- Review KYC applications and maintain KYC templates.
- Handle support tickets.
- Create and manage customer API keys.

### Finance

- Configure fiat and spot currencies.
- Configure deposit gateways and deposit methods.
- Configure exchange providers and markets.
- Review exchange, binary, ecosystem, and futures orders.
- Manage investment plans, durations, and history.
- Review transactions, profit, wallets, deposits, and withdrawals.

### Content and customer experience

- Manage pages, posts, categories, tags, comments, authors, sliders, and
  media.
- Configure application settings, themes, notifications, and announcements.
- Maintain localized strings and supported languages.

### System operations

- View service health.
- Inspect logs.
- Run configured cron tasks.
- Manage database backup and migration operations.
- Manage extensions and system settings.
- Check application version information.

The admin menu is permission-aware. A role must have the relevant permission;
the `Super Admin` role bypasses the route permission checks.

## Feature catalogue

### Trading and market data

- Spot exchange markets and orders.
- Market, ticker, chart, and order-book APIs.
- Watchlists.
- CCXT-based exchange provider integration.
- Exchange balance and provider administration.
- Optional futures and binary trading.

### Money movement

- Fiat and spot wallets.
- Deposit and withdrawal methods.
- Payment intents and gateway integrations.
- Internal transfers.
- Transaction history and fee/profit records.
- Wallet PnL and scheduled financial processing.

### Identity, compliance, and support

- Email/password authentication.
- Optional Google, wallet, and chat-based login flows.
- Email verification and password reset.
- Optional OTP/2FA flows.
- KYC templates and applications.
- Role and permission management.
- Support tickets, notifications, and account deletion.

### Content and localization

- CMS pages and blog content.
- Authors, categories, tags, comments, and sliders.
- Media uploads, including HEIC conversion.
- Next.js locale configuration and translation namespaces.
- The default configuration includes a broad list of locale codes.

### Integrations

The project contains integration points for:

- Binance, KuCoin, XT, and other exchange aliases supported by the exchange
  utility layer.
- Stripe, PayStack, PayPal, and other payment-related providers.
- Twilio SMS.
- SMTP, Gmail, SendGrid, local sendmail, and other mail modes.
- ScyllaDB/Cassandra for high-volume order and candle data.
- Ethereum-compatible networks and blockchain modules.
- Open Exchange Rates or ExchangeRate API.
- Google reCAPTCHA, Google OAuth, Google Analytics, and WalletConnect.
- OpenAI and optional extension-specific providers.

## System architecture

### Runtime components

| Component | Responsibility | Default port |
|---|---|---:|
| Next.js frontend | Public site, user portal, admin UI, middleware, API proxy | 3000 |
| TypeScript backend | Authentication, business APIs, admin APIs, integrations | 4000 |
| MySQL | Users, roles, settings, currencies, orders, content, finance records | 3306 |
| Redis | Sessions, queues, cache, rate limits, ticker/cache data | 6379 internally |
| ScyllaDB | Cassandra-compatible high-volume trading/order/candle storage | 9042 |

The Docker Compose file exposes Redis on host port `6381` while containers use
the service name `redis` and port `6379`. The backend uses `mysql`, `redis`,
and `scylla` as internal hostnames.

### Request flow

1. A browser requests a Next.js page.
2. Frontend middleware checks cookies, verifies the access token, loads roles
   and permissions, and applies route protection.
3. Browser API calls use `/api/...`.
4. Next.js rewrites proxy API calls to the backend service.
5. The backend validates authentication and permissions, executes business
   logic, and reads or writes MySQL, Redis, ScyllaDB, or external providers.
6. Background cron jobs and queue workers process asynchronous work.

### Data responsibilities

MySQL is the system of record for most business entities. Redis is used for
short-lived state and asynchronous processing. ScyllaDB is used for
high-volume trading data where Cassandra-compatible storage is appropriate.
External providers remain authoritative for provider-owned balances, market
metadata, payment status, blockchain confirmations, and similar data.

## Data and business domains

The Sequelize models cover the following major domains:

- **Identity:** users, roles, permissions, sessions, two-factor records,
  one-time tokens, API keys.
- **Exchange:** providers, markets, currencies, orders, watchlists, binary
  orders, futures markets.
- **Finance:** wallets, wallet data, deposits, withdrawals, transactions,
  transfers, invoices, payment intents, fees, profit, currencies.
- **Investment:** plans, durations, investments, staking pools, staking logs.
- **Compliance:** KYC templates and applications.
- **Customer operations:** support tickets, notifications, announcements.
- **CMS:** pages, posts, authors, categories, tags, comments, sliders.
- **Extensions:** ecosystem wallets, ledgers, blockchains, tokens, NFTs,
  P2P, forex, ICO, ecommerce, affiliate, AI investment, and mailwizard data.

The backend API follows a file-based route convention under
`backend/api`. A route such as `backend/api/exchange/market/index.get.ts`
maps to `GET /api/exchange/market`.

## Authentication and authorization

### Login

The seeded local administrator is defined by the
`seeders/20240402234654-superAdmin.js` seeder:

```text
Email: superadmin@example.com
Password: 12345678
```

This credential is for local development only. Change or remove it before
sharing the environment or deploying it.

### Authorization model

- Public routes do not require authentication.
- User routes require a valid session.
- Admin routes require a valid session and a role permission.
- `Super Admin` is treated as an unrestricted administrative role.
- Route permissions are declared in frontend pages and centralized in
  `src/utils/gate.ts`.
- Backend operations also declare authentication and permission metadata.

### Security configuration

The application depends on separate access, refresh, reset, and verification
token secrets. Generate unique high-entropy values for every deployment.
Never commit `.env`, provider secrets, private keys, or production credentials.

## Exchange and market lifecycle

The market system has a distinct lifecycle:

1. **Provider record** — the exchange exists in the `exchange` table.
2. **Provider credentials** — API credentials are configured for the selected
   provider alias.
3. **Provider activation** — the provider is enabled for application use.
4. **Market import or creation** — markets are written to `exchange_market`.
5. **Market activation** — selected market rows are enabled.
6. **Ticker and candle processing** — background jobs populate cache and
   historical data.
7. **Customer trading** — the frontend can display and trade active pairs.

For a provider-backed market, use read-only API credentials where possible.
Trading and withdrawal permissions should only be enabled deliberately.

For an operator-owned market, the operator must define:

- Supported currencies and precision.
- Base and quote assets.
- Price and amount limits.
- Fees and order rules.
- Liquidity or matching strategy.
- Price discovery and market surveillance.
- Settlement, custody, and accounting rules.

The existing application provides administration and trading infrastructure,
but it does not remove the need for a sound market-making, custody, and
financial-control design.

### How a normal market works

A normal spot market is represented as a base currency and quote currency.
For example:

```text
BTC/USDT
currency = BTC
pair     = USDT
```

The customer-facing market API returns only active rows from
`exchange_market`. The frontend then uses the symbol to request ticker,
order-book, chart, and order data. A market is therefore visible only when:

1. The currency and quote asset are configured.
2. A row exists in `exchange_market`.
3. The row has `status = true`.
4. The price/ticker source is available.
5. The order service can validate balances, precision, limits, and fees.

The admin market page is:

```text
/admin/finance/exchange/market
```

The **Import** action calls
`GET /api/admin/finance/exchange/market/import`. It loads symbols from the
active CCXT provider, stores precision, amount/price/cost limits and maker/taker
fees in market metadata, and creates inactive rows. It can also remove rows
that are no longer returned by the provider. Operators must review and enable
the desired rows after import.

The current exchange-market UI supports importing, editing metadata, changing
trending/hot flags, enabling/disabling, and deleting markets. It does not
provide a general “create arbitrary market” button because provider-backed
markets are expected to come from the provider's market definition.

### Adding an operator-owned custom market

There are two different meanings of “custom market”:

#### A. Custom listing or internal token market

For an operator-controlled token or ecosystem market, configure the asset and
use the ecosystem market module. Ecosystem markets are stored in
`ecosystem_market` and are returned by
`GET /api/exchange/market?eco=true` when their status is active. This is
appropriate for displaying operator-managed token pairs and ecosystem
markets.

The operator must still provide:

- The currency and quote asset.
- Decimal precision and amount/price limits in metadata.
- A price source or market-making process.
- Liquidity and settlement rules.
- Wallet, deposit, withdrawal, and accounting behavior.

Creating a database row alone does not create liquidity or a working matching
engine. If the pair must use the standard spot order flow, the order,
order-book, ticker, chart, and settlement services must all recognize the
custom symbol. Test this end to end before enabling customer trading.

#### B. A market not supplied by an external exchange

For a fully independent market, implement a first-party market-data and
matching path rather than placing fake credentials in the provider settings.
At minimum, the implementation needs:

1. A market definition and active/inactive lifecycle.
2. A source of truth for prices or an order-matching engine.
3. Order validation, reservation, matching, fills, cancellation, and fees.
4. Order-book and ticker APIs, plus chart/candle generation.
5. Wallet debits, credits, settlement, and reconciliation.
6. Admin controls, audit logs, rate limits, and market surveillance.
7. Tests for precision, limits, partial fills, duplicate requests, and failure
   recovery.

The existing `exchange_market` schema contains the pair, status, flags, and
provider-style metadata, but it is not by itself a complete custom exchange
engine.

### Prediction markets versus binary trading

The repository includes **binary trading**, which is a price-direction
product, not a general event prediction market. Binary orders support
directions such as `RISE`, `FALL`, `HIGHER`, `LOWER`, `CALL`, `PUT`, `UP`, and
`DOWN`, with time- or tick-based settlement and statuses such as `WIN`,
`LOSS`, `DRAW`, and `CANCELED`. The relevant API is:

```http
POST /api/exchange/binary/order
```

Binary trading is controlled by `NEXT_PUBLIC_BINARY_STATUS` and is available
through the binary pages and admin order screens. It normally settles against
an underlying price feed and should not be described to customers as an
independent event-betting or prediction market.

A true prediction market—such as “Will event X happen by date Y?”—is not
implemented by the existing exchange-market table. To add one safely, create
a separate prediction domain rather than overloading `currency/pair`. A
typical design includes:

- `prediction_market`: question, description, source, opening/closing time,
  resolution time, status, and resolution authority.
- `prediction_outcome`: outcome labels such as `YES` and `NO`, or multiple
  mutually exclusive outcomes.
- `prediction_position` or `prediction_order`: user stake, price/probability,
  quantity, fees, and lifecycle status.
- `prediction_resolution`: final outcome, evidence/source, resolver, and
  immutable resolution timestamp.
- A payout and accounting service that settles winners and handles refunds,
  invalidation, disputes, and rounding.

The user journey would be:

1. Admin creates and publishes a question with explicit rules.
2. Users review outcomes, probability/price, fees, closing time, and
   resolution source.
3. Users buy or sell outcome positions, or stake on an outcome.
4. Trading closes at the configured cutoff.
5. An authorized resolver records the result with an audit trail.
6. The settlement job pays winning positions and returns funds for cancelled
   or invalid markets.

Do not implement prediction settlement by editing binary order status directly.
Prediction markets require independent rules for oracle data, conflicts of
interest, market cancellation, responsible-gaming controls, jurisdictional
restrictions, and customer disclosures. Obtain legal and compliance review
before enabling real-money prediction products.

## Local Docker setup

### Prerequisites

- Docker Engine with Docker Compose v2.
- At least 4 GB of available memory for the complete local stack.
- Node.js 20 and pnpm 10 are recommended for host-side development.
- Git is required during the image build because one dependency is sourced from
  Git.

Node.js 20 is recommended because the pinned `uWebSockets.js` dependency does
not reliably support newer Node versions.

### Configure the environment

Create a local environment file:

```bash
cp .env.example .env
```

For Docker, the Compose file overrides internal service connectivity. Do not
change the host-facing values to `localhost` for container-to-container
connections; Docker services must use `mysql`, `redis`, `scylla`, and
`backend`.

At minimum, review these values:

- `APP_ACCESS_TOKEN_SECRET`
- `APP_REFRESH_TOKEN_SECRET`
- `APP_RESET_TOKEN_SECRET`
- `APP_VERIFY_TOKEN_SECRET`
- Database name and credentials.
- Mail settings if email delivery is required.
- Exchange credentials if provider-backed markets are required.
- Blockchain RPC values if blockchain modules are enabled.

### Start the stack

```bash
docker compose up -d --build
```

Check service status:

```bash
docker compose ps
```

View logs:

```bash
docker compose logs -f backend
docker compose logs -f frontend
```

Open the application:

```text
http://localhost:3000
```

Backend API:

```text
http://localhost:4000
```

Stop the stack without deleting persistent volumes:

```bash
docker compose down
```

To delete local database, Redis, and Scylla data, use the destructive volume
command only when a full local reset is intended:

```bash
docker compose down -v
```

### Seed initial data

The backend startup synchronizes Sequelize models. To apply the seeders:

```bash
docker compose exec backend pnpm seed
```

Seeders provide roles, permissions, initial settings, providers, and the local
development administrator. Existing records may cause later seed runs to skip
some inserts.

## Environment configuration

`.env.example` is the configuration template. The local `.env` file is
deployment-specific and must remain private.

### Application

- `NEXT_PUBLIC_SITE_URL`, site name, description, language, theme, and layout.
- `NEXT_PUBLIC_FRONTEND` and maintenance mode.
- Feature switches for binary trading, blog, Google services, 2FA, and other
  modules.

### Database and queues

- `DB_*` values describe the relational database.
- Docker Compose supplies container overrides for MySQL.
- Redis is used for sessions, cache, queues, and rate limiting.
- Scylla connection points and datacenter are supplied by Compose.

### Integrations

Provider credentials are optional until their feature is enabled. Placeholder
values are not valid credentials. A missing provider key should normally make
that service unavailable; it does not create test market data.

## Common operational tasks

### Make markets appear

If `/market` is empty:

1. Confirm the backend is running.
2. Confirm an exchange provider exists and is active.
3. Configure valid provider credentials, or implement an internal market data
   source.
4. Import provider markets through the admin exchange workflow.
5. Enable selected rows in `exchange_market`.
6. Wait for ticker and candle jobs to populate Redis and ScyllaDB.
7. Refresh the market page.

Useful diagnostic query:

```bash
docker compose exec mysql mysql -uroot -proot v4 \
  -e "SELECT COUNT(*) AS active_markets FROM exchange_market WHERE status=1;"
```

The ticker endpoint may return `404` when the Redis ticker cache has not been
populated. This is different from a frontend routing failure.

### Change provider settings

Update the relevant `APP_<PROVIDER>_API_KEY`, secret, and passphrase values in
`.env`, then rebuild or restart the backend:

```bash
docker compose up -d --build backend
```

Do not grant withdrawal permissions to an exchange API key unless the business
requires it and the operational controls are ready.

### Inspect service health

The admin dashboard includes System Status checks for email, SMS, Scylla,
Google Translate, fiat-rate services, Stripe, and configured blockchain
networks. A service marked Down usually means its optional credentials or
endpoint are absent; it does not necessarily mean the core application is
unhealthy.

### Test authentication

```bash
curl -i -X POST http://localhost:3000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"superadmin@example.com","password":"12345678"}'
```

Use the browser for normal cookie/session handling.

## Development workflow

### Install dependencies

```bash
corepack enable
corepack prepare pnpm@10.34.9 --activate
pnpm install
```

### Run frontend and backend locally

```bash
pnpm dev
pnpm dev:backend
```

Local development requires MySQL, Redis, and ScyllaDB to be reachable with the
host-side values in `.env`. Docker Compose is usually simpler because it
provides the complete dependency graph.

### Build and validate

```bash
pnpm build
pnpm build:backend
pnpm build:all
pnpm lint
pnpm test
```

The Docker build runs the complete application build:

```bash
docker compose build
```

The repository contains duplicate vendor/archive directories. The Docker
context excludes those directories; host-side TypeScript commands may still
discover them if they are present in the working tree. Prefer the Docker
build or clean the archive directories before relying on host-side type-check
results.

### Useful project scripts

| Command | Purpose |
|---|---|
| `pnpm dev` | Run the Next.js frontend in development mode |
| `pnpm dev:backend` | Run the backend with nodemon |
| `pnpm dev:thread` | Run the worker/thread process |
| `pnpm build` | Build the frontend |
| `pnpm build:backend` | Compile the backend |
| `pnpm build:all` | Build frontend and backend |
| `pnpm seed` | Run Sequelize seeders |
| `pnpm lint` | Lint source code |
| `pnpm test` | Run Jest tests |
| `pnpm gate` | Extract or update permission metadata |

## Production readiness

Before production use, complete the following:

### Security

- Replace all example secrets and rotate them securely.
- Use TLS and a real `NEXT_PUBLIC_SITE_URL`.
- Use a non-root database account with least privilege.
- Restrict MySQL, Redis, and Scylla network exposure.
- Protect admin accounts with strong passwords and 2FA.
- Configure rate limits, CSRF/session policies, log retention, and alerting.
- Review upload validation, API keys, wallet custody, and withdrawal controls.

### Financial controls

- Define fee schedules and accounting reconciliation.
- Establish deposit and withdrawal approval rules.
- Reconcile provider, wallet, and ledger balances.
- Define rollback and incident procedures.
- Test duplicate callbacks, partial fills, provider outages, and chain
  reorganizations.

### Compliance and operations

- Configure KYC/AML requirements for the target jurisdiction.
- Define user terms, privacy notices, risk disclosures, and support SLAs.
- Configure real email/SMS delivery and verify templates.
- Establish backups, restore drills, monitoring, and disaster recovery.
- Perform load, security, and penetration testing before handling funds.

### Deployment

- Use separate staging and production environments.
- Pin and review dependency updates.
- Build immutable images and maintain a release process.
- Use managed databases or production-grade clustered services as appropriate.
- Do not expose development seed credentials or placeholder provider keys.

## Known limitations and troubleshooting

### `/admin` or `/admin/dashboard` is not found

The dashboard route is `/admin/dashboard`; the repository also provides an
`/admin` redirect entry point. Rebuild the frontend after route changes:

```bash
docker compose up -d --build frontend
```

### System Status crashes or resets the connection

Health checks depend on their configured integrations. Ensure the backend
container can reach Redis and Scylla through Docker service names. Missing
optional credentials should be treated as expected Down states, not as proof
that the whole platform is unavailable.

### Scylla reports missing credentials

The local Scylla Compose service runs without authentication. The application
can connect without `SCYLLA_USERNAME` and `SCYLLA_PASSWORD`. Production Scylla
deployments may require authentication and should set both values.

### Market page is empty

Check `exchange_market`, provider activation, import status, credentials, and
Redis ticker population. The frontend cannot display a pair that has not been
created and enabled in the database.

### Frontend cannot reach the backend

Inside Docker, `localhost` means the current container. Use:

- Frontend-to-backend: `http://backend:4000`.
- Backend-to-MySQL: `mysql:3306`.
- Backend-to-Redis: `redis:6379`.
- Backend-to-Scylla: `scylla:9042`.

The Compose file supplies these internal values and the Next.js build receives
the backend hostname as a build argument.

### Email and SMS show Down

Check the provider mode and credentials in `.env`. A placeholder Gmail,
SMTP, Twilio, or SendGrid value is not a working integration. Test delivery in
a non-production environment before enabling user notifications.

## Repository map

| Path | Responsibility |
|---|---|
| `src/pages` | Next.js public, user, and admin pages |
| `src/components` | Reusable UI and feature components |
| `src/stores` | Zustand client state and user workflows |
| `src/middleware.ts` | Token validation, role loading, and route protection |
| `src/utils/gate.ts` | Frontend route-to-permission map |
| `backend/api` | File-based backend HTTP routes |
| `backend/utils` | Authentication, exchange, queues, cron, mail, and platform utilities |
| `models` | Sequelize models and database initialization |
| `seeders` | Initial roles, permissions, settings, and development data |
| `packages` | Local packages such as i18n and permission tooling |
| `public` | Static assets and charting resources |
| `themes` | Theme and presentation resources |
| `docker-compose.yml` | Local multi-service orchestration |
| `Dockerfile` | Multi-stage Node 20 build and runtime image |
| `.env.example` | Environment configuration template |
| `docs` | Packaged documentation and supporting assets |

## License and customization

Review [LICENSE.md](./LICENSE.md) and the rights associated with the original
distribution before redistributing, rebranding, or operating this software.
Third-party packages and integrations have their own licenses and terms.

This repository has been customized to support a locally managed deployment
and local provider activation behavior. Automatic vendor update/download
behavior is disabled in the current application utility layer. That change
does not grant rights to redistribute third-party code or remove obligations
from the original software license.

For an independent product, maintain a separate release process, document all
third-party dependencies, remove unused integrations, rotate all credentials,
and obtain legal review for the intended business and jurisdiction.
