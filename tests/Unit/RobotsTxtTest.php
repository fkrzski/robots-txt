<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\RobotsTxt;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

mutates(RobotsTxt::class);

test('can create empty robots txt', function (): void {
    $robots = new RobotsTxt();

    expect($robots->toString())->toBe('');
});

test('can add global rules', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->disallow('/admin')
        ->allow('/public');

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /admin\n".
        "Allow: /public"
    );
});

test('can add rules for specific crawler', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->userAgent(CrawlerEnum::GOOGLE)
        ->disallow('/private')
        ->allow('/public')
        ->crawlDelay(10);

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Disallow: /private\n".
        "Allow: /public\n".
        "Crawl-delay: 10"
    );
});

test('can mix global and specific rules', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->disallow('/admin') // global rule
        ->userAgent(CrawlerEnum::GOOGLE)
        ->disallow('/private')
        ->userAgent(CrawlerEnum::BING)
        ->disallow('/secret');

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /admin\n".
        "\n".
        "User-agent: Googlebot\n".
        "Disallow: /private\n".
        "\n".
        "User-agent: Bingbot\n".
        "Disallow: /secret"
    );
});

test('can add sitemaps', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->sitemap('https://example.com/sitemap.xml')
        ->sitemap('https://example.com/news-sitemap.xml');

    expect($robots->toString())->toBe(
        "Sitemap: https://example.com/sitemap.xml\n".
        "Sitemap: https://example.com/news-sitemap.xml"
    );
});

test('can use closure for user agent rules', function (): void {
    $robots = new RobotsTxt();

    $robots->forUserAgent(CrawlerEnum::GOOGLE, function (RobotsTxt $robotsTxt): void {
        $robotsTxt
            ->disallow('/private')
            ->allow('/public')
            ->crawlDelay(10);
    });

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Disallow: /private\n".
        "Allow: /public\n".
        "Crawl-delay: 10"
    );
});

test('can nest multiple user agents with closures', function (): void {
    $robots = new RobotsTxt();

    $robots
        ->disallow('/admin') // global rule
        ->forUserAgent(CrawlerEnum::GOOGLE, function (RobotsTxt $robotsTxt): void {
            $robotsTxt
                ->disallow('/google-specific')
                ->allow('/public');
        })
        ->forUserAgent(CrawlerEnum::BING, function (RobotsTxt $robotsTxt): void {
            $robotsTxt
                ->disallow('/bing-specific')
                ->crawlDelay(5);
        });

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /admin\n".
        "\n".
        "User-agent: Googlebot\n".
        "Disallow: /google-specific\n".
        "Allow: /public\n".
        "\n".
        "User-agent: Bingbot\n".
        "Disallow: /bing-specific\n".
        "Crawl-delay: 5"
    );
});

test('rules are added in correct order', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->userAgent(CrawlerEnum::GOOGLE)
        ->allow('/first')
        ->disallow('/second')
        ->crawlDelay(5);

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Allow: /first\n".
        "Disallow: /second\n".
        "Crawl-delay: 5"
    );
});

test('switching user agent context preserves previous rules', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->userAgent(CrawlerEnum::GOOGLE)
        ->disallow('/google')
        ->userAgent(CrawlerEnum::BING)
        ->disallow('/bing')
        ->userAgent(CrawlerEnum::GOOGLE)
        ->allow('/google-public');

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Disallow: /google\n".
        "Allow: /google-public\n".
        "\n".
        "User-agent: Bingbot\n".
        "Disallow: /bing"
    );
});

test('can mix global rules with user agent rules and sitemaps', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->disallow('/admin')
        ->sitemap('https://example.com/sitemap1.xml')
        ->userAgent(CrawlerEnum::GOOGLE)
        ->disallow('/private')
        ->sitemap('https://example.com/sitemap2.xml');

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /admin\n".
        "\n".
        "User-agent: Googlebot\n".
        "Disallow: /private\n".
        "\n".
        "Sitemap: https://example.com/sitemap1.xml\n".
        "Sitemap: https://example.com/sitemap2.xml"
    );
});

test('empty rules sections are not included in output', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->sitemap('https://example.com/sitemap.xml')
        ->userAgent(CrawlerEnum::GOOGLE);

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "\n".
        "Sitemap: https://example.com/sitemap.xml"
    );
});

test('can handle multiple user agents with same rules', function (): void {
    $rules = function (RobotsTxt $robotsTxt): void {
        $robotsTxt
            ->disallow('/private')
            ->allow('/public');
    };

    $robots = new RobotsTxt();
    $robots
        ->forUserAgent(CrawlerEnum::GOOGLE, $rules)
        ->forUserAgent(CrawlerEnum::BING, $rules);

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Disallow: /private\n".
        "Allow: /public\n".
        "\n".
        "User-agent: Bingbot\n".
        "Disallow: /private\n".
        "Allow: /public"
    );
});

test('nested user agent closures maintain correct scope', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->userAgent(CrawlerEnum::GOOGLE)
        ->disallow('/before')
        ->forUserAgent(CrawlerEnum::BING, function (RobotsTxt $robotsTxt): void {
            $robotsTxt->disallow('/bing');
            $robotsTxt->forUserAgent(CrawlerEnum::FACEBOOK, function (RobotsTxt $robotsTxt): void {
                $robotsTxt->disallow('/facebook');
            });
            $robotsTxt->disallow('/bing-after');
        })
        ->disallow('/after');

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Disallow: /before\n".
        "Disallow: /after\n".
        "\n".
        "User-agent: Bingbot\n".
        "Disallow: /bing\n".
        "Disallow: /bing-after\n".
        "\n".
        "User-agent: facebookexternalhit\n".
        "Disallow: /facebook"
    );
});

