# Changelog

All notable changes to `robots-txt` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.1.2] - 2026-10-15

### Added

- A changelog, in Keep a Changelog 1.1.0, recording every release back to 1.0.0 and published beside the docs at [docs.fkrzski.dev/robots-txt](https://docs.fkrzski.dev/robots-txt). It is kept out of the Composer archive, so a `--prefer-dist` download is unchanged ([#24](https://github.com/fkrzski/robots-txt/issues/24)).

## [2.1.1] - 2026-10-14

### Changed

- The guide, the API reference and the crawler list are published at [docs.fkrzski.dev/robots-txt](https://docs.fkrzski.dev/robots-txt) and live in `docs/` in the repository; the README is a quickstart that links there instead of carrying the whole manual.
- The Composer archive leaves out `docs/` as well, so a `--prefer-dist` download stays `src/` and `composer.json`.

## [2.1.0] - 2025-12-11

### Added

- PHP 8.5 support.

### Changed

- A `--prefer-dist` download ships `src/` and `composer.json` only: the `export-ignore` rules in the new `.gitattributes` keep `art/`, `tests/`, `.github/`, the README, the license and the tool configs out of the Composer archive.

### Removed

- **BC break.** PHP 8.3 support; the package requires PHP 8.4 or 8.5.

## [2.0.1] - 2025-08-20

### Fixed

- `disallowAll()` writes `Disallow: /`, the whole-site rule every crawler reads, where it wrote the `Disallow: /*` wildcard that only crawlers supporting pattern matching honour.

## [2.0.0] - 2025-01-28

### Removed

- **BC break.** PHP 8.2 support; the package requires PHP 8.3 or higher.

## [1.0.2] - 2024-12-17

### Fixed

- The README's `disallowAll()` example closes its code fence, so everything after it reads as prose again instead of as one code block.

## [1.0.1] - 2024-12-16

### Fixed

- `composer.json` no longer pins `"version": "dev-0.1.0"`, so Composer and Packagist read the version from the tag; 1.0.0 reported itself as `dev-0.1.0`.

## [1.0.0] - 2024-12-16

### Added

- `RobotsTxt`, a fluent builder: `allow()`, `disallow()`, `crawlDelay()` and `sitemap()` for global rules, `userAgent()` and `forUserAgent()` for per-crawler blocks, `disallowAll()` to block the whole site, and `toString()` / `toFile()` to render the file or write it to disk.
- `CrawlerEnum`, 37 crawlers mapped to their official user-agent strings, and `DirectiveEnum` with `Allow`, `Disallow`, `User-agent`, `Crawl-delay` and `Sitemap`.
- `Path`, `CrawlDelay`, `Sitemap`, `UserAgent` and `Rule` value objects behind the `ValueObject` contract, each validating on construction and throwing `InvalidArgumentException` on a path that does not start with `/`, a negative delay, or a sitemap URL that is not an HTTP(S) `.xml`.
- Support for PHP 8.2 and higher.

[Unreleased]: https://github.com/fkrzski/robots-txt/compare/2.1.2...HEAD
[2.1.2]: https://github.com/fkrzski/robots-txt/compare/2.1.1...2.1.2
[2.1.1]: https://github.com/fkrzski/robots-txt/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/fkrzski/robots-txt/compare/2.0.1...2.1.0
[2.0.1]: https://github.com/fkrzski/robots-txt/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/fkrzski/robots-txt/compare/1.0.2...2.0.0
[1.0.2]: https://github.com/fkrzski/robots-txt/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/fkrzski/robots-txt/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/fkrzski/robots-txt/releases/tag/1.0.0
