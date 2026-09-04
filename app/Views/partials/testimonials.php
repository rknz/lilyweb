<?php

/**
 * Modern Luxury Architectural Testimonials Carousel / Slider.
 * Features:
 * - Responsive Infinite / Stepped Carousel (Desktop Multi-Card & Mobile Single-Card Smooth Sliding)
 * - Navigation Arrows (Previous / Next) with Glassmorphism Hover Effects
 * - Pagination Indicators with Active Fill Animation
 * - Touch Swipe (Mobile) & Auto-Slide with Hover Pause
 * - Decorative Watermark Quotes, 5-Star Ratings & Verified Badges
 * - Fully localized for English and Bengali.
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();

$testimonials = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM `lilyweb_testimonials` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    $dbReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dbReviews as $row) {
        $tag = $isBn ? ($row['project_tag_bn'] ?: $row['project_tag_en']) : $row['project_tag_en'];
        $quote = $isBn ? ($row['content_bn'] ?: $row['content_en']) : $row['content_en'];
        $author = $row['author_name'];
        $role = $isBn ? ($row['author_role_bn'] ?: $row['author_role_en']) : $row['author_role_en'];
        $location = $isBn ? ($row['author_location_bn'] ?: $row['author_location_en']) : $row['author_location_en'];
        $badge = $isBn ? 'ভেরিফায়েড ক্লায়েন্ট' : 'Verified Client';

        $testimonials[] = [
            'tag' => $tag,
            'quote' => $quote,
            'author' => $author,
            'role' => $role,
            'location' => $location,
            'badge' => $badge,
            'initials' => $row['author_initials'] ?: 'LI',
        ];
    }
} catch (\Throwable $e) {
    // Fail-safe
}

if (empty($testimonials)) {
    $testimonials = [
        [
            'tag' => $isBn ? 'গুলশান ২ ডুপ্লেক্স • ৩২০০ বর্গফুট' : 'Gulshan 2 Duplex • 3,200 sqft',
            'quote' => $isBn 
                ? 'লিলি ইন্টেরিয়র্স গুলশান ২-এ আমাদের ৩২০০ বর্গফুটের অ্যাপার্টমেন্টের ডিজাইন এমনভাবে করেছে যা আমাদের প্রত্যাশাকেও ছাড়িয়ে গেছে।'
                : 'Lily Interiors transformed our 3,200 sqft apartment in Gulshan 2 beyond our expectations.',
            'author' => $isBn ? 'ব্যারিস্টার রফিকুল ইসলাম' : 'Barrister Rafiqul Islam',
            'role' => $isBn ? 'মালিক, লাক্সারি ডুপ্লেক্স' : 'Homeowner, Luxury Duplex',
            'location' => $isBn ? 'গুলশান ২, ঢাকা' : 'Gulshan 2, Dhaka',
            'badge' => $isBn ? 'ভেরিফায়েড ক্লায়েন্ট' : 'Verified Client',
            'initials' => 'RI',
        ],
    ];
}
?>
<section class="section testimonials" id="testimonials" aria-labelledby="testimonials-title">
    <div class="site-wrapper">
        <!-- Section Header with Trust Rating Pill -->
        <div class="section-header-center" data-reveal>
            <div class="testimonials-trust-pill">
                <span class="trust-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                <span class="trust-text"><?= $isBn ? '৪.৯/৫ ক্লায়েন্ট রেটিং • ১৫০+ বাস্তবায়িত প্রজেক্ট' : '4.9/5 Rating • 150+ Happy Clients' ?></span>
            </div>
            <span class="section-kicker"><?= Lang::get('testimonials_kicker') ?></span>
            <h2 id="testimonials-title" class="section-h2"><?= Lang::get('testimonials_h2') ?></h2>
            <p class="section-sub-lead">
                <?= Lang::get('testimonials_sub') ?>
            </p>
        </div>

        <!-- Modern Testimonials Slider / Carousel -->
        <div class="testimonials-carousel" data-testimonials-carousel data-reveal>
            <!-- Navigation Arrow Previous -->
            <button type="button" class="t-carousel-arrow t-arrow-prev" data-t-prev aria-label="Previous Testimonials">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            </button>

            <!-- Carousel Viewport & Sliding Track -->
            <div class="testimonials-viewport" data-t-viewport>
                <div class="testimonials-track" data-t-track>
                    <?php foreach ($testimonials as $index => $item): ?>
                        <div class="testimonial-slide" data-t-slide="<?= $index ?>">
                            <article class="testimonial-card">
                                <!-- Watermark Quote Icon in Background -->
                                <div class="testimonial-watermark" aria-hidden="true">&ldquo;</div>

                                <!-- Top Row: Project Scope Tag & Verified Pill -->
                                <div class="testimonial-top-row">
                                    <span class="testimonial-project-tag"><?= Security::e($item['tag']) ?></span>
                                    <span class="verified-pill">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <?= Security::e($item['badge']) ?>
                                    </span>
                                </div>

                                <!-- 5-Star Rating Row with 5.0 Score -->
                                <div class="testimonial-rating-row">
                                    <div class="star-rating" aria-label="5 out of 5 stars">
                                        <span class="star-icon">&#9733;</span>
                                        <span class="star-icon">&#9733;</span>
                                        <span class="star-icon">&#9733;</span>
                                        <span class="star-icon">&#9733;</span>
                                        <span class="star-icon">&#9733;</span>
                                    </div>
                                    <span class="rating-score">5.0</span>
                                </div>

                                <!-- Review Quote Body -->
                                <blockquote class="testimonial-quote">
                                    &ldquo;<?= Security::e($item['quote']) ?>&rdquo;
                                </blockquote>

                                <!-- Author Details Row -->
                                <div class="testimonial-author-row">
                                    <div class="author-avatar-circle" aria-hidden="true">
                                        <?= Security::e($item['initials']) ?>
                                    </div>
                                    <div class="author-info">
                                        <strong class="author-name"><?= Security::e($item['author']) ?></strong>
                                        <span class="author-role"><?= Security::e($item['role']) ?></span>
                                        <span class="author-location">
                                            <span class="loc-pin">&#128205;</span> <?= Security::e($item['location']) ?>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Navigation Arrow Next -->
            <button type="button" class="t-carousel-arrow t-arrow-next" data-t-next aria-label="Next Testimonials">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        </div>

        <!-- Carousel Pagination Dots / Indicators -->
        <div class="testimonials-pagination" data-t-pagination>
            <!-- Generated dynamically or rendered -->
            <?php foreach ($testimonials as $index => $item): ?>
                <button type="button" class="t-dot <?= $index === 0 ? 'is-active' : '' ?>" data-t-dot="<?= $index ?>" aria-label="Go to slide <?= $index + 1 ?>">
                    <span class="t-dot-inner"></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