test('can handle wildcards in paths', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->userAgent(CrawlerEnum::GOOGLE)
        ->allow('/public/*')
        ->disallow('/*.php')
        ->disallow('/private/$');

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Allow: /public/*\n".
        "Disallow: /*.php\n".
        "Disallow: /private/$"
    );
});

test('sitemap must be a valid https url', function (): void {
    $robots = new RobotsTxt();

    expect(fn (): \Fkrzski\RobotsTxt\RobotsTxt => $robots->sitemap('not-a-url'))
        ->toThrow(InvalidArgumentException::class, 'Invalid sitemap URL format');
});

test('sitemap must use http or https protocol', function (): void {
    $robots = new RobotsTxt();

    expect(fn (): \Fkrzski\RobotsTxt\RobotsTxt => $robots->sitemap('ftp://example.com/sitemap.xml'))
        ->toThrow(InvalidArgumentException::class, 'Sitemap URL must use HTTP(S) protocol');
});

test('sitemap must have xml extension', function (): void {
    $robots = new RobotsTxt();

    expect(fn (): \Fkrzski\RobotsTxt\RobotsTxt => $robots->sitemap('https://example.com/sitemap.txt'))
        ->toThrow(InvalidArgumentException::class, 'Sitemap URL must be in .xml format');
});

test('path must start with forward slash', function (): void {
    $robots = new RobotsTxt();

    expect(fn (): \Fkrzski\RobotsTxt\RobotsTxt => $robots->disallow('invalid/path'))
        ->toThrow(InvalidArgumentException::class, 'Path must start with forward slash (/)');
});

test('path cannot contain query parameters', function (): void {
    $robots = new RobotsTxt();

    expect(fn (): \Fkrzski\RobotsTxt\RobotsTxt => $robots->allow('/path?query=value'))
        ->toThrow(InvalidArgumentException::class, 'Path cannot contain query parameters');
});

test('path cannot contain fragments', function (): void {
    $robots = new RobotsTxt();

    expect(fn (): \Fkrzski\RobotsTxt\RobotsTxt => $robots->disallow('/path#fragment'))
        ->toThrow(InvalidArgumentException::class, 'Path cannot contain fragments');
});

test('path cannot be empty', function (): void {
    $robots = new RobotsTxt();

    expect(fn (): \Fkrzski\RobotsTxt\RobotsTxt => $robots->allow(''))
        ->toThrow(InvalidArgumentException::class, 'Path cannot be empty');
});

test('sitemaps are rendered in order of addition', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->sitemap('https://example.com/sitemap1.xml')
        ->sitemap('https://example.com/sitemap2.xml')
        ->sitemap('https://example.com/sitemap3.xml');

    expect($robots->toString())->toBe(
        "Sitemap: https://example.com/sitemap1.xml\n".
        "Sitemap: https://example.com/sitemap2.xml\n".
        "Sitemap: https://example.com/sitemap3.xml"
    );
});

test('sitemaps maintain order when mixed with other rules', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->sitemap('https://example.com/sitemap1.xml')
        ->userAgent(CrawlerEnum::GOOGLE)
        ->disallow('/private')
        ->sitemap('https://example.com/sitemap2.xml')
        ->userAgent(CrawlerEnum::BING)
        ->disallow('/secret')
        ->sitemap('https://example.com/sitemap3.xml');

    expect($robots->toString())->toBe(
        "User-agent: Googlebot\n".
        "Disallow: /private\n".
        "\n".
        "User-agent: Bingbot\n".
        "Disallow: /secret\n".
        "\n".
        "Sitemap: https://example.com/sitemap1.xml\n".
        "Sitemap: https://example.com/sitemap2.xml\n".
        "Sitemap: https://example.com/sitemap3.xml"
    );
});

test('disallowAll clears global rules and adds disallow all', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->allow('/public')
        ->disallow('/private')
        ->disallowAll();

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /*"
    );
});

test('disallowAll clears only specific user agent rules', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->disallow('/admin')  // global rule
        ->userAgent(CrawlerEnum::GOOGLE)
        ->allow('/public')
        ->disallow('/private')
        ->disallowAll()
        ->userAgent(CrawlerEnum::BING)
        ->disallow('/secret');

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /admin\n".
        "\n".
        "User-agent: Googlebot\n".
        "Disallow: /*\n".
        "\n".
        "User-agent: Bingbot\n".
        "Disallow: /secret"
    );
});

test('disallowAll preserves sitemaps while clearing rules', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->allow('/public')
        ->sitemap('https://example.com/sitemap1.xml')
        ->disallow('/private')
        ->sitemap('https://example.com/sitemap2.xml')
        ->disallowAll();

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /*\n".
        "\n".
        "Sitemap: https://example.com/sitemap1.xml\n".
        "Sitemap: https://example.com/sitemap2.xml"
    );
});

test('disallowAll with false parameter does nothing', function (): void {
    $robots = new RobotsTxt();
    $robots
        ->disallow('/private')
        ->disallowAll(false);

    expect($robots->toString())->toBe(
        "User-agent: *\n".
        "Disallow: /private"
    );
});
