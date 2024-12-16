<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\ValueObjects\Rule;
use Fkrzski\RobotsTxt\ValueObjects\Path;
use Fkrzski\RobotsTxt\ValueObjects\CrawlDelay;
use Fkrzski\RobotsTxt\Enums\DirectiveEnum;

mutates(Rule::class);

test('can create allow rule with path', function (): void {
    $path = new Path('/path');
    $rule = new Rule(DirectiveEnum::ALLOW, $path);

    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->and($rule->directiveEnum)->toBe(DirectiveEnum::ALLOW)
        ->and($rule->valueObject)->toBe($path)
        ->and($rule->toString())->toBe('Allow: /path');
});

test('can create crawl delay rule', function (): void {
    $delay = new CrawlDelay(10);
    $rule = new Rule(DirectiveEnum::CRAWL_DELAY, $delay);

    expect($rule->toString())->toBe('Crawl-delay: 10');
});

test('rules with same values are equal', function (): void {
    $path1 = new Path('/path');
    $path2 = new Path('/path');
    $path3 = new Path('/other');

    $rule1 = new Rule(DirectiveEnum::ALLOW, $path1);
    $rule2 = new Rule(DirectiveEnum::ALLOW, $path2);
    $rule3 = new Rule(DirectiveEnum::ALLOW, $path3);

    expect($rule1->equals($rule2))->toBeTrue()
        ->and($rule1->equals($rule3))->toBeFalse();
});

test('rules with different directives are not equal', function (): void {
    $path = new Path('/path');

    $rule1 = new Rule(DirectiveEnum::ALLOW, $path);
    $rule2 = new Rule(DirectiveEnum::ALLOW, $path);
    $rule3 = new Rule(DirectiveEnum::DISALLOW, $path);

    expect($rule1->equals($rule2))->toBeTrue()
        ->and($rule1->equals($rule3))->toBeFalse()
        ->and($rule2->equals($rule3))->toBeFalse();
});
