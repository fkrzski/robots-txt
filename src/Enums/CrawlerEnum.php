<?php

declare(strict_types=1);

namespace Fkrzski\RobotsTxt\Enums;

enum CrawlerEnum: string
{
    // Google Bots
    case GOOGLE = 'Googlebot';
    case GOOGLE_IMAGES = 'Googlebot-Image';
    case GOOGLE_NEWS = 'Googlebot-News';
    case GOOGLE_VIDEO = 'Googlebot-Video';
    case GOOGLE_ADS = 'AdsBot-Google';
    case GOOGLE_ADSENSE = 'Mediapartners-Google';

    // Bing Bots
    case BING = 'Bingbot';
    case BING_PREVIEW = 'BingPreview';
    case MSN = 'msnbot';
    case MSN_MEDIA = 'msnbot-media';

    // Yahoo Bots
    case YAHOO = 'Slurp';

    // Social Media Bots
    case FACEBOOK = 'facebookexternalhit';
    case TWITTER = 'Twitterbot';
    case LINKEDIN = 'LinkedInBot';
    case PINTEREST = 'Pinterest';
    case DISCORD = 'Discordbot';

    // E-commerce & Price Comparison
    case AMAZON = 'Amazonbot';

    // Search Engines
    case DUCKDUCKGO = 'DuckDuckBot';
    case YANDEX = 'YandexBot';
    case BAIDU = 'Baiduspider';
    case NAVER = 'Naverbot';
    case SOGOU_WEB_SPIDER = 'Sogou web spider';
    case QWANT = 'Qwantify';

    // Archives & Libraries
    case INTERNET_ARCHIVE = 'ia_archiver';
    case ALEXA = 'ia_archiver-web.archive.org';
    case ARCHIVE_ORG = 'archive.org_bot';

    // SEO & Analysis Tools
    case AHREFS = 'AhrefsBot';
    case SEMRUSH = 'SemrushBot';
    case MAJESTIC = 'MJ12bot';
    case MOZ = 'rogerbot';
    case SCREAMING_FROG = 'Screaming Frog SEO Spider';

    // Feed Readers & Validators
    case FEEDLY = 'Feedly';
    case W3C_VALIDATOR = 'W3C-checklink';
    case CSS_VALIDATOR = 'W3C_CSS_Validator';
    case HTML_VALIDATOR = 'W3C_Validator';

    // Others
    case SLACK = 'Slackbot';
    case WHATSAPP = 'WhatsApp';
}
