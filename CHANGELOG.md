# Changelog

This project follows [Semantic Versioning](https://semver.org/).

## [1.0.0] - Unreleased

First versioned release, prepared in 2026 from the unversioned 2018 code.

### Changed

- **Breaking:** replaced the individual PHP page URLs with `/`, `/section-two`, `/section-three`, and `/section-four`.
- Translated all identifiers, filenames, and interface text to English.
- Restructured the code into `public/`, `src/`, and `templates/`, with PHP 8.4+ as the minimum version.
- Replaced the single-letter navigation labels with descriptive page names.

### Removed

- The fifth navigation entry, which had an empty link and no matching page.
- The empty `index.php.txt` file.

### Fixed

- Active-page highlighting, which could never match in the original code.
- Empty navigation links.

### Security

- Added security headers, output escaping, method restrictions, and template name validation.
- Stopped reading the client-supplied host header.

### Added

- Unit, integration, functional, and database tests with a committed report.
- PHPStan analysis and a least-privilege GitHub Actions workflow.
- Optional test-results schema and project documentation.
