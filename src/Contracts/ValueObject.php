<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\Contracts;

interface ValueObject
{
    public function value(): mixed;

    public function toString(): string;

    public function equals(self $other): bool;

    public function validate(): void;
}