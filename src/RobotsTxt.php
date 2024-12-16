<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt;

use Closure;
use Fkrzski\RobotsTxt\Contracts\ValueObject;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;
use Fkrzski\RobotsTxt\Enums\DirectiveEnum;
use Fkrzski\RobotsTxt\ValueObjects\CrawlDelay;
use Fkrzski\RobotsTxt\ValueObjects\Path;
use Fkrzski\RobotsTxt\ValueObjects\Rule;
use Fkrzski\RobotsTxt\ValueObjects\Sitemap;
use Fkrzski\RobotsTxt\ValueObjects\UserAgent;
use InvalidArgumentException;
use RuntimeException;

/**
 * Main class for building robots.txt files with a fluent interface.
 *
 * Provides methods for defining rules for specific user agents,
 * adding global rules, and generating the final robots.txt content.
 *
 * Example usage:
 * ```php
 * $robots = new RobotsTxt();
 * $robots
 *     ->disallow('/admin')     // Global rule
 *     ->userAgent(CrawlerEnum::GOOGLE)
 *     ->allow('/public')
 *     ->disallow('/private')
 *     ->sitemap('https://example.com/sitemap.xml');
 *
 * echo $robots->toString();
 * ```
 *
 * @since 1.0.0
 */
final class RobotsTxt
{
    /** @var array<string, array<int, Rule>> Mapping of user agent strings to their rules */
    private array $userAgentRules = [];

    /** @var array<int, Rule> Rules that apply to all user agents */
    private array $globalRules = [];

    /** @var array<int, Rule> Sitemap directives */
    private array $sitemaps = [];

    /** @var CrawlerEnum|null Current user agent context for rule addition */
    private ?CrawlerEnum $crawlerEnum = null;

    /**
     * Allows access to a specific path.
     *
     * @param string $path Path to allow
     *
     * @throws InvalidArgumentException If path format is invalid
     */
    public function allow(string $path): self
    {
        return $this->addRule(DirectiveEnum::ALLOW, new Path($path));
    }

    /**
     * Prevents access to a specific path.
     *
     * @param string $path Path to disallow
     *
     * @throws InvalidArgumentException If path format is invalid
     */
    public function disallow(string $path): self
    {
        return $this->addRule(DirectiveEnum::DISALLOW, new Path($path));
    }

    /**
     * Sets the crawl delay for the current context.
     *
     * @param int $seconds Delay in seconds (must be non-negative)
     *
     * @throws InvalidArgumentException If seconds is negative
     */
    public function crawlDelay(int $seconds): self
    {
        return $this->addRule(DirectiveEnum::CRAWL_DELAY, new CrawlDelay($seconds));
    }

    /**
     * Applies rules for a specific user agent using a closure.
     *
     * This method provides a convenient way to group rules for a specific crawler.
     * The rules defined in the closure will only apply to the specified user agent.
     *
     * Example:
     * ```php
     * $robots->forUserAgent(CrawlerEnum::GOOGLE, function (RobotsTxt $robots): void {
     *     $robots
     *         ->disallow('/private')
     *         ->allow('/public');
     * });
     * ```
     *
     * @param CrawlerEnum $crawlerEnum The crawler to apply rules to
     * @param Closure(RobotsTxt): void $rules Closure containing the rules
     */
    public function forUserAgent(CrawlerEnum $crawlerEnum, Closure $rules): self
    {
        $previousUserAgent = $this->crawlerEnum;

        $this->userAgent($crawlerEnum);

        $rules($this);

        $this->crawlerEnum = $previousUserAgent;

        return $this;
    }

    /**
     * Sets the current user agent context for subsequent rules.
     *
     * All rules added after this call will apply to this user agent
     * until another user agent is set or rules are added to the global context.
     *
     * @param CrawlerEnum $crawlerEnum The crawler to set as current context
     */
    public function userAgent(CrawlerEnum $crawlerEnum): self
    {
        $this->crawlerEnum = $crawlerEnum;

        if (!isset($this->userAgentRules[$crawlerEnum->value])) {
            /** @var ValueObject<CrawlerEnum|int|string> $userAgent */
            $userAgent = new UserAgent($crawlerEnum);
            $rule = new Rule(DirectiveEnum::USER_AGENT, $userAgent);
            $this->userAgentRules[$crawlerEnum->value] = [$rule];
        }

        return $this;
    }

