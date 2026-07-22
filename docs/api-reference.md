---
title: API reference
description: Complete method reference for the RobotsTxt builder — signatures, validation rules, and verified output for every call.
---

Every public method of `RobotsTxt`, with its signature, behavior, and the exact
output it produces. All methods return the builder for chaining, except `toFile()`
(returns `bool`) and `toString()` (returns `string`).

## allow()

```text
allow(string $path): self
```

Adds an `Allow` rule for `$path` to the current context.

```php
use Fkrzski\RobotsTxt\RobotsTxt;

echo (new RobotsTxt())->allow('/public')->toString();
```

```text
User-agent: *
Allow: /public
```

## disallow()

```text
disallow(string $path): self
```

Adds a `Disallow` rule for `$path` to the current context. Same path rules as
`allow()` (see [Path validation](#path-validation)).

```php
echo (new RobotsTxt())->disallow('/private')->toString();
```

```text
User-agent: *
Disallow: /private
```

## crawlDelay()

```text
crawlDelay(int $seconds): self
```

Adds a `Crawl-delay` rule to the current context. `$seconds` must be non-negative.

```php
echo (new RobotsTxt())->crawlDelay(10)->toString();
```

```text
User-agent: *
Crawl-delay: 10
```

## userAgent()

```text
userAgent(CrawlerEnum $crawler): self
```

Switches the current context to `$crawler`. Every rule added afterwards belongs to
that crawler until the context changes. Referencing a crawler for the first time
opens its block; referencing it again reuses the same block.

```php
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

echo (new RobotsTxt())
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

## forUserAgent()

```text
forUserAgent(CrawlerEnum $crawler, Closure(RobotsTxt): void $rules): self
```

Runs `$rules` with the context set to `$crawler`, then restores the previous
context. Use it to group a crawler's rules without affecting later calls.

```php
$robots = new RobotsTxt();

$robots->forUserAgent(CrawlerEnum::BING, function (RobotsTxt $robots): void {
    $robots
        ->disallow('/bing-private')
        ->crawlDelay(10);
});

echo $robots->toString();
```

```text
User-agent: Bingbot
Disallow: /bing-private
Crawl-delay: 10
```

## sitemap()

```text
sitemap(string $url): self
```

Adds a sitemap. Sitemaps are context-independent — they always render together in
the trailing section, in insertion order (see [Sitemap validation](#sitemap-validation)).

```php
echo (new RobotsTxt())
    ->sitemap('https://example.com/sitemap.xml')
    ->toString();
```

```text
Sitemap: https://example.com/sitemap.xml
```

## disallowAll()

```text
disallowAll(bool $disallow = true): self
```

Blocks the entire current context. When `$disallow` is `true` (the default) it
**clears the existing rules of the current context** and adds a single
`Disallow: /`. Global rules for other crawlers and all sitemaps are kept. When
`$disallow` is `false` the method does nothing.

Applied globally, it wipes the previously chained rules:

```php
echo (new RobotsTxt())
    ->allow('/public')   // cleared
    ->disallow('/admin') // cleared
    ->disallowAll()
    ->toString();
```

```text
User-agent: *
Disallow: /
```

Applied inside a crawler context, it only clears that crawler — global rules and
other crawlers survive:

```php
echo (new RobotsTxt())
    ->disallow('/admin')                  // global — kept
    ->userAgent(CrawlerEnum::GOOGLE)
    ->allow('/public')                    // Google — cleared
    ->disallow('/private')                // Google — cleared
    ->disallowAll()
    ->userAgent(CrawlerEnum::BING)
    ->disallow('/secret')                 // Bing — kept
    ->toString();
```

```text
User-agent: *
Disallow: /admin

User-agent: Googlebot
Disallow: /

User-agent: Bingbot
Disallow: /secret
```

## toString()

```text
toString(): string
```

Renders the complete `robots.txt` content: global block, then each crawler block,
then all sitemaps, separated by blank lines. Returns an empty string when no rules
have been added.

## toFile()

```text
toFile(?string $path = null): bool
```

Writes the rendered content to a file and returns `true` on success. With no
argument it writes `robots.txt` to the current working directory.

```php
$robots = (new RobotsTxt())->disallow('/admin')->allow('/public');

$robots->toFile();                           // ./robots.txt
$robots->toFile('/var/www/html/robots.txt'); // custom path
```

Throws a `RuntimeException` when the target directory does not exist, or the
directory or an existing file is not writable.

## Wildcards

Paths pass wildcard characters through unchanged, so the standard `*` (match any
sequence) and `$` (match end of URL) patterns work as-is:

```php
echo (new RobotsTxt())
    ->disallow('/*.php')     // block all PHP files
    ->allow('/public/*')     // allow everything under /public
    ->disallow('/private/$') // exact match for /private/
    ->toString();
```

```text
User-agent: *
Disallow: /*.php
Allow: /public/*
Disallow: /private/$
```

## Validation

Values are validated the moment you add them, so an invalid `robots.txt` can never
be built. Bad paths, sitemaps, and crawl delays throw an
`InvalidArgumentException`; file errors throw a `RuntimeException`.

### Path validation

Applies to `allow()` and `disallow()`. A path must:

| Requirement           | Exception message                        |
| --------------------- | ---------------------------------------- |
| Not be empty          | `Path cannot be empty`                   |
| Start with `/`        | `Path must start with forward slash (/)` |
| No query string (`?`) | `Path cannot contain query parameters`   |
| No fragment (`#`)     | `Path cannot contain fragments`          |
| No whitespace         | `Path cannot contain whitespace`         |

### Sitemap validation

Applies to `sitemap()`. A URL must:

| Requirement           | Exception message                       |
| --------------------- | --------------------------------------- |
| Not be empty          | `Sitemap URL cannot be empty`           |
| Be a valid URL        | `Invalid sitemap URL format`            |
| Use `http` or `https` | `Sitemap URL must use HTTP(S) protocol` |
| End with `.xml`       | `Sitemap URL must be in .xml format`    |

### Crawl delay validation

Applies to `crawlDelay()`. The value must be non-negative, otherwise it throws
`Crawl delay cannot be negative`.

```php
use Fkrzski\RobotsTxt\RobotsTxt;

(new RobotsTxt())->disallow('admin');   // InvalidArgumentException: Path must start with forward slash (/)
(new RobotsTxt())->sitemap('/site.xml'); // InvalidArgumentException: Sitemap URL must use HTTP(S) protocol
(new RobotsTxt())->crawlDelay(-1);       // InvalidArgumentException: Crawl delay cannot be negative
```
