<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use InvalidArgumentException;

final readonly class CrawlDelay implements ValueObject
{
    public function __construct(
        private int $seconds
    ) {
        $this->validate();
    }

    public function value(): int
    {
        return $this->seconds;
    }

    public function toString(): string
    {
        return (string) $this->seconds;
    }

    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    public function validate(): void
    {
        if ($this->seconds < 0) {
            throw new InvalidArgumentException('Crawl delay cannot be negative');
        }
    }
}