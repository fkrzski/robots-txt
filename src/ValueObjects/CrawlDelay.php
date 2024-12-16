<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use InvalidArgumentException;

/**
 * Represents a crawl delay directive in seconds.
 *
 * The crawl delay specifies how many seconds a crawler should wait
 * between successive requests to the same server.
 *
 * @implements ValueObject<int>
 */
final readonly class CrawlDelay implements ValueObject
{
    public function __construct(
        private int $seconds
    ) {
        $this->validate();
    }

    /** @inheritDoc */
    public function value(): int
    {
        return $this->seconds;
    }

    /** @inheritDoc */
    public function toString(): string
    {
        return (string) $this->seconds;
    }

    /** @inheritDoc */
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    /** @inheritDoc */
    public function validate(): void
    {
        if ($this->seconds < 0) {
            throw new InvalidArgumentException('Crawl delay cannot be negative');
        }
    }
}
