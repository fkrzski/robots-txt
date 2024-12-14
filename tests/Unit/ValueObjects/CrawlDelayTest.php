<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\ValueObjects\CrawlDelay;

mutates(CrawlDelay::class);

test('can create crawl delay with positive value', function (): void {
    $delay = new CrawlDelay(10);

    expect($delay->value())->toBe(10)
        ->and($delay->toString())->toBe('10');
});

test('can create crawl delay with zero', function (): void {
    $delay = new CrawlDelay(0);

    expect($delay->value())->toBe(0)
        ->and($delay->toString())->toBe('0');
});

test('cannot create crawl delay with negative value', function (): void {
    expect(fn () => new CrawlDelay(-1))
        ->toThrow(InvalidArgumentException::class, 'Crawl delay cannot be negative');
});

test('crawl delays with same value are equal', function (): void {
    $delay1 = new CrawlDelay(10);
    $delay2 = new CrawlDelay(10);
    $delay3 = new CrawlDelay(20);

    expect($delay1->equals($delay2))->toBeTrue()
        ->and($delay1->equals($delay3))->toBeFalse()
        ->and($delay2->equals($delay3))->toBeFalse();
});

test('crawl delay string representation is numeric', function (): void {
    $delay = new CrawlDelay(5);

    expect($delay->toString())
        ->toBeString()
        ->toMatch('/^\d+$/');
});