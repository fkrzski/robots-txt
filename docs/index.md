---
title: robots-txt
description: A modern, fluent PHP package for building type-safe, immutable robots.txt rules with a first-class developer experience.
repository: https://github.com/fkrzski/robots-txt
packagist: fkrzski/robots-txt
status: stable
---

A modern, fluent PHP package for building `robots.txt` files with **type safety**
and **immutability**. The `RobotsTxt` class exposes a chainable interface that
guides you toward valid output — invalid paths, sitemaps, or crawl delays fail
fast with clear exceptions instead of shipping a broken file.

## Why robots-txt

- **Fluent & chainable** — every rule method returns a new instance, so building
  a policy reads top to bottom.
- **Type-safe crawlers** — target bots through the `CrawlerEnum` instead of
  hand-writing user-agent strings.
- **Validated by design** — paths, sitemap URLs, and crawl delays are checked as
  you build, never at render time.
- **Immutable** — nothing mutates in place, making rule sets safe to compose and
  reuse.

## Requirements

- PHP 8.4 or higher
- A code coverage driver (development only)

## Installation

Install the package via Composer:

```bash
composer require fkrzski/robots-txt
```

## Quick start

Chain rules fluently and render the result with `toString()`:

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

## Targeting specific crawlers

Use the `CrawlerEnum` to scope rules to a single bot — the enum maps each case to
its official user-agent string, so you never memorize `Googlebot` or `Bingbot`.

```php
use Fkrzski\RobotsTxt\RobotsTxt;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

$robots = new RobotsTxt();

echo $robots
    ->userAgent(CrawlerEnum::GOOGLE)
    ->disallow('/private')
    ->allow('/public')
    ->toString();
```

```text
User-agent: Googlebot
Disallow: /private
Allow: /public
```

For grouped, self-contained crawler rules, reach for the closure-based
`forUserAgent()`:

```php
$robots->forUserAgent(CrawlerEnum::BING, function (RobotsTxt $robots): void {
    $robots
        ->disallow('/bing-private')
        ->crawlDelay(10);
});
```

## Sitemaps and full blocks

Add sitemaps and block an entire site with dedicated helpers:

```php
use Fkrzski\RobotsTxt\RobotsTxt;

$robots = new RobotsTxt();

echo $robots
    ->sitemap('https://example.com/sitemap.xml')
    ->disallowAll()
    ->toString();
```

```text
User-agent: *
Disallow: /*

Sitemap: https://example.com/sitemap.xml
```

## Writing to disk

Persist the output straight to a file with `toFile()`. Called with no argument it
writes `robots.txt` to the project root:

```php
$robots->toFile();                          // ./robots.txt
$robots->toFile('/var/www/html/robots.txt'); // custom path
```

It returns `true` on success and throws a `RuntimeException` when the target
directory or existing file is not writable.

## Validation rules

Building fails fast with an `InvalidArgumentException` when a value would produce
an invalid `robots.txt`:

| Input       | Requirement                                        |
| ----------- | -------------------------------------------------- |
| Path        | Starts with `/`, no query string or fragment       |
| Sitemap URL | Valid HTTP/HTTPS URL ending in `.xml`              |
| Crawl delay | Non-negative integer                               |

## Next steps

- Browse the full API and advanced examples in the
  [README](https://github.com/fkrzski/robots-txt#readme).
- Report issues or request features on the
  [issue tracker](https://github.com/fkrzski/robots-txt/issues).
