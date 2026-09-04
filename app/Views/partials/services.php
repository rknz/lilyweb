<?php

/**
 * Services section matching architectural luxury design system (2x3 Grid with Watermark Counters, Dual-Tone Pods & Glow Accents).
 * Fully localized for English & Bengali.
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();

$services = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM `lilyweb_services` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    $dbServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dbServices as $row) {
        $title = $isBn ? ($row['title_bn'] ?: $row['title_en']) : $row['title_en'];
        $tag = $isBn ? ($row['tag_badge_bn'] ?: $row['tag_badge_en']) : $row['tag_badge_en'];
        $desc = $isBn ? ($row['summary_bn'] ?: $row['summary_en']) : $row['summary_en'];

        $services[] = [
            'tag' => $tag,
            'title' => $title,
            'desc' => $desc,
            'slug' => $row['slug'],
            'icon' => $row['icon_svg'],
        ];
    }
} catch (\Throwable $e) {
    // Fail-safe
}

if (empty($services)) {
    $services = [
        [
            'tag' => $isBn ? 'আর্কিটেকচারাল' : 'Bespoke 3D',
            'title' => $isBn ? 'ইন্টেরিয়র ডিজাইন' : 'Interior Design',
            'desc' => $isBn ? 'আবাসিক ও বাণিজ্যিক স্পেসের জন্য আধুনিক ও সময়োপযোগী আর্কিটেকচারাল স্পেস প্ল্যানিং ও থ্রিডি ডিজাইন।' : 'Comprehensive conceptual layout, spatial planning and aesthetic styling for premium modern living.',
            'slug' => 'interior-design',
            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
        ],
    ];
}
?>
<section class="section services" id="services" aria-labelledby="services-title">
    <div class="site-wrapper">
        <div class="section-header-split" data-reveal>
            <div class="header-titles">
                <span class="section-kicker"><?= Lang::get('services_kicker') ?></span>
                <h2 id="services-title" class="section-h2"><?= Lang::get('services_h2') ?></h2>
            </div>
            <a class="section-top-link" href="#contact">
                <span><?= Lang::get('view_all_services') ?></span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="services-grid">
            <?php foreach ($services as $idx => $service): ?>
                <article class="service-card" data-reveal>
                    <div class="service-watermark" aria-hidden="true">0<?= $idx + 1 ?></div>
                    
                    <div class="service-top-row">
                        <div class="service-icon-pod" aria-hidden="true">
                            <?= Security::sanitizeSvg($service['icon']) ?>
                        </div>
                        <span class="service-badge-pill">
                            <?= Security::e($service['tag']) ?>
                        </span>
                    </div>

                    <div class="service-body">
                        <h3 class="service-title"><?= Security::e($service['title']) ?></h3>
                        <p class="service-desc"><?= Security::e($service['desc']) ?></p>
                    </div>

                    <div class="service-footer-cta">
                        <a href="#contact" class="service-cta-link">
                            <span><?= $isBn ? 'পরামর্শ ও বিস্তারিত বুকিং' : 'Book Free Consultation' ?></span>
                            <span class="service-cta-arrow" aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
