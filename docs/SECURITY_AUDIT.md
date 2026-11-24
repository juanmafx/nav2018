# Security Audit

Audit date: 2026-11-23. Scope: all tracked files and the full Git history of this repository. This was a manual review supported by the automated checks listed below; it was not an independent penetration test.

## Findings resolved

| ID | Severity | Finding | Resolution |
| -- | -------- | ------- | ---------- |
| S1 | Low | Client-controlled host and URI values were read into template scope | Removed; routing uses only the normalized path against a fixed list |
| S2 | Low | No security response headers | Added CSP, `nosniff`, referrer, permissions, and cross-origin policies |
| S3 | Low | PHP files in the web root, including internal partials | Introduced `public/` so only the front controller and assets are served |
| S4 | Low | Runtime errors could be displayed, depending on server configuration | Display disabled; a generic 500 response is returned and details are logged |
| S5 | Info | No dependency or version constraints | Added a locked toolchain, `composer audit`, and SHA-pinned CI actions |
| S6 | Low | `X-Powered-By` disclosed the exact PHP version on application responses | Header removed before each response and covered by functional tests |
| S7 | Low | The optional schema's inline foreign key was parsed but not enforced by MySQL | Declared at table level; orphan rejection and cascade checked on SQLite, PostgreSQL 17, and MySQL 8.4 |

## Categories reviewed with no issue found

- **SQL injection:** no database access in the application.
- **XSS:** the original pages did not output request data. All dynamic output is now escaped and covered by tests.
- **CSRF and authentication:** no forms, state changes, sessions, or accounts, so two-factor authentication and password hashing do not apply.
- **Unsafe evaluation:** no `eval`, shell execution, or dynamic includes from user input.
- **Secrets:** `gitleaks` 8.30.1 with `--redact` found no leaks in the branch history or working tree, and no private keys are tracked.

## Automated checks

- `composer audit --locked`: no advisories at audit time.
- PHPStan level 8 on `public/`, `src/`, and `tests/`, with the `missingType.iterableValue` rule ignored.
- PHPUnit suites, including escaping, path traversal, method handling, response headers, and the generic error response.

## Open items

- TLS, HSTS, rate limiting, and security headers on static files must be provided by the deployment layer.
- PHP's built-in development server answers missing paths under `/assets/` with its own `404` page. It is not intended for production.
