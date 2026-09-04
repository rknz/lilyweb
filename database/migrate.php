<?php

declare(strict_types=1);

/**
 * Database Migration & Seeding Runner for Lily Interiors CMS.
 *
 * Enforces:
 * - Table prefix `lilyweb_`
 * - Engine `InnoDB`
 * - Character set `utf8mb4` with collation `utf8mb4_unicode_ci`
 * - Complete isolation from foreign host databases.
 */

require_once __DIR__ . '/../app/Core/autoload.php';

use Lilyweb\Core\Env;
use Lilyweb\Core\Config;

$basePath = dirname(__DIR__);
Env::load($basePath);
Config::load($basePath . '/config');

$config = Config::get('database.connections.mysql', []);
$host = $config['host'] ?? '127.0.0.1';
$port = (int) ($config['port'] ?? 3306);
$dbName = $config['database'] ?? 'lily_web';
$user = $config['username'] ?? 'root';
$pass = $config['password'] ?? '';

echo "=== LILY INTERIORS DATABASE MIGRATION ENGINE ===\n";
echo "Connecting to MySQL server at {$host}:{$port}...\n";

try {
    $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Ensure database exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `{$dbName}`;");
    echo "[✓] Database `{$dbName}` selected and ready.\n\n";

    // 1. Users Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_users` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(60) NOT NULL UNIQUE,
            `email` VARCHAR(190) NOT NULL UNIQUE,
            `password_hash` VARCHAR(255) NOT NULL,
            `display_name` VARCHAR(100) NOT NULL DEFAULT 'Owner',
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `last_login_at` DATETIME NULL,
            `remember_token` VARCHAR(100) NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_users` verified.\n";

    // 2. Site Settings Table (Includes SEO, Socials, Brand NAP, Legal Text)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_site_settings` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `setting_key` VARCHAR(100) NOT NULL UNIQUE,
            `setting_value` LONGTEXT NULL,
            `setting_group` ENUM('general', 'contact', 'social', 'legal', 'seo') NOT NULL DEFAULT 'general',
            `is_public` TINYINT(1) NOT NULL DEFAULT 1,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_group` (`setting_group`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_site_settings` verified.\n";

    // 3. Media Assets Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_media_assets` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `original_name` VARCHAR(255) NOT NULL,
            `filename` VARCHAR(255) NOT NULL,
            `storage_path` VARCHAR(255) NOT NULL UNIQUE,
            `mime_type` VARCHAR(100) NOT NULL,
            `size_bytes` BIGINT UNSIGNED NOT NULL DEFAULT 0,
            `width` INT UNSIGNED NULL,
            `height` INT UNSIGNED NULL,
            `alt_en` VARCHAR(255) NULL,
            `alt_bn` VARCHAR(255) NULL,
            `hash_sha256` CHAR(64) NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_mime` (`mime_type`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_media_assets` verified.\n";

    // 4. Hero Slides Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_hero_slides` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `step_number` VARCHAR(10) NOT NULL DEFAULT '01',
            `kicker_en` VARCHAR(100) NOT NULL,
            `kicker_bn` VARCHAR(100) NULL,
            `title_prefix_en` VARCHAR(150) NOT NULL,
            `title_prefix_bn` VARCHAR(150) NULL,
            `title_highlight_en` VARCHAR(100) NOT NULL,
            `title_highlight_bn` VARCHAR(100) NULL,
            `title_suffix_en` VARCHAR(150) NOT NULL,
            `title_suffix_bn` VARCHAR(150) NULL,
            `subtitle_en` TEXT NOT NULL,
            `subtitle_bn` TEXT NULL,
            `image_url` VARCHAR(255) NOT NULL,
            `badge_room_en` VARCHAR(100) NOT NULL,
            `badge_room_bn` VARCHAR(100) NULL,
            `badge_location_en` VARCHAR(100) NOT NULL,
            `badge_location_bn` VARCHAR(100) NULL,
            `cta_text_en` VARCHAR(100) NOT NULL,
            `cta_text_bn` VARCHAR(100) NULL,
            `cta_url` VARCHAR(255) NOT NULL DEFAULT '#portfolio',
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_active_sort` (`is_active`, `sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_hero_slides` verified.\n";

    // 5. Services Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_services` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `slug` VARCHAR(100) NOT NULL UNIQUE,
            `title_en` VARCHAR(150) NOT NULL,
            `title_bn` VARCHAR(150) NULL,
            `tag_badge_en` VARCHAR(100) NOT NULL,
            `tag_badge_bn` VARCHAR(100) NULL,
            `summary_en` TEXT NOT NULL,
            `summary_bn` TEXT NULL,
            `description_en` LONGTEXT NULL,
            `description_bn` LONGTEXT NULL,
            `icon_svg` TEXT NOT NULL,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_active_sort` (`is_active`, `sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_services` verified.\n";

    // 6. Process Steps Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_process_steps` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `step_number` VARCHAR(10) NOT NULL UNIQUE,
            `title_en` VARCHAR(150) NOT NULL,
            `title_bn` VARCHAR(150) NULL,
            `description_en` TEXT NOT NULL,
            `description_bn` TEXT NULL,
            `icon_svg` TEXT NOT NULL,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_process_steps` verified.\n";

    // 7. Stats Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_stats` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `stat_key` VARCHAR(50) NOT NULL UNIQUE,
            `value_number` INT NOT NULL DEFAULT 0,
            `suffix` VARCHAR(20) NOT NULL DEFAULT '+',
            `label_en` VARCHAR(100) NOT NULL,
            `label_bn` VARCHAR(100) NULL,
            `icon_svg` TEXT NOT NULL,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_stats` verified.\n";

    // 8. Testimonials Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_testimonials` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `author_name` VARCHAR(150) NOT NULL,
            `author_role_en` VARCHAR(150) NOT NULL,
            `author_role_bn` VARCHAR(150) NULL,
            `author_location_en` VARCHAR(150) NOT NULL,
            `author_location_bn` VARCHAR(150) NULL,
            `project_tag_en` VARCHAR(150) NOT NULL,
            `project_tag_bn` VARCHAR(150) NULL,
            `author_initials` VARCHAR(10) NOT NULL,
            `content_en` TEXT NOT NULL,
            `content_bn` TEXT NULL,
            `rating_score` DECIMAL(2,1) NOT NULL DEFAULT 5.0,
            `is_verified` TINYINT(1) NOT NULL DEFAULT 1,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_active_sort` (`is_active`, `sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_testimonials` verified.\n";

    // 9. FAQs Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_faqs` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `category` ENUM('general', 'process', 'pricing', 'warranty', 'turnkey') NOT NULL DEFAULT 'general',
            `question_en` TEXT NOT NULL,
            `question_bn` TEXT NULL,
            `answer_en` LONGTEXT NOT NULL,
            `answer_bn` LONGTEXT NULL,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_cat_active` (`category`, `is_active`, `sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_faqs` verified.\n";

    // 10. Project Categories Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_project_categories` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `category_type` ENUM('room_type', 'property_type') NOT NULL DEFAULT 'room_type',
            `name_en` VARCHAR(100) NOT NULL,
            `name_bn` VARCHAR(100) NULL,
            `slug` VARCHAR(100) NOT NULL UNIQUE,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_type_active` (`category_type`, `is_active`, `sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_project_categories` verified.\n";

    // 11. Projects Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_projects` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `slug` VARCHAR(120) NOT NULL UNIQUE,
            `title_en` VARCHAR(200) NOT NULL,
            `title_bn` VARCHAR(200) NULL,
            `summary_en` TEXT NOT NULL,
            `summary_bn` TEXT NULL,
            `description_en` LONGTEXT NULL,
            `description_bn` LONGTEXT NULL,
            `location_en` VARCHAR(150) NOT NULL,
            `location_bn` VARCHAR(150) NULL,
            `client_name` VARCHAR(150) NULL,
            `completion_year` VARCHAR(20) NOT NULL DEFAULT '2025',
            `area_sqft` VARCHAR(50) NOT NULL DEFAULT '2,800 sqft',
            `room_details_en` VARCHAR(100) NOT NULL DEFAULT '4 Bed, 5 Bath, Living, Dining',
            `room_details_bn` VARCHAR(100) NULL,
            `design_style_en` VARCHAR(100) NOT NULL DEFAULT 'Modern Luxury Minimalism',
            `design_style_bn` VARCHAR(100) NULL,
            `project_status` ENUM('Completed', 'In Progress', 'Concept') NOT NULL DEFAULT 'Completed',
            `cover_image` VARCHAR(255) NOT NULL,
            `features_json` JSON NULL,
            `highlights_json` JSON NULL,
            `materials_json` JSON NULL,
            `gallery_json` JSON NULL,
            `proposal_pdf` VARCHAR(255) NULL,
            `room_type_key` VARCHAR(50) NOT NULL DEFAULT 'living_room',
            `property_type_key` VARCHAR(50) NOT NULL DEFAULT 'residential',
            `show_on_home` TINYINT(1) NOT NULL DEFAULT 1,
            `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_home_active` (`show_on_home`, `is_active`, `sort_order`),
            INDEX `idx_room_key` (`room_type_key`),
            INDEX `idx_prop_key` (`property_type_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_projects` verified.\n";

    // 12. Contact & Consultation Submissions Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_contact_submissions` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `full_name` VARCHAR(150) NOT NULL,
            `phone_number` VARCHAR(50) NOT NULL,
            `email_address` VARCHAR(190) NULL,
            `service_slug` VARCHAR(100) NULL,
            `project_location` VARCHAR(150) NULL,
            `message` TEXT NOT NULL,
            `status` ENUM('new', 'contacted', 'in_progress', 'closed', 'archived') NOT NULL DEFAULT 'new',
            `admin_notes` TEXT NULL,
            `ip_address` VARCHAR(45) NULL,
            `user_agent` TEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_status_created` (`status`, `created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_contact_submissions` verified.\n";

    // 13. System Logs Table (Health & Bug Logger)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_system_logs` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `log_level` ENUM('info', 'warning', 'error', 'critical') NOT NULL DEFAULT 'info',
            `error_code` VARCHAR(50) NULL,
            `message` TEXT NOT NULL,
            `stack_trace` LONGTEXT NULL,
            `request_uri` VARCHAR(255) NULL,
            `ip_address` VARCHAR(45) NULL,
            `is_resolved` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_level_resolved` (`log_level`, `is_resolved`, `created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_system_logs` verified.\n";

    // 14. Page Views / Visitor Analytics Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_page_views` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `view_date` DATE NOT NULL,
            `page_path` VARCHAR(255) NOT NULL,
            `unique_visitors` INT UNSIGNED NOT NULL DEFAULT 1,
            `total_pageviews` INT UNSIGNED NOT NULL DEFAULT 1,
            `device_mobile_count` INT UNSIGNED NOT NULL DEFAULT 0,
            `device_desktop_count` INT UNSIGNED NOT NULL DEFAULT 0,
            `referrer_group` VARCHAR(100) NOT NULL DEFAULT 'Direct',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `uniq_date_page` (`view_date`, `page_path`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_page_views` verified.\n";

    // 15. Backup Records Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_backup_records` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `filename` VARCHAR(255) NOT NULL UNIQUE,
            `storage_path` VARCHAR(255) NOT NULL,
            `size_bytes` BIGINT UNSIGNED NOT NULL DEFAULT 0,
            `checksum_sha256` CHAR(64) NULL,
            `created_by_user` VARCHAR(60) NOT NULL DEFAULT 'Owner',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_backup_records` verified.\n";

    // 16. Custom Content Pages Table (Bilingual EN/BN)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_pages` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `slug` VARCHAR(120) NOT NULL UNIQUE,
            `title_en` VARCHAR(200) NOT NULL,
            `title_bn` VARCHAR(200) NULL,
            `content_en` LONGTEXT NULL,
            `content_bn` LONGTEXT NULL,
            `meta_desc_en` VARCHAR(300) NULL,
            `meta_desc_bn` VARCHAR(300) NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_slug_active` (`slug`, `is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_pages` verified.\n";

    // 18. Audit Logs Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `lilyweb_audit_logs` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(60) NOT NULL,
            `action` VARCHAR(100) NOT NULL,
            `entity_type` VARCHAR(100) NOT NULL,
            `entity_id` VARCHAR(100) NULL,
            `details_json` JSON NULL,
            `ip_address` VARCHAR(45) NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_action_created` (`action`, `created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[✓] Table `lilyweb_audit_logs` verified.\n";

    echo "\n=== ALL MIGRATIONS COMPLETED SUCCESSFULLY ===\n";

} catch (Exception $e) {
    echo "\n[ERROR] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
