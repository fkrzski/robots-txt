---
title: Guide
description: How the RobotsTxt builder composes global and crawler-specific rules, orders its output, and writes robots.txt files to disk.
---

This guide covers the mental model behind the builder: how rules are grouped, how
the final file is ordered, and how to persist it.

## Global vs crawler context

The builder tracks a *current context*. Until you call `userAgent()`, every rule
you add is **global** and renders under `User-agent: *`:

```php
use Fkrzski\RobotsTxt\RobotsTxt;

$robots = new RobotsTxt();

echo $robots
    ->disallow('/admin')
    ->allow('/public')
    ->toString();
```

```text
User-agent: *
Disallow: /admin
Allow: /public
```

Calling `userAgent()` switches the context: every rule after it belongs to that
crawler until you switch again.

```php
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

echo $robots
    ->disallow('/admin')              // global
    ->userAgent(CrawlerEnum::GOOGLE)
    ->disallow('/google-only')        // Googlebot
    ->userAgent(CrawlerEnum::BING)
    ->disallow('/bing-only')          // Bingbot
    ->toString();
```

```text
User-agent: *
Disallow: /admin

User-agent: Googlebot
Disallow: /google-only

User-agent: Bingbot
Disallow: /bing-only
```

## userAgent() vs forUserAgent()

`userAgent()` sets the context and leaves it set — every following rule sticks to
that crawler. `forUserAgent()` scopes a group of rules to a crawler inside a
closure, then restores the previous context when the closure returns. Use it when
you want a self-contained block without leaking the context into later calls:

```php
$robots
    ->disallow('/admin')                    // global
    ->forUserAgent(CrawlerEnum::GOOGLE, function (RobotsTxt $robots): void {
        $robots
            ->disallow('/private')
            ->crawlDelay(10);
    })
    ->allow('/public');                     // still global — context restored
```

```text
User-agent: *
Disallow: /admin
Allow: /public

User-agent: Googlebot
Disallow: /private
Crawl-delay: 10
```

Note how `allow('/public')` lands back in the global block even though it comes
after the Google group in the source.

## Output ordering

`toString()` always emits sections in a fixed order, regardless of the order you
called the methods:

1. Global rules (under `User-agent: *`), if any
2. Each crawler block, in the order its crawler was first referenced
3. All sitemaps, last

Blocks are separated by a blank line. A realistic example combining all three:

```php
$robots = new RobotsTxt();

echo $robots
    ->disallow('/admin')
    ->sitemap('https://example.com/sitemap1.xml')
    ->forUserAgent(CrawlerEnum::GOOGLE, function (RobotsTxt $robots): void {
        $robots
            ->disallow('/google-private')
            ->allow('/public/*');
    })
    ->forUserAgent(CrawlerEnum::BING, function (RobotsTxt $robots): void {
        $robots
            ->disallow('/bing-private')
            ->crawlDelay(5);
    })
    ->sitemap('https://example.com/sitemap2.xml')
    ->toString();
```

```text
User-agent: *
Disallow: /admin

User-agent: Googlebot
Disallow: /google-private
Allow: /public/*

User-agent: Bingbot
Disallow: /bing-private
Crawl-delay: 5

Sitemap: https://example.com/sitemap1.xml
Sitemap: https://example.com/sitemap2.xml
```

The two sitemaps were added at opposite ends of the chain, yet both render
together in the trailing sitemap section, in insertion order.

## Writing to disk

`toFile()` renders the content and writes it to a file. With no argument it writes
`robots.txt` to the current working directory:

```php
$robots->toFile();                           // ./robots.txt
$robots->toFile('/var/www/html/robots.txt'); // custom path
```

It returns `true` on success and throws a `RuntimeException` when the target
directory does not exist, or the directory or an existing file is not writable.

See the [API reference](/robots-txt/api-reference) for the per-method details and the
full validation rules.
