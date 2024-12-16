<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use InvalidArgumentException;

/**
 * Represents a path in the robots.txt file.
 *
 * A valid path must:
 * - Start with a forward slash (/)
 * - Not contain query parameters
 * - Not contain fragments
 * - Not contain whitespace
 * - Not be empty
 *
 * @implements ValueObject<string>
 */
final readonly class Path implements ValueObject
{
    public function __construct(
        private string $path
    ) {
        $this->validate();
    }

    /** @inheritDoc */
    public function value(): string
    {
        return $this->path;
    }

    /** @inheritDoc */
    public function toString(): string
    {
        return $this->path;
    }

    /** @inheritDoc */
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    /** @inheritDoc */
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
