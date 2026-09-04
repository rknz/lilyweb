<?php

/**
 * Main application layout.
 *
 * Visual Source of Truth: design-reference/FINAL_DESKTOP_UI.png
 * Features: Zero-flicker Light/Dark theme initialization, Bilingual support (EN/BN), Full Technical SEO foundation.
 */
use Lilyweb\Core\View;
use Lilyweb\Core\Config;
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;
use Lilyweb\Core\Request;
use Lilyweb\Core\Security;

$siteName = (string) Config::get('app.name', 'Lily Interiors');
$currentLocale = Lang::getLocale();
$isBn = Lang::isBn();
$currentPath = Request::path();

$seoSettings = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings`");
    $seoSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (\Throwable $e) {
    // Fail-safe
}

// Canonical URL & Host determination
$domain = 'https://lilyinteriorsbd.com';
if (!empty($_SERVER['HTTP_HOST']) && Security::isValidHost($_SERVER['HTTP_HOST'])) {
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $domain = $proto . '://' . $_SERVER['HTTP_HOST'];
}
$canonicalUrl = $domain . ($currentPath === '/' ? '' : $currentPath);

// Meta Titles & Descriptions (Google Search Intent Optimized)
$defaultTitle = $isBn 
    ? ($seoSettings['seo_meta_title_bn'] ?? 'লিলি ইন্টেরিয়র্স — সেরা আর্কিটেকচারাল ইন্টেরিয়র ডিজাইন ও ডেকোরেশন ঢাকা')
    : ($seoSettings['seo_meta_title_en'] ?? 'Lily Interiors — Best Architectural Interior Design Firm in Dhaka, Bangladesh');

$defaultDesc = $isBn 
    ? ($seoSettings['seo_meta_desc_bn'] ?? 'ঢাকায় গুলশান, বনানী, ধানমন্ডি ও উত্তরা জুড়ে আন্তর্জাতিক মানের রেসিডেন্সিয়াল, ডুপ্লেক্স ও কর্পোরেট ইন্টেরিয়র ডিজাইন ও টার্নকি সল্যুশন।')
    : ($seoSettings['seo_meta_desc_en'] ?? 'Premier architectural interior design and turnkey renovation firm in Dhaka, Bangladesh. Specializing in luxury residential apartments, corporate headquarters, modular gourmet kitchens and duplexes.');

$pageTitle = $title ?? $defaultTitle;
$metaDescription = $meta_desc ?? $defaultDesc;
$pageOgImage = $og_image ?? ($seoSettings['og_image_url'] ?? $domain . '/assets/img/hero-living-room.webp');
if (!str_starts_with($pageOgImage, 'http')) {
    $pageOgImage = $domain . $pageOgImage;
}

$googleVerification = $seoSettings['google_site_verification'] ?? '';
$ga4Id = $seoSettings['google_analytics_id'] ?? '';
$gtmId = $seoSettings['google_tag_manager_id'] ?? '';
$customHead = $seoSettings['custom_head_scripts'] ?? '';
$customBody = $seoSettings['custom_body_scripts'] ?? '';

// Business Info for Rich Snippets
$businessAddress = $seoSettings['address_en'] ?? '36 Bir Uttam C.R Dutta Road, Hatirpool, Dhaka-1205, Bangladesh';
$businessPhone = $seoSettings['phone_primary'] ?? '+88 01734182694';
$businessEmail = $seoSettings['email_primary'] ?? 'lilyinteriorsbd@gmail.com';
$fbUrl = $seoSettings['social_facebook'] ?? 'https://www.facebook.com/LilyInteriorsbd/';
$igUrl = $seoSettings['social_instagram'] ?? 'https://www.instagram.com/lilyinteriors/';
$ytUrl = $seoSettings['social_youtube'] ?? 'https://www.youtube.com/channel/UCkLkpvteAlhSFUkgzQy8p_g/videos';
$linkedInUrl = $seoSettings['social_linkedin'] ?? '';
$pinterestUrl = $seoSettings['social_pinterest'] ?? 'https://www.pinterest.com/';
?>
<!DOCTYPE html>
<html lang="<?= $currentLocale ?>" class="<?= $isBn ? 'is-bengali' : '' ?>" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= View::e($pageTitle) ?></title>
    
    <!-- Zero-Flicker Theme Pre-loader -->
    <script>
        (function() {
            try {
                var storedTheme = localStorage.getItem('lily_theme');
                if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            } catch(e) {}
        })();
    </script>

    <!-- Essential SEO Meta Tags -->
    <meta name="description" content="<?= View::e($metaDescription) ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="theme-color" content="#C8102E">
    <link rel="canonical" href="<?= View::e($canonicalUrl) ?>">

    <!-- Complete Favicon & Mobile Touch Icons (All Platforms) -->
    <link rel="icon" type="image/x-icon" href="<?= asset('/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= asset('/site.webmanifest') ?>">

    <!-- Multilingual Hreflang Alternates -->
    <link rel="alternate" hreflang="en" href="<?= View::e($canonicalUrl) ?>?lang=en">
    <link rel="alternate" hreflang="bn" href="<?= View::e($canonicalUrl) ?>?lang=bn">
    <link rel="alternate" hreflang="x-default" href="<?= View::e($canonicalUrl) ?>">

    <!-- Google Search Console Verification -->
    <?php if (!empty($googleVerification)): ?>
        <meta name="google-site-verification" content="<?= View::e($googleVerification) ?>">
    <?php endif; ?>

    <!-- Open Graph (Facebook / LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= View::e($pageTitle) ?>">
    <meta property="og:description" content="<?= View::e($metaDescription) ?>">
    <meta property="og:url" content="<?= View::e($canonicalUrl) ?>">
    <meta property="og:site_name" content="Lily Interiors BD">
    <meta property="og:image" content="<?= View::e($pageOgImage) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="<?= $isBn ? 'bn_BD' : 'en_US' ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= View::e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= View::e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= View::e($pageOgImage) ?>">

    <!-- Comprehensive LocalBusiness & HomeAndConstructionBusiness JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": ["HomeAndConstructionBusiness", "LocalBusiness"],
      "@id": "https://lilyinteriorsbd.com/#business",
      "name": "Lily Interiors",
      "legalName": "Lily Interiors BD",
      "url": "https://lilyinteriorsbd.com",
      "logo": "https://lilyinteriorsbd.com/assets/img/admin-logo.png",
      "image": "<?= View::e($pageOgImage) ?>",
      "description": "<?= View::e($defaultDesc) ?>",
      "telephone": "<?= View::e($businessPhone) ?>",
      "email": "<?= View::e($businessEmail) ?>",
      "priceRange": "$$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "36 Bir Uttam C.R Dutta Road, Hatirpool",
        "addressLocality": "Dhaka",
        "postalCode": "1205",
        "addressCountry": "BD"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "23.7431",
        "longitude": "90.3924"
      },
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"],
          "opens": "09:00",
          "closes": "19:00"
        }
      ],
      "sameAs": [
        "<?= View::e($fbUrl) ?>",
        "<?= View::e($igUrl) ?>",
        "<?= View::e($ytUrl) ?>",
        "<?= View::e($pinterestUrl) ?>"
        <?php if ($linkedInUrl !== ''): ?>,
        "<?= View::e($linkedInUrl) ?>"
        <?php endif; ?>
      ],
      "areaServed": [
        { "@type": "City", "name": "Dhaka" },
        { "@type": "AdministrativeArea", "name": "Gulshan" },
        { "@type": "AdministrativeArea", "name": "Banani" },
        { "@type": "AdministrativeArea", "name": "Dhanmondi" },
        { "@type": "AdministrativeArea", "name": "Uttara" },
        { "@type": "AdministrativeArea", "name": "Bashundhara R/A" }
      ]
    }
    </script>

    <!-- WebSite Search Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Lily Interiors",
      "url": "https://lilyinteriorsbd.com",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://lilyinteriorsbd.com/projects?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <?php if (!empty($ga4Id)): ?>
        <!-- Google tag (gtag.js) GA4 Integration -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= View::e($ga4Id) ?>"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '<?= View::e($ga4Id) ?>');
        </script>
    <?php endif; ?>

    <?php if (!empty($customHead)): ?>
        <?= Security::sanitizeAdminHtml($customHead) ?>
    <?php endif; ?>

    <!-- Preconnect & Google Fonts with display=swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Critical Hero Asset Preloads (Immediate Cache for Zero-Gap Slider Cycle) -->
    <link rel="preload" as="image" href="<?= asset('/assets/img/hero-living-room.webp') ?>" type="image/webp" fetchpriority="high">
    <link rel="preload" as="image" href="<?= asset('/assets/img/hero-slide-2.webp') ?>" type="image/webp">
    <link rel="preload" as="image" href="<?= asset('/assets/img/hero-slide-3.webp') ?>" type="image/webp">
    <link rel="preload" as="image" href="<?= asset('/assets/img/hero-slide-4.webp') ?>" type="image/webp">
    <link rel="preload" as="image" href="<?= asset('/assets/img/hero-slide-5.webp') ?>" type="image/webp">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/css/app.css') ?: '2.3.0' ?>">
</head>
<body>
    <!-- Luxury Modern Custom Cursor (Desktop Only) -->
    <div class="custom-cursor" id="custom-cursor" aria-hidden="true">
        <div class="cursor-dot"></div>
        <div class="cursor-ring"></div>
    </div>

    <!-- Top Strip & Sticky Main Header -->
    <?= View::renderPartial('partials.header', ['active' => $active ?? 'home']) ?>

    <!-- Main Content Area -->
    <main id="main-content" tabindex="-1">
        <?= $content ?? '' ?>
    </main>

    <!-- Distinctive Architectural Dark Footer -->
    <?= \Lilyweb\Core\View::renderPartial('partials.footer') ?>

    <!-- Floating Speed Actions (WhatsApp, Call, Back-to-Top) -->
    <?= View::renderPartial('partials.floating-actions') ?>

    <!-- Performance & Browser Cookie Alert Banner -->
    <?= View::renderPartial('partials.cookie-banner') ?>

    <!-- Client-side Interactions & Theme / Language Controllers -->
    <script src="<?= asset('/assets/js/app.js') ?>?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/js/app.js') ?: '2.3.0' ?>"></script>
    
    <?php if (!empty($customBody)): ?>
        <?= Security::sanitizeAdminHtml($customBody) ?>
    <?php endif; ?>
</body>
</html>
