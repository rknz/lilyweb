<?php

/**
 * Hero Carousel & Slider component matching FINAL_DESKTOP_UI.png.
 * Fully localized for English & Bengali with instantaneous loop transitions.
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();
$bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
$toBnDigits = function ($num) use ($bengaliDigits) {
    return preg_replace_callback('/\d/', fn($m) => $bengaliDigits[(int)$m[0]], (string)$num);
};

$heroSlides = [];

try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM `lilyweb_hero_slides` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    $dbSlides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dbSlides as $row) {
        $stepStr = (string)$row['step_number'];
        $stepDisplay = $isBn ? $toBnDigits($stepStr) : $stepStr;

        $kicker = $isBn ? ($row['kicker_bn'] ?: $row['kicker_en']) : $row['kicker_en'];
        $titlePrefix = $isBn ? ($row['title_prefix_bn'] ?: $row['title_prefix_en']) : $row['title_prefix_en'];
        $titleHighlight = $isBn ? ($row['title_highlight_bn'] ?: $row['title_highlight_en']) : $row['title_highlight_en'];
        $titleSuffix = $isBn ? ($row['title_suffix_bn'] ?: $row['title_suffix_en']) : $row['title_suffix_en'];
        $sub = $isBn ? ($row['subtitle_bn'] ?: $row['subtitle_en']) : $row['subtitle_en'];
        $badgeRoom = $isBn ? ($row['badge_room_bn'] ?: $row['badge_room_en']) : $row['badge_room_en'];
        $badgeLocation = $isBn ? ($row['badge_location_bn'] ?: $row['badge_location_en']) : $row['badge_location_en'];
        $ctaText = $isBn ? ($row['cta_text_bn'] ?: $row['cta_text_en']) : $row['cta_text_en'];

        $heroSlides[] = [
            'step' => $stepDisplay,
            'kicker' => $kicker,
            'title_prefix' => $titlePrefix,
            'title_highlight' => $titleHighlight,
            'title_suffix' => $titleSuffix,
            'sub' => $sub,
            'image' => $row['image_url'],
            'badge_room' => $badgeRoom,
            'badge_location' => $badgeLocation,
            'cta_text' => $ctaText,
            'cta_url' => $row['cta_url'] ?: '#portfolio',
        ];
    }
} catch (\Throwable $e) {
    // Fail-safe static fallback below
}

if (empty($heroSlides)) {
    $heroSlides = [
        [
            'step' => $isBn ? '০১' : '01',
            'kicker' => $isBn ? 'ডিজাইন • ক্রিয়েট • ইন্সপায়ার' : 'DESIGN • CREATE • INSPIRE',
            'title_prefix' => $isBn ? 'আমরা এমন স্পেস ডিজাইন করি যা' : 'We Design Spaces That',
            'title_highlight' => $isBn ? 'অনুপ্রাণিত' : 'Inspire',
            'title_suffix' => $isBn ? 'করে জীবনকে' : 'Life',
            'sub' => $isBn ? 'আপনার জীবনযাত্রার সাথে মানানসই কার্যকারিতা, সৃজনশীলতা এবং আভিজাত্যের সমন্বয়ে গঠিত অনন্য ইন্টেরিয়র ডিজাইন সল্যুশন।' : 'Timeless interior design solutions combining functionality, creativity and elegance tailored to your lifestyle.',
            'image' => '/assets/img/hero-living-room.webp',
            'badge_room' => $isBn ? 'মডার্ন লিভিং রুম' : 'Modern Living Room',
            'badge_location' => $isBn ? 'মিরপুর, ঢাকা' : 'Mirpur, Dhaka',
            'cta_text' => Lang::get('explore_projects_btn'),
            'cta_url' => '#portfolio',
        ],
    ];
}
?>
<section class="hero" id="hero" aria-labelledby="hero-title" data-hero-carousel>
    <div class="site-wrapper hero-layout">
        <!-- Left Slider Counter / Interactive Stepper -->
        <div class="hero-stepper" aria-label="Slide Selector">
            <?php foreach ($heroSlides as $idx => $slide): ?>
                <button type="button" 
                        class="stepper-btn <?= $idx === 0 ? 'is-active' : '' ?>" 
                        data-slide-index="<?= $idx ?>"
                        aria-label="Go to slide <?= Security::e($slide['step']) ?>">
                    <span class="step-num"><?= Security::e($slide['step']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Center-Left Copy Slides Container -->
        <div class="hero-copy-container">
            <?php foreach ($heroSlides as $idx => $slide): ?>
                <div class="hero-slide-copy <?= $idx === 0 ? 'is-active' : '' ?>" data-slide-copy="<?= $idx ?>">
                    <p class="hero-kicker"><?= Security::e($slide['kicker']) ?></p>
                    <?php if ($idx === 0): ?>
                        <h1 class="hero-title" id="hero-title">
                            <?= Security::e($slide['title_prefix']) ?> 
                            <span class="hero-italic"><?= Security::e($slide['title_highlight']) ?></span> 
                            <?= Security::e($slide['title_suffix']) ?>
                        </h1>
                    <?php else: ?>
                        <div class="hero-title" role="heading" aria-level="2">
                            <?= Security::e($slide['title_prefix']) ?> 
                            <span class="hero-italic"><?= Security::e($slide['title_highlight']) ?></span> 
                            <?= Security::e($slide['title_suffix']) ?>
                        </div>
                    <?php endif; ?>
                    <p class="hero-sub"><?= Security::e($slide['sub']) ?></p>
                    
                    <div class="hero-actions">
                        <a class="btn btn-pill-crimson" href="<?= Security::e($slide['cta_url']) ?>"><?= Security::e($slide['cta_text']) ?></a>
                        <a class="btn btn-showreel" href="#contact">
                            <span class="play-icon">&#9658;</span>
                            <span><?= Lang::get('watch_showreel_btn') ?></span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Right Visual Images & Floating Badges Container -->
        <div class="hero-visual">
            <div class="hero-dot-grid" aria-hidden="true"></div>
            
            <div class="hero-image-wrapper">
                <?php foreach ($heroSlides as $idx => $slide): ?>
                    <div class="hero-slide-visual <?= $idx === 0 ? 'is-active' : '' ?>" data-slide-visual="<?= $idx ?>">
                        <picture>
                            <source srcset="<?= Security::e(preg_replace('/\.(jpg|png)$/i', '.webp', $slide['image'])) ?>" type="image/webp">
                            <img src="<?= Security::e($slide['image']) ?>"
                                 alt="<?= Security::e($slide['badge_room']) ?> by Lily Interiors"
                                 width="640" height="420" class="hero-main-img"
                                 loading="eager" decoding="async" <?= $idx === 0 ? 'fetchpriority="high"' : '' ?>>
                        </picture>
                        
                        <!-- Floating Location Glass Badge -->
                        <div class="hero-floating-badge">
                            <span class="badge-pin">&#128205;</span>
                            <div class="badge-text">
                                <strong><?= Security::e($slide['badge_room']) ?></strong>
                                <small><?= Security::e($slide['badge_location']) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>