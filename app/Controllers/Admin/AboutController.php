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

final class AboutController extends AdminController
{
    /**
     * Show About Us settings editor form.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings` WHERE `setting_key` LIKE 'about_%'");
        $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $about = [
            'about_kicker_en' => $rawSettings['about_kicker_en'] ?? 'ABOUT LILY INTERIORS',
            'about_kicker_bn' => $rawSettings['about_kicker_bn'] ?? 'লিলি ইন্টেরিয়র্স পরিচিতি',
            'about_heading_en' => $rawSettings['about_heading_en'] ?? 'Designing Dreams, Building Reality',
            'about_heading_bn' => $rawSettings['about_heading_bn'] ?? 'স্বপ্ন থেকে বাস্তবতায় রূপান্তর',
            'about_lead_en' => $rawSettings['about_lead_en'] ?? 'At Lily Interiors, we believe great design is a harmonious balance of aesthetics, function and timeless elegance. Founded in Dhaka, our multidisciplinary team of architects, interior designers and master craftsmen have transformed over 250 luxury residences, penthouses, executive duplexes and corporate headquarters.',
            'about_lead_bn' => $rawSettings['about_lead_bn'] ?? 'লিলি ইন্টেরিয়র্সে আমরা বিশ্বাস করি আর্কিটেকচারাল ডিজাইন হলো সৌন্দর্য, উপযোগিতা ও আভিজাত্যের নিখুঁত মেলবন্ধন। ঢাকায় প্রতিষ্ঠিত আমাদের দক্ষ আর্কিটেক্ট ও প্রকৌশলী দল ২৫০টিরও বেশি বিলাসবহুল বাসস্থান, পেন্টহাউস, এক্সিকিউটিভ ডুপ্লেক্স ও কর্পোরেট হেডকোয়ার্টারে অসাধারণ ইন্টেরিয়র উপহার দিয়েছে।',
            'about_link_text_en' => $rawSettings['about_link_text_en'] ?? 'More about our services &rarr;',
            'about_link_text_bn' => $rawSettings['about_link_text_bn'] ?? 'আমাদের সেবা সম্পর্কে আরও জানুন &rarr;',
            'about_link_url' => $rawSettings['about_link_url'] ?? '#services',
            'about_img_main' => $rawSettings['about_img_main'] ?? '/assets/img/about-1.jpg',
            'about_img_secondary' => $rawSettings['about_img_secondary'] ?? '/assets/img/about-2.jpg',
            'about_exp_years' => $rawSettings['about_exp_years'] ?? '8+',
            'about_exp_text_en' => $rawSettings['about_exp_text_en'] ?? 'Years of Architectural Craftsmanship',
            'about_exp_text_bn' => $rawSettings['about_exp_text_bn'] ?? 'বছরের নিখুঁত স্থাপত্য ও নির্মাণের অভিজ্ঞতা',
        ];

        $content = View::make('admin.about.index', [
            'about' => $about,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'About Us Section Management',
            'pageHeading' => 'About Us Section Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save About Us settings.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $keys = [
            'about_kicker_en', 'about_kicker_bn',
            'about_heading_en', 'about_heading_bn',
            'about_lead_en', 'about_lead_bn',
            'about_link_text_en', 'about_link_text_bn',
            'about_link_url',
            'about_img_main', 'about_img_secondary',
            'about_exp_years', 'about_exp_text_en', 'about_exp_text_bn',
        ];

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO `lilyweb_site_settings` (`setting_key`, `setting_value`, `setting_group`, `is_public`)
                VALUES (:k, :v, 'general', 1)
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

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'settings_about', 'about_section', Request::ip());
            Session::flash('success', 'About Us section updated successfully and live on website.');

        } catch (Exception $e) {
            Session::flash('error', 'Failed to update About section: ' . $e->getMessage());
        }

        return Response::redirect('/admin/about');
    }
}
