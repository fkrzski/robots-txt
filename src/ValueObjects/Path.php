<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use InvalidArgumentException;

final readonly class Path implements ValueObject
{
    public function __construct(
        private string $path
    )
    {
        $this->validate();
    }

    public function value(): string
    {
        return $this->path;
    }

    public function toString(): string
    {
        return $this->path;
    }

    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    public function validate(): void
    {
        if ($this->path === '') {
            throw new InvalidArgumentException('Path cannot be empty');
        }

        if (!str_starts_with($this->path, '/')) {
            throw new InvalidArgumentException('Path must start with forward slash (/)');
        }

        if (str_contains($this->path, '?')) {
            throw new InvalidArgumentException('Path cannot contain query parameters');
        }

        if (str_contains($this->path, '#')) {
            throw new InvalidArgumentException('Path cannot contain fragments');
        }

        if (preg_match('/\s/', $this->path)) {
            throw new InvalidArgumentException('Path cannot contain whitespace');
        }
    }
}