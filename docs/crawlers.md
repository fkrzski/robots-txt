---
title: Crawlers
description: Every crawler in CrawlerEnum mapped to its official user-agent string, grouped by search engines, social platforms, SEO tools, and more.
---

`CrawlerEnum` maps each supported crawler to its official user-agent string. Pass a
case to `userAgent()` or `forUserAgent()` instead of typing the string by hand — a
typo becomes a compile-time error rather than a silently ignored rule.

```php
use Fkrzski\RobotsTxt\RobotsTxt;
use Fkrzski\RobotsTxt\Enums\CrawlerEnum;

echo (new RobotsTxt())
    ->userAgent(CrawlerEnum::GOOGLE)
    ->disallow('/private')
    ->toString();
```

```text
User-agent: Googlebot
Disallow: /private
```

The rendered `User-agent:` line is the enum's backing value, listed below.

## Search engines

### Google

| Case                        | User-agent            |
| --------------------------- | --------------------- |
| `CrawlerEnum::GOOGLE`        | `Googlebot`           |
| `CrawlerEnum::GOOGLE_IMAGES` | `Googlebot-Image`     |
| `CrawlerEnum::GOOGLE_NEWS`   | `Googlebot-News`      |
| `CrawlerEnum::GOOGLE_VIDEO`  | `Googlebot-Video`     |
| `CrawlerEnum::GOOGLE_ADS`    | `AdsBot-Google`       |
| `CrawlerEnum::GOOGLE_ADSENSE`| `Mediapartners-Google`|

### Bing

| Case                       | User-agent    |
| -------------------------- | ------------- |
| `CrawlerEnum::BING`         | `Bingbot`     |
| `CrawlerEnum::BING_PREVIEW` | `BingPreview` |
| `CrawlerEnum::MSN`          | `msnbot`      |
| `CrawlerEnum::MSN_MEDIA`    | `msnbot-media`|

### Other engines

| Case                          | User-agent         |
| ----------------------------- | ------------------ |
| `CrawlerEnum::YAHOO`           | `Slurp`            |
| `CrawlerEnum::DUCKDUCKGO`      | `DuckDuckBot`      |
| `CrawlerEnum::YANDEX`          | `YandexBot`        |
| `CrawlerEnum::BAIDU`           | `Baiduspider`      |
| `CrawlerEnum::NAVER`           | `Naverbot`         |
| `CrawlerEnum::SOGOU_WEB_SPIDER`| `Sogou web spider` |
| `CrawlerEnum::QWANT`           | `Qwantify`         |

## Social media

| Case                    | User-agent            |
| ----------------------- | --------------------- |
| `CrawlerEnum::FACEBOOK`  | `facebookexternalhit` |
| `CrawlerEnum::TWITTER`   | `Twitterbot`          |
| `CrawlerEnum::LINKEDIN`  | `LinkedInBot`         |
| `CrawlerEnum::PINTEREST` | `Pinterest`           |
| `CrawlerEnum::DISCORD`   | `Discordbot`          |
| `CrawlerEnum::SLACK`     | `Slackbot`            |
| `CrawlerEnum::WHATSAPP`  | `WhatsApp`            |

## E-commerce

| Case                  | User-agent  |
| --------------------- | ----------- |
| `CrawlerEnum::AMAZON`  | `Amazonbot` |

## Archives

| Case                          | User-agent                     |
| ----------------------------- | ------------------------------ |
| `CrawlerEnum::INTERNET_ARCHIVE`| `ia_archiver`                  |
| `CrawlerEnum::ALEXA`           | `ia_archiver-web.archive.org`  |
| `CrawlerEnum::ARCHIVE_ORG`     | `archive.org_bot`              |

## SEO tools

| Case                         | User-agent                  |
| ---------------------------- | --------------------------- |
| `CrawlerEnum::AHREFS`         | `AhrefsBot`                 |
| `CrawlerEnum::SEMRUSH`        | `SemrushBot`                |
| `CrawlerEnum::MAJESTIC`       | `MJ12bot`                   |
| `CrawlerEnum::MOZ`            | `rogerbot`                  |
| `CrawlerEnum::SCREAMING_FROG` | `Screaming Frog SEO Spider` |

## Feed readers & validators

| Case                       | User-agent          |
| -------------------------- | ------------------- |
| `CrawlerEnum::FEEDLY`       | `Feedly`            |
| `CrawlerEnum::W3C_VALIDATOR`| `W3C-checklink`     |
| `CrawlerEnum::CSS_VALIDATOR`| `W3C_CSS_Validator` |
| `CrawlerEnum::HTML_VALIDATOR`| `W3C_Validator`    |
