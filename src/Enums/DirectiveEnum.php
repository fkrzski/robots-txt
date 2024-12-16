<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\Enums;

/**
 * Represents the standard directives available in robots.txt files.
 *
 * This enum defines the core directives that can be used in a robots.txt file
 * according to the robots.txt standard. Each case represents a directive that
 * controls crawler behavior.
 *
 * @link https://developers.google.com/search/docs/crawling-indexing/robots/robots_txt
 * @since 1.0.0
 */
enum DirectiveEnum: string
{
    case ALLOW = 'Allow';
    case DISALLOW = 'Disallow';
    case USER_AGENT = 'User-agent';
    case CRAWL_DELAY = 'Crawl-delay';
    case SITEMAP = 'Sitemap';
}
