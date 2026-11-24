# nav2018

A small server-rendered PHP 8.4+ website in which every page shares one navigation component that highlights the current page.

> **Project history:** originally created in 2018, modernized in 2026.

## Purpose

The project shows how to define site navigation once and reuse it across pages, instead of copying markup into each file.

## Why this project exists

The 2018 version was a learning exercise: four near-identical PHP pages including a shared `nav.php`. Its highlighting logic was unfinished, because every link had an empty target and the active-page comparison could never match. This version completes that idea with a typed, tested structure.

## What it does

- Serves four pages: `/`, `/section-two`, `/section-three`, and `/section-four`.
- Renders one navigation menu and marks the current page with `aria-current="page"`.
- Answers `GET` and `HEAD`; other methods receive `405` with an `Allow` header.
- Answers unknown paths with `404` without reflecting the requested path.
- Sends a strict Content Security Policy and related security headers.

It has no accounts, forms, JavaScript, cookies, sessions, or database connection.

## Architecture

```text
public/      Web root: front controller and stylesheet
src/         Application, Http, Navigation, Routing, and View modules
templates/   HTML layout and navigation partial
tests/       Unit, Integration, Functional, and Database suites
db/          Optional test-results schema
reports/     Committed output of a real test run
```

All code is server-rendered, so there is no separate `frontend/` and `backend/`. See [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md).

## Quick start

Requirements: PHP 8.4 or 8.5 and Composer 2.

```bash
composer install --no-dev --no-scripts --no-plugins
php -S 127.0.0.1:8080 -t public
```

Open <http://127.0.0.1:8080/>.

## Usage guide

To add a page, add one `Page` entry to `PageCatalog::default()` in `src/Routing/PageCatalog.php`. The route and navigation link are both derived from that entry.

For production, point the web server's document root at `public/` and route unmatched requests to `public/index.php`. Never expose the repository root.

## Configuration

The application needs no environment variables or secrets. Pages are configured in `PageCatalog`, and response headers in `src/Http/SecurityHeaders.php`.

## Database (optional)

The website does **not** use a database. [db/schema.sql](db/schema.sql) is an optional schema for storing automated test-run results:

```bash
sqlite3 test-results.db < db/schema.sql
```

## Testing

With PHP extensions `dom`, `xml`, `xmlwriter`, and `pdo_sqlite`:

```bash
composer install --no-scripts --no-plugins
composer analyse
composer test
```

Without those extensions, use the digest-pinned Docker image, which also regenerates the report (run `composer install` first, adding `--ignore-platform-req=ext-dom --ignore-platform-req=ext-xml --ignore-platform-req=ext-xmlwriter` if needed):

```bash
scripts/test-in-docker.sh
```

The latest committed results are in [reports/tests/](reports/tests/).

## Security notes

Output is escaped, template names are validated, and security headers are sent on every application response. TLS, HSTS, and rate limiting belong to the deployment layer and are not provided here. See [SECURITY.md](SECURITY.md) and [docs/SECURITY_AUDIT.md](docs/SECURITY_AUDIT.md).

## Versioning

The current version is in [VERSION](VERSION). See [CHANGELOG.md](CHANGELOG.md) and [RELEASE_NOTES.md](RELEASE_NOTES.md).

## License

This repository has no license file, so no reuse rights are granted by default.
