<?php

/**
 * Full Projects Directory Page matching Lily Interiors design system.
 * Filtered by Property/Project Types (Residential, Commercial, Turnkey Duplex, Renovation).
 * Followed by Stats Strip & About Us Snapshot as requested.
 * Fully localized for English & Bengali.
 */
use Lilyweb\Core\View;
use Lilyweb\Core\Lang;

$projects = $projects ?? [];
$categories = $categories ?? [];
$isBn = Lang::isBn();
?>
<section class="section projects-page-header">
    <div class="site-wrapper">
        <div class="section-header-center">
            <span class="section-kicker"><?= $isBn ? 'আমাদের সম্পূর্ণ পোর্টফোলিও' : 'OUR COMPLETE PORTFOLIO' ?></span>
            <h1 class="section-h2"><?= $isBn ? 'ঢাকা জুড়ে স্পেসের অনন্য রূপান্তর' : 'Transforming Spaces Across Bangladesh' ?></h1>
            <p class="hero-sub" style="margin: 0.8rem auto 0; max-width: 58ch;">
                <?= $isBn 
                    ? 'আবাসিক, বাণিজ্যিক, মডিউলার কিচেন ও বিলাসবহুল টার্নকি ইন্টেরিয়র আর্কিটেকচার প্রজেক্টের সম্পূর্ণ ডিরেক্টরি।' 
                    : 'Explore our curated collection of bespoke residential, commercial, modular kitchen, and luxury turnkey interior architecture projects in Dhaka.' ?>
            </p>
        </div>

        <!-- Property / Project Type Filter Pills (Residential, Commercial, Turnkey, Renovation) -->
        <div class="portfolio-filter-bar" style="justify-content: center; margin-top: 2rem;">
            <?php $first = true; foreach ($categories as $key => $label): ?>
                <button type="button"
                        class="filter-pill <?= $first ? 'is-active' : '' ?>"
                        data-filter="<?= View::e($key) ?>">
                    <?= View::e($label) ?>
                </button>
            <?php $first = false; endforeach; ?>
        </div>
    </div>
</section>

<section class="section projects-grid-section" style="padding-top: 0; padding-bottom: 2rem;">
    <div class="site-wrapper">
        <div class="portfolio-grid" id="projects-directory-grid">
            <?php foreach ($projects as $project): ?>
                <article class="project-card" data-category="<?= View::e($project['project_type_key']) ?>">
                    <a href="/projects/<?= View::e($project['slug']) ?>" class="project-card-link" aria-label="View details for <?= View::e($project['title']) ?>">
                        <div class="project-thumb">
                            <img src="<?= View::e($project['image']) ?>"
                                 alt="<?= View::e($project['title']) ?> - <?= View::e($project['location']) ?>"
                                 loading="lazy">
                            <span class="project-cat-badge"><?= View::e($project['project_type']) ?></span>
                        </div>
                        <div class="project-body">
                            <h2 class="project-title" style="font-size: 1.15rem;"><?= View::e($project['title']) ?></h2>
                            <p class="project-location">&#128205; <?= View::e($project['location']) ?> &bull; <?= View::e($project['area']) ?></p>
                            <p class="project-summary-snippet" style="font-size: 0.86rem; color: var(--charcoal-muted); margin-top: 0.4rem; line-height: 1.5;"><?= View::e($project['summary']) ?></p>
                            <span class="project-view-cta" style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.88rem; font-weight: 700; color: var(--crimson); margin-top: 0.8rem;">
                                <?= $isBn ? 'প্রজেক্টের বিস্তারিত দেখুন &rarr;' : 'View Project Details &rarr;' ?>
                            </span>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Company Trust Stats Strip -->
<?= View::renderPartial('partials.stats') ?>

<!-- About Us Snapshot -->
<?= View::renderPartial('partials.about') ?>
