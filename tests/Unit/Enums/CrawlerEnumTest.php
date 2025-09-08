<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

mutates(CrawlerEnum::class);

test('crawler enum values are non-empty strings', function (): void {
    $cases = CrawlerEnum::cases();

    expect($cases)
        ->toBeArray()
        ->not->toBeEmpty();

    foreach ($cases as $case) {
        expect($case->value)
            ->toBeString()
            ->not->toBeEmpty();
    }
});

test('crawler enum values do not contain whitespace at start or end', function (): void {
    foreach (CrawlerEnum::cases() as $case) {
        expect(mb_trim($case->value))
            ->toBe($case->value);
    }
});

test('crawler enum values do not contain newlines or tabs', function (): void {
    foreach (CrawlerEnum::cases() as $case) {
        expect($case->value)
            ->not->toContain("\n")
            ->not->toContain("\r")
            ->not->toContain("\t");
    }
});

test('crawler enum values contain only allowed special characters', function (): void {
    foreach (CrawlerEnum::cases() as $case) {
        expect($case->value)
            ->toMatch('/^[a-zA-Z0-9\-_\.\s]+$/');
    }
});

test('crawler enum cases are in uppercase', function (): void {
    foreach (CrawlerEnum::cases() as $case) {
        expect($case->name)
            ->toBe(mb_strtoupper($case->name));
    }
});

test('each major search engine has representation', function (): void {
    $values = array_map(
        static fn (CrawlerEnum $crawlerEnum): string => $crawlerEnum->value,
        CrawlerEnum::cases()
    );

    expect($values)
        ->toContain('Googlebot')
        ->toContain('Bingbot')
        ->toContain('Slurp')  // Yahoo
        ->toContain('YandexBot')
        ->toContain('Baiduspider')
        ->toContain('DuckDuckBot');
});
