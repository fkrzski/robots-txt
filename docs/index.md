---
title: robots-txt
description: A modern, fluent PHP package for building valid robots.txt files with type-safe crawlers and fail-fast validation.
repository: https://github.com/fkrzski/robots-txt
packagist: fkrzski/robots-txt
status: stable
---

A modern, fluent PHP package for building `robots.txt` files. The `RobotsTxt`
class exposes a chainable interface that guides you toward valid output — invalid
paths, sitemap URLs, or crawl delays fail fast with a clear exception instead of
shipping a broken file.

## Why robots-txt

- **Fluent & chainable** — every method returns the builder, so a policy reads
  top to bottom.
- **Type-safe crawlers** — target bots through the `CrawlerEnum` instead of
  hand-writing user-agent strings.
- **Validated by design** — paths, sitemap URLs, and crawl delays are checked as
  you build, never at render time.
- **Zero runtime dependencies** — one small package, nothing to pull in.

## Requirements

- PHP 8.4 or higher
- A code coverage driver (development only)

## Installation

Install the package via Composer:

```bash
composer require fkrzski/robots-txt
```

## Quick start

Chain rules and render the result with `toString()`:

```php
use Fkrzski\RobotsTxt\RobotsTxt;

$robots = new RobotsTxt();

echo $robots
    ->disallow('/admin')
    ->allow('/public')
    ->crawlDelay(5)
    ->toString();
```

```text
User-agent: *
Disallow: /admin
Allow: /public
Crawl-delay: 5
```

Rules added before any `userAgent()` call apply to every crawler and render under
`User-agent: *`.

## Where to next

- **[Guide](./guide)** — how global and crawler-specific rules compose, how the
  output is ordered, and how to write the file to disk.
- **[API reference](./api-reference)** — every method with its signature,
  validation rules, and verified output.
- **[Crawlers](./crawlers)** — the full `CrawlerEnum` list mapped to official
  user-agent strings.
