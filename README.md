# PHP Robots.txt

A modern, fluent PHP package for managing robots.txt rules with type safety and great developer experience.

## Requirements

- PHP 8.2 or higher
- Code coverage driver

## Installation

You can install the package via composer:

```bash
composer require fkrzski/robots-txt
```

## Documentation

The `RobotsTxt` class provides a fluent interface for creating and managing robots.txt rules with type safety and immutability.

### Basic Methods

#### Constructor

Creates a new instance of the RobotsTxt class.

```php
$robots = new RobotsTxt();
```

Output:
```
// Empty output - no rules defined yet
```

#### allow(string $path)

Adds an Allow rule for the specified path. The path must:
- Start with a forward slash (/)
- Not contain query parameters
- Not contain fragments
- Not be empty

```php
$robots = new RobotsTxt();
$robots->allow('/public');
```

Output:
```
User-agent: *
Allow: /public
```

#### disallow(string $path)

Adds a Disallow rule for the specified path. Has the same path requirements as `allow()`.

```php
$robots = new RobotsTxt();
$robots->disallow('/private');
```

Output:
```
User-agent: *
Disallow: /private
```

#### crawlDelay(int $seconds)

Sets the crawl delay in seconds. The delay value must be non-negative.

```php
$robots = new RobotsTxt();
$robots->crawlDelay(10);
```

Output:
```
User-agent: *
Crawl-delay: 10
```

#### sitemap(string $url)

Adds a Sitemap URL. The URL must:
- Be a valid URL
- Use HTTP or HTTPS protocol
- Have an .xml extension

```php
$robots = new RobotsTxt();
$robots->sitemap('https://example.com/sitemap.xml');
```

Output:
```
Sitemap: https://example.com/sitemap.xml
```

#### disallowAll(bool $disallow = true)

A convenience method for quickly blocking access to the entire site. When `$disallow` is true (default):
- Clears all existing rules in the current context (global or user-agent specific)
- Adds a single "Disallow: /*" rule
- Preserves sitemap entries and rules for other user agents

```php
// Block everything globally
$robots = new RobotsTxt();
$robots
    ->allow('/public')    // This will be cleared
    ->disallow('/admin')  // This will be cleared
    ->disallowAll();     // Only Disallow: /* remains

Output:
```
User-agent: *
Disallow: /*
```

Block access only for specific crawler:
```php
$robots = new RobotsTxt();
$robots
    ->disallow('/admin')          // Global rule - keeps
    ->userAgent(CrawlerEnum::GOOGLE)
    ->allow('/public')            // Google rule - cleared
    ->disallow('/private')        // Google rule - cleared
    ->disallowAll()               // Only Disallow: /* for Google
    ->userAgent(CrawlerEnum::BING)
    ->disallow('/secret');        // Bing rule - keeps
```

Output:
```
User-agent: *
Disallow: /admin

User-agent: Googlebot
Disallow: /*

User-agent: Bingbot
Disallow: /secret
```

When `$disallow` is false, the method does nothing.

#### userAgent(CrawlerEnum $crawler)

Sets the context for subsequent rules to apply to a specific crawler.

```php
$robots = new RobotsTxt();
$robots->userAgent(CrawlerEnum::GOOGLE);
```

Output:
```
User-agent: Googlebot
```

### Combining Methods

#### Basic Rule Combinations

You can chain multiple rules together:

```php
$robots = new RobotsTxt();
$robots
    ->disallow('/admin')
    ->allow('/public')
    ->crawlDelay(5);
```

Output:
```
User-agent: *
Disallow: /admin
Allow: /public
Crawl-delay: 5
```

#### Crawler-Specific Rules

You can set rules for specific crawlers:

```php
$robots = new RobotsTxt();
$robots
    ->userAgent(CrawlerEnum::GOOGLE)
    ->disallow('/private')
    ->allow('/public')
    ->crawlDelay(10);
```

Output:
```
User-agent: Googlebot
Disallow: /private
Allow: /public
Crawl-delay: 10
```

#### Multiple Crawlers

You can define rules for multiple crawlers:

```php
$robots = new RobotsTxt();
$robots
    ->userAgent(CrawlerEnum::GOOGLE)
    ->disallow('/google-private')
    ->userAgent(CrawlerEnum::BING)
    ->disallow('/bing-private');
```

Output:
```
User-agent: Googlebot
Disallow: /google-private

User-agent: Bingbot
Disallow: /bing-private
```

#### Using forUserAgent()

The `forUserAgent()` method provides a closure-based syntax for grouping crawler-specific rules:

```php
$robots = new RobotsTxt();
$robots->forUserAgent(CrawlerEnum::GOOGLE, function (RobotsTxt $robots): void {
    $robots
        ->disallow('/private')
        ->allow('/public')
        ->crawlDelay(10);
});
```

Output:
```
User-agent: Googlebot
Disallow: /private
Allow: /public
Crawl-delay: 10
```

#### Complex Example

Combining global rules, multiple crawlers, and sitemaps:

```php
$robots = new RobotsTxt();
$robots
    ->disallow('/admin')  // Global rule
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
    ->sitemap('https://example.com/sitemap2.xml');
```

Output:
```
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

### Working with Wildcards

The library supports wildcards in paths:

```php
$robots = new RobotsTxt();
$robots
    ->disallow('/*.php')    // Block all PHP files
    ->allow('/public/*')    // Allow all under public
    ->disallow('/private/$'); // Exact match for /private/
```

Output:
```
User-agent: *
Disallow: /*.php
Allow: /public/*
Disallow: /private/$
```

#### toFile(?string $path = null)

Saves the robots.txt content to a file. If no path is provided, saves to `robots.txt` in the project root directory.

```php
$robots = new RobotsTxt();
$robots
    ->disallow('/admin')
    ->allow('/public');

// Save to default location (project root)
$robots->toFile();

// Save to custom location
$robots->toFile('/var/www/html/robots.txt');
```

The method will throw a `RuntimeException` if:
- The target directory doesn't exist or isn't writable
- The existing robots.txt file isn't writable

Returns `true` if the file was successfully written.

### Best Practices

1. **Start with Global Rules**: Define global rules before crawler-specific rules for better organization.
2. **Group Related Rules**: Use the `forUserAgent()` method to group rules for the same crawler.
3. **Use Wildcards Carefully**: Be precise with wildcard patterns to avoid unintended matches.
4. **Order Matters**: More specific rules should come before more general ones.
5. **Validate Paths**: Always ensure paths start with a forward slash and don't contain query parameters or fragments.

### Error Handling

The class will throw `InvalidArgumentException` in the following cases:

- Path doesn't start with forward slash
- Path contains query parameters or fragments
- Path is empty
- Sitemap URL is invalid or not HTTP/HTTPS
- Sitemap URL doesn't end with .xml
- Crawl delay is negative

These validations ensure that the generated robots.txt file is always valid and follows the standard format.

## Testing and Development

The project includes several command groups for testing and code quality:

### Run all tests and checks

```bash
composer ci
```

This command runs:
- Type coverage tests
- Test coverage analysis
- Mutation tests
- Static analysis (PHPStan & Psalm)
- Code style checks
- Rector checks

### Run all tests

```bash
composer test:all
```

This command runs:
- Type coverage tests (`test:types`)
- Test coverage analysis (`test:coverage`)
- Mutation tests (`test:mutation`)

### Run code analysis

```bash
composer analyse
```

This command runs:
- PHPStan static analysis
- Psalm static analysis

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.