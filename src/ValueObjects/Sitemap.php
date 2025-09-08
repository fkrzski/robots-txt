<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\ValueObjects;

use Fkrzski\RobotsTxt\Contracts\ValueObject;
use InvalidArgumentException;
use Override;

/**
 * Represents a sitemap URL in the robots.txt file.
 *
 * A valid sitemap URL must:
 * - Be a valid URL
 * - Use either HTTP or HTTPS protocol
 * - End with .xml extension
 * - Not be empty
 *
 * @implements ValueObject<string>
 */
final readonly class Sitemap implements ValueObject
{
    public function __construct(
        public string $url
    ) {
        $this->validate();
    }

    /** {@inheritDoc} */
    #[Override]
    public function value(): string
    {
        return $this->url;
    }

    /** {@inheritDoc} */
    #[Override]
    public function toString(): string
    {
        return $this->url;
    }

    /** {@inheritDoc} */
    #[Override]
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    /** {@inheritDoc} */
    #[Override]
    public function validate(): void
    {
        if ($this->url === '') {
            throw new InvalidArgumentException('Sitemap URL cannot be empty');
        }

        if (! filter_var($this->url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid sitemap URL format');
        }

        $scheme = parse_url($this->url, PHP_URL_SCHEME);
        if (! in_array($scheme, ['http', 'https'], true)) {
            throw new InvalidArgumentException('Sitemap URL must use HTTP(S) protocol');
        }

        if (! str_ends_with($this->url, '.xml')) {
            throw new InvalidArgumentException('Sitemap URL must be in .xml format');
        }
    }
}
