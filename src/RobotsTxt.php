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
    private ?CrawlerEnum $currentUserAgent = null;

    /**
     * Adds a rule to either global rules or current user agent rules.
     *
     * @template T of string|int|CrawlerEnum
     *
     * @param DirectiveEnum $directive The type of rule to add
     * @param ValueObject<T> $value The value for the rule
     *
     * @return self For method chaining
     */
    private function addRule(DirectiveEnum $directive, ValueObject $value): self
    {
        $rule = new Rule($directive, $value);

        if ($this->currentUserAgent === null) {
            $this->globalRules[] = $rule;
        } else {
            $this->userAgentRules[$this->currentUserAgent->value][] = $rule;
        }

        return $this;
    }

    /**
     * Allows access to a specific path.
     *
     * @param string $path Path to allow
     *
     * @return self
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
     * @return self
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
     * @return self
     * @throws InvalidArgumentException If seconds is negative
     *
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
     * @param CrawlerEnum $crawler The crawler to apply rules to
     * @param Closure(RobotsTxt): void $rules Closure containing the rules
     *
     * @return self
     */
    public function forUserAgent(CrawlerEnum $crawler, Closure $rules): self
    {
        $previousUserAgent = $this->currentUserAgent;

        $this->userAgent($crawler);

        $rules($this);

        $this->currentUserAgent = $previousUserAgent;

        return $this;
    }

    /**
     * Sets the current user agent context for subsequent rules.
     *
     * All rules added after this call will apply to this user agent
     * until another user agent is set or rules are added to the global context.
     *
     * @param CrawlerEnum $crawler The crawler to set as current context
     *
     * @return self
     */
    public function userAgent(CrawlerEnum $crawler): self
    {
        $this->currentUserAgent = $crawler;

        if (!isset($this->userAgentRules[$crawler->value])) {
            /** @var ValueObject<CrawlerEnum|int|string> $userAgent */
            $userAgent = new UserAgent($crawler);
            $rule = new Rule(DirectiveEnum::USER_AGENT, $userAgent);
            $this->userAgentRules[$crawler->value] = [$rule];
        }

        return $this;
    }

    /**
     * Adds a sitemap URL to the robots.txt file.
     *
     * @param string $url URL of the sitemap (must be valid HTTP(S) URL ending in .xml)
     *
     * @return self
     * @throws InvalidArgumentException If URL format is invalid
     */
    public function sitemap(string $url): self
    {
        /** @var ValueObject<CrawlerEnum|int|string> $sitemapObject */
        $sitemapObject = new Sitemap($url);

        $this->sitemaps[] = new Rule(DirectiveEnum::SITEMAP, $sitemapObject);

        return $this;
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

        if (!empty($this->globalRules)) {
            $output[] = 'User-agent: *';

            foreach ($this->globalRules as $rule) {
                $output[] = $rule->toString();
            }

            $output[] = '';
        }

        foreach ($this->userAgentRules as $rules) {
            foreach ($rules as $rule) {
                $output[] = $rule->toString();
            }

            $output[] = '';
        }


        if (!empty($this->sitemaps)) {
            foreach ($this->sitemaps as $sitemapRule) {
                $output[] = $sitemapRule->toString();
            }

            $output[] = '';
        }

        return trim(implode("\n", $output), "\n");
    }
}