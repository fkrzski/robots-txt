<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\Enums\CrawlerEnum;
use Fkrzski\RobotsTxt\ValueObjects\UserAgent;

mutates(UserAgent::class);

test('can create user agent for Google', function (): void {
    $userAgent = new UserAgent(CrawlerEnum::GOOGLE);

    expect($userAgent)
        ->toBeInstanceOf(UserAgent::class)
        ->and($userAgent->toString())->toBe('Googlebot');
});

test('can create user agent for specialized Google bot', function (): void {
    $userAgent = new UserAgent(CrawlerEnum::GOOGLE_NEWS);

    expect($userAgent->toString())->toBe('Googlebot-News');
});

test('can create user agent for social media crawler', function (): void {
    $userAgent = new UserAgent(CrawlerEnum::FACEBOOK);

    expect($userAgent->toString())->toBe('facebookexternalhit');
});

test('user agents with same crawler are equal', function (): void {
    $agent1 = new UserAgent(CrawlerEnum::GOOGLE);
    $agent2 = new UserAgent(CrawlerEnum::GOOGLE);
    $agent3 = new UserAgent(CrawlerEnum::BING);

    expect($agent1->equals($agent2))->toBeTrue()
        ->and($agent1->equals($agent3))->toBeFalse()
        ->and($agent2->equals($agent3))->toBeFalse();
});
