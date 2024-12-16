<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\Contracts;

use InvalidArgumentException;

/**
 * Contract for immutable value objects in the robots.txt system.
 *
 * This interface defines the core behavior required for all value objects
 * in the robots.txt management system. Value objects are immutable entities
 * that represent a specific value or concept (like a path, user agent, or sitemap URL)
 * and are compared based on their values rather than their identity.
 *
 * @template-covariant T The type of the value stored in the value object
 * @since 1.0.0
 */
interface ValueObject
{
    /**
     * Returns a string representation of the value object.
     *
     * This method is used when generating the robots.txt file content.
     * Each value object must be able to represent itself as a valid
     * robots.txt string.
     *
     * @return T The raw value stored in the value object
     */
    public function value(): mixed;

    /**
     * Returns a string representation of the value object.
     *
     * This method is used when generating the robots.txt file content.
     * Each value object must be able to represent itself as a valid
     * robots.txt string.
     *
     * @return string The string representation of the value object
     */
    public function toString(): string;

    /**
     * Compares this value object with another for equality.
     *
     * Value objects are considered equal if they have the same type
     * and their values are equal. This is different from identity
     * comparison (===) which compares object references.
     *
     * @param ValueObject<mixed> $valueObject The value object to compare with
     *
     * @return bool True if the value objects are equal, false otherwise
     */
    public function equals(self $valueObject): bool;

    /**
     * Validates the value object's internal state.
     *
     * This method should be called in the constructor to ensure
     * the value object is always in a valid state. It should throw
     * an exception if any validation rules are violated.
     *
     * @throws InvalidArgumentException If the value object's state is invalid
     */
    public function validate(): void;
}
