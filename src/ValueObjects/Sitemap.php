<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use InvalidArgumentException;

final readonly class Sitemap implements ValueObject
{
    public function __construct(
        public string $url
    ) {
        $this->validate();
    }

    public function value(): string
    {
        return $this->url;
    }

    public function toString(): string
    {
        return $this->url;
    }

    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    public function validate(): void
    {
        if ($this->url === '') {
            throw new InvalidArgumentException('Sitemap URL cannot be empty');
        }

        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid sitemap URL format');
        }

        $scheme = parse_url($this->url, PHP_URL_SCHEME);
        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new InvalidArgumentException('Sitemap URL must use HTTP(S) protocol');
        }

        if (!str_ends_with($this->url, '.xml')) {
            throw new InvalidArgumentException('Sitemap URL must be in .xml format');
        }
    }
}