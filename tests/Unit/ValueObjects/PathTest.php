<?php

declare(strict_types=1);

use Fkrzski\RobotsTxt\ValueObjects\Path;

mutates(Path::class);

test('can create valid path', function (): void {
    $path = new Path('/valid/path');

    expect($path)
        ->toBeInstanceOf(Path::class)
        ->and($path->toString())->toBe('/valid/path');
});

test('path must start with forward slash', function (): void {
    expect(fn (): \Fkrzski\RobotsTxt\ValueObjects\Path => new Path('invalid/path'))
        ->toThrow(InvalidArgumentException::class, 'Path must start with forward slash (/)');
});

test('path cannot be empty', function (): void {
    expect(fn (): \Fkrzski\RobotsTxt\ValueObjects\Path => new Path(''))
        ->toThrow(InvalidArgumentException::class, 'Path cannot be empty');
});

test('path cannot contain query parameters', function (): void {
    expect(fn (): \Fkrzski\RobotsTxt\ValueObjects\Path => new Path('/path?query=value'))
        ->toThrow(InvalidArgumentException::class, 'Path cannot contain query parameters');
});

test('path cannot contain fragments', function (): void {
    expect(fn (): \Fkrzski\RobotsTxt\ValueObjects\Path => new Path('/path#fragment'))
        ->toThrow(InvalidArgumentException::class, 'Path cannot contain fragments');
});

test('path cannot contain whitespace', function (): void {
    expect(fn (): \Fkrzski\RobotsTxt\ValueObjects\Path => new Path('/path with space'))
        ->toThrow(InvalidArgumentException::class, 'Path cannot contain whitespace');
});

test('paths can be compared for equality', function (): void {
    $path1 = new Path('/path');
    $path2 = new Path('/path');
    $path3 = new Path('/other');

    expect($path1->equals($path2))->toBeTrue()
        ->and($path1->equals($path3))->toBeFalse()
        ->and($path2->equals($path3))->toBeFalse();
});
