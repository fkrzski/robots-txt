<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use Fkrzski\RobotsTxt\Enums\DirectiveEnum;

final readonly class Rule implements ValueObject
{
    public function __construct(
        public DirectiveEnum $directive,
        public ValueObject   $value
    ) {}

    public function value(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return sprintf('%s: %s', $this->directive->value, $this->value->toString());
    }

    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    public function validate(): void
    {
        $this->value->validate();
    }
}