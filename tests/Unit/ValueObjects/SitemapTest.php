<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\ValueObjects\Sitemap;

mutates(Sitemap::class);

test('can create sitemap with valid https url', function (): void {
    $sitemap = new Sitemap('https://example.com/sitemap.xml');

    expect($sitemap)
        ->toBeInstanceOf(Sitemap::class)
        ->and($sitemap->toString())->toBe('https://example.com/sitemap.xml');
});

test('can create sitemap with valid http url', function (): void {
    $sitemap = new Sitemap('http://example.com/sitemap.xml');

    expect($sitemap)
        ->toBeInstanceOf(Sitemap::class)
        ->and($sitemap->toString())->toBe('http://example.com/sitemap.xml');
});

test('cannot create sitemap with empty string', function (): void {
    expect(fn (): Sitemap => new Sitemap(''))
        ->toThrow(InvalidArgumentException::class, 'Sitemap URL cannot be empty');
});

test('cannot create sitemap with invalid url format', function (): void {
    expect(fn (): Sitemap => new Sitemap('invalid-url'))
        ->toThrow(InvalidArgumentException::class, 'Invalid sitemap URL format');
});

test('cannot create sitemap with non-http protocol', function (): void {
    expect(fn (): Sitemap => new Sitemap('ftp://example.com/sitemap.xml'))
        ->toThrow(InvalidArgumentException::class, 'Sitemap URL must use HTTP(S) protocol');
});

test('cannot create sitemap with non-xml extension', function (): void {
    expect(fn (): Sitemap => new Sitemap('https://example.com/sitemap.html'))
        ->toThrow(InvalidArgumentException::class, 'Sitemap URL must be in .xml format');
});

test('sitemap urls with same value are equal', function (): void {
    $sitemap1 = new Sitemap('https://example.com/sitemap.xml');
    $sitemap2 = new Sitemap('https://example.com/sitemap.xml');
    $sitemap3 = new Sitemap('https://example.com/other-sitemap.xml');

    expect($sitemap1->equals($sitemap2))->toBeTrue()
        ->and($sitemap1->equals($sitemap3))->toBeFalse()
        ->and($sitemap2->equals($sitemap3))->toBeFalse();
});
