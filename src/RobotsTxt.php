<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt;

use Closure;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;
use Fkrzski\RobotsTxt\Enums\DirectiveEnum;
use Fkrzski\RobotsTxt\ValueObjects\CrawlDelay;
use Fkrzski\RobotsTxt\ValueObjects\Path;
use Fkrzski\RobotsTxt\ValueObjects\Rule;
use Fkrzski\RobotsTxt\ValueObjects\Sitemap;
use Fkrzski\RobotsTxt\ValueObjects\UserAgent;

final class RobotsTxt
{
    /** @var array<string, array<int, Rule>> */
    private array $userAgentRules = [];

    /** @var array<int, Rule> */
    private array $globalRules = [];

    /** @var array<int, Rule> */
    private array $sitemaps = [];

    /** @var CrawlerEnum|null */
    private ?CrawlerEnum $currentUserAgent = null;

    private function addRule(DirectiveEnum $directive, Path|CrawlDelay $value): self
    {
        $rule = new Rule($directive, $value);

        if ($this->currentUserAgent === null) {
            $this->globalRules[] = $rule;
        } else {
            $this->userAgentRules[$this->currentUserAgent->value][] = $rule;
        }

        return $this;
    }

    public function allow(string $path): self
    {
        return $this->addRule(DirectiveEnum::ALLOW, new Path($path));
    }

    public function disallow(string $path): self
    {
        return $this->addRule(DirectiveEnum::DISALLOW, new Path($path));
    }

    public function crawlDelay(int $seconds): self
    {
        return $this->addRule(DirectiveEnum::CRAWL_DELAY, new CrawlDelay($seconds));
    }

    public function forUserAgent(CrawlerEnum $crawler, Closure $rules): self
    {
        $previousUserAgent = $this->currentUserAgent;

        $this->userAgent($crawler);

        $rules($this);

        $this->currentUserAgent = $previousUserAgent;

        return $this;
    }

    public function userAgent(CrawlerEnum $crawler): self
    {
        $this->currentUserAgent = $crawler;

        if (!isset($this->userAgentRules[$crawler->value])) {
            $userAgent = new UserAgent($crawler);
            $rule = new Rule(DirectiveEnum::USER_AGENT, $userAgent);
            $this->userAgentRules[$crawler->value] = [$rule];
        }

        return $this;
    }

    public function sitemap(string $url): self
    {
        $this->sitemaps[] = new Rule(DirectiveEnum::SITEMAP, new Sitemap($url));

        return $this;
    }

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