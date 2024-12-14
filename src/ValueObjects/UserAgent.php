<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

final readonly class UserAgent implements ValueObject
{
    public function __construct(
        private CrawlerEnum $crawler
    ) {}

    public function value(): CrawlerEnum
    {
        return $this->crawler;
    }

    public function toString(): string
    {
        return $this->crawler->value;
    }

    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    public function validate(): void
    {
        //
    }
}