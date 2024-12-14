<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\Enums;

enum DirectiveEnum: string
{
    case ALLOW = 'Allow';
    case DISALLOW = 'Disallow';
    case USER_AGENT = 'User-agent';
    case CRAWL_DELAY = 'Crawl-delay';
    case SITEMAP = 'Sitemap';
}