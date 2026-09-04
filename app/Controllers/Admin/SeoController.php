<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Request;
use Lilyweb\Core\Response;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;
use Lilyweb\Core\Session;
use Lilyweb\Core\View;
use PDO;
use Exception;

final class SeoController extends AdminController
{
    /**
     * Show SEO, Google Search & AI Crawl (GEO) Management panel.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings` WHERE `setting_group` = 'seo' OR `setting_key` LIKE 'seo_%' OR `setting_key` LIKE 'google_%' OR `setting_key` LIKE 'ai_%'");
        $raw = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $seo = [
            'seo_meta_title_en' => $raw['seo_meta_title_en'] ?? 'Lily Interiors — Architectural Interior Design & Turnkey Solutions in Dhaka',
            'seo_meta_title_bn' => $raw['seo_meta_title_bn'] ?? 'লিলি ইন্টেরিয়র্স — ঢাকায় প্রিমিয়াম আর্কিটেকচারাল ইন্টেরিয়র ডিজাইন ও টার্নকি সল্যুশন',
            'seo_meta_desc_en' => $raw['seo_meta_desc_en'] ?? 'Award-winning interior design and architecture studio in Dhaka specializing in luxury residences, penthouses, duplexes, modular kitchens, and corporate offices.',
            'seo_meta_desc_bn' => $raw['seo_meta_desc_bn'] ?? 'ঢাকায় প্রিমিয়াম আর্কিটেকচারাল ইন্টেরিয়র ডিজাইন, মডিউলার কিচেন, ডুপ্লেক্স রিনোভেশন এবং টার্নকি সল্যুশন।',
            'seo_keywords' => $raw['seo_keywords'] ?? 'interior design dhaka, best interior company in bangladesh, luxury duplex interior, turnkey interior design, modular kitchen dhaka, office interior design',
            'google_site_verification' => $raw['google_site_verification'] ?? '',
            'bing_site_verification' => $raw['bing_site_verification'] ?? '',
            'google_analytics_id' => $raw['google_analytics_id'] ?? '',
            'google_tag_manager_id' => $raw['google_tag_manager_id'] ?? '',
            'og_image_url' => $raw['og_image_url'] ?? '/assets/img/hero-living-room.jpg',
            'ai_company_synopsis' => $raw['ai_company_synopsis'] ?? 'Lily Interiors is Dhaka\'s premier architectural interior design practice, established by licensed architects and master engineers. Specialized in luxury turnkey residences, executive duplexes in Gulshan, Banani, Dhanmondi, Uttara and corporate headquarters.',
            'ai_target_locations' => $raw['ai_target_locations'] ?? 'Gulshan, Banani, Dhanmondi, Uttara, Bashundhara, Baridhara, Mirpur, Dhaka, Bangladesh',
            'schema_price_range' => $raw['schema_price_range'] ?? '$$$',
            'schema_rating_val' => $raw['schema_rating_val'] ?? '4.9',
            'schema_review_count' => $raw['schema_review_count'] ?? '150',
            'custom_head_scripts' => $raw['custom_head_scripts'] ?? '',
            'custom_body_scripts' => $raw['custom_body_scripts'] ?? '',
        ];

        $content = View::make('admin.seo.index', [
            'seo' => $seo,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'SEO, Google Search & AI Optimization (GEO)',
            'pageHeading' => 'Google Crawlability, Meta Tags & AI Search (GEO) Engine',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save SEO & AI Search settings.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $keys = [
            'seo_meta_title_en', 'seo_meta_title_bn',
            'seo_meta_desc_en', 'seo_meta_desc_bn',
            'seo_keywords', 'google_site_verification', 'bing_site_verification',
            'google_analytics_id', 'google_tag_manager_id', 'og_image_url',
            'ai_company_synopsis', 'ai_target_locations',
            'schema_price_range', 'schema_rating_val', 'schema_review_count',
            'custom_head_scripts', 'custom_body_scripts',
        ];

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO `lilyweb_site_settings` (`setting_key`, `setting_value`, `setting_group`, `is_public`)
                VALUES (:k, :v, 'seo', 1)
                ON DUPLICATE KEY UPDATE `setting_value` = :v2
            ");

            foreach ($keys as $k) {
                $val = (string) Request::post($k, '');
                $stmt->execute([
                    ':k' => $k,
                    ':v' => $val,
                    ':v2' => $val,
                ]);
            }

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'seo_settings', 'seo_engine', Request::ip());
            Session::flash('success', 'SEO & AI Search (GEO) configuration saved and live on sitemap & Google metadata.');

        } catch (Exception $e) {
            Session::flash('error', 'Failed to update SEO settings: ' . $e->getMessage());
        }

        return Response::redirect('/admin/seo');
    }
}