    /**
     * Adds a sitemap URL to the robots.txt file.
     *
     * @param string $url URL of the sitemap (must be valid HTTP(S) URL ending in .xml)
     *
     * @throws InvalidArgumentException If URL format is invalid
     */
    public function sitemap(string $url): self
    {
        $sitemap = new Sitemap($url);

        $this->sitemaps[] = new Rule(DirectiveEnum::SITEMAP, $sitemap);

        return $this;
    }

    /**
     * Disallows or allows access to all paths using a wildcard.
     *
     * This is a convenience method for quickly blocking or allowing access to the entire site.
     * When disallowing ($disallow = true), it clears all existing rules for the current context
     * and adds a single "Disallow: /*" rule.
     *
     * @param bool $disallow If true, disallows all paths and clears other rules. If false, allows all paths.
     *
     * @return self For method chaining
     * @throws InvalidArgumentException If path format is invalid
     */
    public function disallowAll(bool $disallow = true): self
    {
        if ($disallow) {
            if ($this->crawlerEnum instanceof CrawlerEnum) {
                $userAgentRule = $this->userAgentRules[$this->crawlerEnum->value][0];
                $this->userAgentRules[$this->crawlerEnum->value] = [$userAgentRule];
            } else {
                $this->globalRules = [];
            }

            $this->disallow('/*');
        }

        return $this;
    }

    /**
     * Saves the robots.txt content to a file.
     *
     * If no path is provided, saves to robots.txt in the project root directory.
     * Throws an exception if the file cannot be written or the directory is not writable.
     *
     * @param string|null $path Optional custom path where the file should be saved
     *
     * @return bool True if the file was successfully written
     * @throws RuntimeException If the file cannot be written
     */
    public function toFile(?string $path = null): bool
    {
        $filePath = $path ?? (getcwd() ?: __DIR__).'/robots.txt';

        if (file_exists($filePath) && !is_writable($filePath)) {
            throw new RuntimeException('Existing robots.txt file is not writable');
        }

        $directory = dirname($filePath);
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new RuntimeException('Directory is not writable');
        }

        return (bool)file_put_contents($filePath, $this->toString());
    }

    /**
     * Generates the complete robots.txt content.
     *
     * The output follows this order:
     * 1. Global rules (if any)
     * 2. User agent specific rules
     * 3. Sitemap directives
     *
     * Rules are separated by newlines and groups of rules are
     * separated by blank lines.
     *
     * @return string The complete robots.txt content
     */
    public function toString(): string
    {
        $output = [];

        if ($this->globalRules !== []) {
            $output[] = 'User-agent: *';

            foreach ($this->globalRules as $globalRule) {
                $output[] = $globalRule->toString();
            }

            $output[] = '';
        }

        foreach ($this->userAgentRules as $userAgentRule) {
            foreach ($userAgentRule as $rule) {
                $output[] = $rule->toString();
            }

            $output[] = '';
        }


        if ($this->sitemaps !== []) {
            foreach ($this->sitemaps as $sitemap) {
                $output[] = $sitemap->toString();
            }

            $output[] = '';
        }

        return trim(implode("\n", $output), "\n");
    }

    /**
     * Adds a rule to either global rules or current user agent rules.
     *
     * @template T of string|int|CrawlerEnum
     *
     * @param DirectiveEnum $directiveEnum The type of rule to add
     * @param ValueObject<T> $valueObject The value for the rule
     *
     * @return self For method chaining
     */
    private function addRule(DirectiveEnum $directiveEnum, ValueObject $valueObject): self
    {
        $rule = new Rule($directiveEnum, $valueObject);

        if (!$this->crawlerEnum instanceof CrawlerEnum) {
            $this->globalRules[] = $rule;
        } else {
            $this->userAgentRules[$this->crawlerEnum->value][] = $rule;
        }

        return $this;
    }
}
