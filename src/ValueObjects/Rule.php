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
     * @param DirectiveEnum $directiveEnum The directive type for this rule
     * @param ValueObject<(string | int | CrawlerEnum)> $valueObject The value for this directive
     */
    public function __construct(
        public DirectiveEnum $directiveEnum,
        public ValueObject   $valueObject
    ) {
    }

    /** @inheritDoc */
    public function value(): string
    {
        return $this->toString();
    }

    /** @inheritDoc */
    public function toString(): string
    {
        return sprintf('%s: %s', $this->directiveEnum->value, $this->valueObject->toString());
    }

    /** @inheritDoc */
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    /** @inheritDoc */
    public function validate(): void
    {
        $this->valueObject->validate();
    }
}
