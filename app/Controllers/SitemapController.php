<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Response;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;
use PDO;

final class SitemapController extends Controller
{
    /**
     * Generate dynamic XML Sitemap for Google, Bing & AI Web Crawlers.
     */
    public function sitemap(): Response
    {
        $domain = 'https://lilyinteriorsbd.com';
        if (!empty($_SERVER['HTTP_HOST']) && Security::isValidHost($_SERVER['HTTP_HOST'])) {
            $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $domain = $proto . '://' . $_SERVER['HTTP_HOST'];
        }

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT `slug`, `title_en`, `cover_image`, `updated_at` FROM `lilyweb_projects` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xhtml="http://www.w3.org/1999/xhtml"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // Static core routes
        $corePages = [
            ['loc' => '/', 'priority' => '1.0', 'freq' => 'weekly', 'img' => '/assets/img/hero-living-room.webp', 'img_title' => 'Lily Interiors Architectural Design Showcase'],
            ['loc' => '/projects', 'priority' => '0.9', 'freq' => 'weekly', 'img' => '/assets/img/project-1.webp', 'img_title' => 'Lily Interiors Portfolio Projects'],
            ['loc' => '/services', 'priority' => '0.8', 'freq' => 'monthly', 'img' => '/assets/img/about-1.webp', 'img_title' => 'Lily Interiors Comprehensive Services'],
            ['loc' => '/about', 'priority' => '0.8', 'freq' => 'monthly', 'img' => '/assets/img/about-1.webp', 'img_title' => 'About Lily Interiors'],
            ['loc' => '/faq', 'priority' => '0.8', 'freq' => 'monthly'],
            ['loc' => '/privacy-policy', 'priority' => '0.3', 'freq' => 'yearly'],
            ['loc' => '/terms', 'priority' => '0.3', 'freq' => 'yearly'],
        ];

        foreach ($corePages as $p) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$domain}{$p['loc']}</loc>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$domain}{$p['loc']}\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"bn\" href=\"{$domain}{$p['loc']}?lang=bn\"/>\n";
            $xml .= "    <changefreq>{$p['freq']}</changefreq>\n";
            $xml .= "    <priority>{$p['priority']}</priority>\n";
            if (!empty($p['img'])) {
                $xml .= "    <image:image>\n";
                $xml .= "      <image:loc>{$domain}{$p['img']}</image:loc>\n";
                $xml .= "      <image:title>" . htmlspecialchars($p['img_title'] ?? '', ENT_XML1, 'UTF-8') . "</image:title>\n";
                $xml .= "    </image:image>\n";
            }
            $xml .= "  </url>\n";
        }

        // Dynamic Projects
        foreach ($projects as $proj) {
            $slug = htmlspecialchars($proj['slug'], ENT_QUOTES, 'UTF-8');
            $date = date('Y-m-d', strtotime($proj['updated_at'] ?? 'now'));
            $imgUrl = !empty($proj['cover_image']) ? $proj['cover_image'] : '/assets/img/project-1.webp';
            if (!str_starts_with($imgUrl, 'http')) {
                $imgUrl = $domain . $imgUrl;
            }

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$domain}/projects/{$slug}</loc>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$domain}/projects/{$slug}\"/>\n";
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"bn\" href=\"{$domain}/projects/{$slug}?lang=bn\"/>\n";
            $xml .= "    <lastmod>{$date}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.85</priority>\n";
            $xml .= "    <image:image>\n";
            $xml .= "      <image:loc>" . htmlspecialchars($imgUrl, ENT_XML1, 'UTF-8') . "</image:loc>\n";
            $xml .= "      <image:title>" . htmlspecialchars($proj['title_en'], ENT_XML1, 'UTF-8') . " by Lily Interiors</image:title>\n";
            $xml .= "    </image:image>\n";
            $xml .= "  </url>\n";
}

        $xml .= '</urlset>';

        return new Response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /**
     * Generate dynamic robots.txt.
     */
    public function robots(): Response
    {
        $domain = 'https://lilyinteriorsbd.com';
        if (!empty($_SERVER['HTTP_HOST']) && Security::isValidHost($_SERVER['HTTP_HOST'])) {
            $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $domain = $proto . '://' . $_SERVER['HTTP_HOST'];
        }

        $txt = "User-agent: *\n";
        $txt .= "Allow: /\n";
        $txt .= "Disallow: /admin/\n";
        $txt .= "Disallow: /admin\n";
        $txt .= "\n";
        $txt .= "# Google Gemini & OpenAI AI Search Crawlers\n";
        $txt .= "User-agent: Google-Extended\n";
        $txt .= "Allow: /\n";
        $txt .= "User-agent: GPTBot\n";
        $txt .= "Allow: /\n";
        $txt .= "User-agent: PerplexityBot\n";
        $txt .= "Allow: /\n";
        $txt .= "\n";
        $txt .= "Sitemap: {$domain}/sitemap.xml\n";

        return new Response($txt, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
}
