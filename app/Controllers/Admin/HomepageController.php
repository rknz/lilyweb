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

final class HomepageController extends AdminController
{
    /**
     * Master Homepage Section Builder & Manager.
     * Displays all homepage sections in the exact order of the frontend website:
     * 1. Hero Slider
     * 2. Achievement Stats
     * 3. About Us Section
     * 4. Services (6 Cards)
     * 5. Process Steps (4 Steps)
     * 6. Featured Portfolio
     * 7. Client Testimonials
     * 8. Contact & Consultation CTA
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();

        // 1. Hero Slides
        $heroStmt = $pdo->query("SELECT * FROM `lilyweb_hero_slides` ORDER BY `sort_order` ASC, `id` ASC");
        $heroSlides = $heroStmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Achievement Stats
        $statStmt = $pdo->query("SELECT * FROM `lilyweb_stats` ORDER BY `sort_order` ASC, `id` ASC");
        $statsList = $statStmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. About Us Settings
        $aboutStmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings` WHERE `setting_key` LIKE 'about_%'");
        $rawAbout = $aboutStmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $about = [
            'about_kicker_en' => $rawAbout['about_kicker_en'] ?? 'ABOUT LILY INTERIORS',
            'about_kicker_bn' => $rawAbout['about_kicker_bn'] ?? 'লিলি ইন্টেরিয়র্স পরিচিতি',
            'about_heading_en' => $rawAbout['about_heading_en'] ?? 'Designing Dreams, Building Reality',
            'about_heading_bn' => $rawAbout['about_heading_bn'] ?? 'স্বপ্ন থেকে বাস্তবতায় রূপান্তর',
            'about_lead_en' => $rawAbout['about_lead_en'] ?? 'At Lily Interiors, we believe great design is a harmonious balance of aesthetics, function and timeless elegance. Founded in Dhaka, our multidisciplinary team of architects, interior designers and master craftsmen have transformed over 250 luxury residences, penthouses, executive duplexes and corporate headquarters.',
            'about_lead_bn' => $rawAbout['about_lead_bn'] ?? 'লিলি ইন্টেরিয়র্সে আমরা বিশ্বাস করি আর্কিটেকচারাল ডিজাইন হলো সৌন্দর্য, উপযোগিতা ও আভিজাত্যের নিখুঁত মেলবন্ধন। ঢাকায় প্রতিষ্ঠিত আমাদের দক্ষ আর্কিটেক্ট ও প্রকৌশলী দল ২৫০টিরও বেশি বিলাসবহুল বাসস্থান, পেন্টহাউস, এক্সিকিউটিভ ডুপ্লেক্স ও কর্পোরেট হেডকোয়ার্টারে অসাধারণ ইন্টেরিয়র উপহার দিয়েছে।',
            'about_exp_years' => $rawAbout['about_exp_years'] ?? '8+',
            'about_exp_text_en' => $rawAbout['about_exp_text_en'] ?? 'Years of Architectural Craftsmanship',
            'about_exp_text_bn' => $rawAbout['about_exp_text_bn'] ?? 'বছরের নিখুঁত স্থাপত্য ও নির্মাণের অভিজ্ঞতা',
        ];

        // 4. Services
        $srvStmt = $pdo->query("SELECT * FROM `lilyweb_services` ORDER BY `sort_order` ASC, `id` ASC");
        $services = $srvStmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. Process Steps
        $procStmt = $pdo->query("SELECT * FROM `lilyweb_process_steps` ORDER BY `step_number` ASC, `id` ASC");
        $processSteps = $procStmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Featured Portfolio
        $projStmt = $pdo->query("SELECT * FROM `lilyweb_projects` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `created_at` DESC LIMIT 6");
        $featuredProjects = $projStmt->fetchAll(PDO::FETCH_ASSOC);

        // 7. Testimonials
        $testStmt = $pdo->query("SELECT * FROM `lilyweb_testimonials` ORDER BY `sort_order` ASC, `id` ASC");
        $testimonials = $testStmt->fetchAll(PDO::FETCH_ASSOC);

        // 8. Contact & Site Settings
        $contactStmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings` WHERE `setting_key` LIKE 'contact_%' OR `setting_key` LIKE 'site_%'");
        $rawContact = $contactStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $content = View::make('admin.homepage.index', [
            'heroSlides' => $heroSlides,
            'statsList' => $statsList,
            'about' => $about,
            'services' => $services,
            'processSteps' => $processSteps,
            'featuredProjects' => $featuredProjects,
            'testimonials' => $testimonials,
            'contactSettings' => $rawContact,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Homepage Master Section Builder',
            'pageHeading' => 'Homepage Sections & Content Builder',
            'content' => $content,
        ]);

        return new Response($html);
    }
}
