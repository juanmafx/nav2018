# Release Notes: nav2018 1.0.0

Status: prepared, not tagged or published.

This is the first versioned release. It rebuilds the 2018 navigation exercise as a typed PHP 8.4+ application in which one page catalog drives both routing and the navigation menu.

## Highlights

- Working active-page highlighting with accessible markup.
- A strict Content Security Policy and related response headers.
- Automated tests and static analysis, run in CI on PHP 8.4 and 8.5.

## Breaking changes

- PHP 8.4 or newer is required.
- The web server's document root must be `public/`.
- The original `.php` page URLs no longer exist.

## Upgrade

Run `composer install --no-dev --no-scripts --no-plugins`, then point the document root at `public/`.
