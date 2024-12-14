<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\Enums\DirectiveEnum;

mutates(DirectiveEnum::class);

test('directive enum contains exactly 5 cases', function () {
    expect(count(DirectiveEnum::cases()))->toBe(5);
});

test('directive enum cases have correct string values', function (): void {
    expect(DirectiveEnum::ALLOW->value)->toBe('Allow')
        ->and(DirectiveEnum::DISALLOW->value)->toBe('Disallow')
        ->and(DirectiveEnum::USER_AGENT->value)->toBe('User-agent')
        ->and(DirectiveEnum::CRAWL_DELAY->value)->toBe('Crawl-delay')
        ->and(DirectiveEnum::SITEMAP->value)->toBe('Sitemap');
});

test('directive enum cases are in uppercase', function (): void {
    foreach (DirectiveEnum::cases() as $case) {
        expect($case->name)
            ->toBe(strtoupper($case->name));
    }
});

test('directive enum values do not contain whitespace at start or end', function (): void {
    foreach (DirectiveEnum::cases() as $case) {
        expect(trim($case->value))
            ->toBe($case->value);
    }
});

test('directive enum values contain only allowed characters', function (): void {
    foreach (DirectiveEnum::cases() as $case) {
        expect($case->value)
            ->toMatch('/^[a-zA-Z\-]+$/');
    }
});

test('directive enum has all required robot.txt directives', function (): void {
    $values = array_map(
        static fn (DirectiveEnum $case): string => $case->value,
        DirectiveEnum::cases()
    );

    expect($values)
        ->toContain('Allow')
        ->toContain('Disallow')
        ->toContain('User-agent')
        ->toContain('Crawl-delay')
        ->toContain('Sitemap');
});