<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

/**
 * Represents a User-Agent directive in the robots.txt file.
 *
 * Maps a crawler enum to its corresponding User-Agent string value
 * as defined in the robots.txt standard.
 *
 * @implements ValueObject<CrawlerEnum>
 */
final readonly class UserAgent implements ValueObject
{
    public function __construct(
        private CrawlerEnum $crawler
    ) {}

    /** @inheritDoc */
    public function value(): CrawlerEnum
    {
        return $this->crawler;
    }

    /** @inheritDoc */
    public function toString(): string
    {
        return $this->crawler->value;
    }

    /** @inheritDoc */
    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    /** @inheritDoc */
    public function validate(): void
    {
        //
    }
}