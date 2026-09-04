<?php

/**
 * About Snapshot section matching FINAL_DESKTOP_UI.png (Overlapping Dual Image Frame + Badge).
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();
$bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
$toBnDigits = function ($num) use ($bengaliDigits) {
    return preg_replace_callback('/\d/', fn($m) => $bengaliDigits[(int)$m[0]], (string)$num);
};

$aboutSettings = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings` WHERE `setting_key` LIKE 'about_%'");
    $aboutSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (\Throwable $e) {
    // Fail-safe
}

$kicker = $isBn 
    ? (!empty($aboutSettings['about_kicker_bn']) ? $aboutSettings['about_kicker_bn'] : ($aboutSettings['about_kicker_en'] ?? Lang::get('about_kicker')))
    : ($aboutSettings['about_kicker_en'] ?? Lang::get('about_kicker'));

$heading = $isBn
    ? (!empty($aboutSettings['about_heading_bn']) ? $aboutSettings['about_heading_bn'] : ($aboutSettings['about_heading_en'] ?? Lang::get('about_h2')))
    : ($aboutSettings['about_heading_en'] ?? Lang::get('about_h2'));

$lead = $isBn
    ? (!empty($aboutSettings['about_lead_bn']) ? $aboutSettings['about_lead_bn'] : ($aboutSettings['about_lead_en'] ?? Lang::get('about_lead')))
    : ($aboutSettings['about_lead_en'] ?? Lang::get('about_lead'));

$linkText = $isBn
    ? (!empty($aboutSettings['about_link_text_bn']) ? $aboutSettings['about_link_text_bn'] : ($aboutSettings['about_link_text_en'] ?? Lang::get('more_about_us')))
    : ($aboutSettings['about_link_text_en'] ?? Lang::get('more_about_us'));

$linkUrl = !empty($aboutSettings['about_link_url']) ? $aboutSettings['about_link_url'] : '#services';
$imgMain = !empty($aboutSettings['about_img_main']) ? $aboutSettings['about_img_main'] : '/assets/img/about-1.jpg';
$imgSecondary = !empty($aboutSettings['about_img_secondary']) ? $aboutSettings['about_img_secondary'] : '/assets/img/about-2.jpg';

$expYearsRaw = !empty($aboutSettings['about_exp_years']) ? $aboutSettings['about_exp_years'] : '8+';
$expYears = $isBn ? $toBnDigits($expYearsRaw) : $expYearsRaw;

$expText = $isBn
    ? (!empty($aboutSettings['about_exp_text_bn']) ? $aboutSettings['about_exp_text_bn'] : ($aboutSettings['about_exp_text_en'] ?? Lang::get('years_experience')))
    : ($aboutSettings['about_exp_text_en'] ?? Lang::get('years_experience'));
?>
<section class="section about" id="about" aria-labelledby="about-title">
    <div class="site-wrapper about-layout">
        <!-- Left Copy Column -->
        <div class="about-copy" data-reveal>
            <span class="section-kicker"><?= Security::e($kicker) ?></span>
            <h2 id="about-title" class="section-h2"><?= Security::e($heading) ?></h2>
            <p class="about-lead"><?= Security::e($lead) ?></p>
            <a class="about-link" href="<?= Security::e($linkUrl) ?>"><?= Security::e($linkText) ?></a>
        </div>

        <!-- Right Overlapping Dual Image Composition -->
        <div class="about-media" data-reveal>
            <div class="about-dot-grid" aria-hidden="true"></div>
            
            <div class="about-img-main">
                <picture>
                    <source srcset="<?= Security::e(preg_replace('/\.(jpg|png)$/i', '.webp', $imgMain)) ?>" type="image/webp">
                    <img src="<?= Security::e($imgMain) ?>"
                         alt="Lily Interiors architectural studio work"
                         width="480" height="540"
                         loading="lazy" decoding="async">
                </picture>
            </div>
            
            <div class="about-img-secondary">
                <picture>
                    <source srcset="<?= Security::e(preg_replace('/\.(jpg|png)$/i', '.webp', $imgSecondary)) ?>" type="image/webp">
                    <img src="<?= Security::e($imgSecondary) ?>"
                         alt="Craftsmanship detail by Lily Interiors"
                         width="340" height="380"
                         loading="lazy" decoding="async">
                </picture>
            </div>
            
            <div class="about-experience-badge" data-reveal>
                <span class="exp-num"><?= Security::e($expYears) ?></span>
                <span class="exp-text"><?= Security::e($expText) ?></span>
            </div>
        </div>
    </div>
</section>