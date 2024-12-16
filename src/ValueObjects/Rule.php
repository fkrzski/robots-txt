<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;
use Fkrzski\RobotsTxt\Enums\DirectiveEnum;

/**
 * Represents a complete rule in the robots.txt file.
 *
 * A rule consists of a directive (e.g., Allow, Disallow) and its value.
 * When converted to string, it produces a valid robots.txt line
 * in the format: "Directive: Value".
 *
 * @implements ValueObject<string>
 */
final readonly class Rule implements ValueObject
{
    /**
     * @param DirectiveEnum $directive The directive type for this rule
     * @param ValueObject<string|int|CrawlerEnum> $value The value for this directive
     */
    public function __construct(
        public DirectiveEnum $directive,
        public ValueObject   $value
    ) {}

    /** @inheritDoc */
    public function value(): string
    {
        return $this->toString();
    }

    /** @inheritDoc */
    public function toString(): string
    {
        return sprintf('%s: %s', $this->directive->value, $this->value->toString());
    }

    /** @inheritDoc */
    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    /** @inheritDoc */
    public function validate(): void
    {
        $this->value->validate();
    }
}