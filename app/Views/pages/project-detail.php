<?php

/**
 * Project Details Page matching EXACT structure from design-reference/project details pages.png
 * Styled in the Lily Interiors brand design system with Full English & Bengali Localization.
 */
use Lilyweb\Core\View;
use Lilyweb\Core\Lang;

$project = $project ?? [];
$related = $related ?? [];
$gallery = $project['gallery'] ?? [$project['image']];
$isBn = Lang::isBn();
?>
<div class="project-details-page">
    <div class="site-wrapper">
        <!-- 1. Breadcrumb Bar -->
        <nav class="project-breadcrumb" aria-label="Breadcrumb">
            <a href="/"><?= Lang::get('nav_home') ?></a>
            <span class="bc-sep">&rsaquo;</span>
            <a href="/projects"><?= Lang::get('nav_projects') ?></a>
            <span class="bc-sep">&rsaquo;</span>
            <span class="bc-current"><?= View::e($project['title']) ?></span>
        </nav>

        <!-- 2. Top Hero Showcase: Left Gallery Slider + Right Metadata Specs -->
        <section class="pd-showcase-grid">
            <!-- Left Column: Main Image Slider + 8 Thumbnails -->
            <div class="pd-gallery-block">
                <div class="pd-main-slider" id="pd-slider">
                    <span class="pd-badge-tag"><?= View::e($project['status']) ?> <?= $isBn ? 'প্রজেক্ট' : 'Project' ?></span>
                    
                    <button type="button" class="pd-nav-arrow pd-prev" id="pd-prev-btn" aria-label="Previous image">&#10094;</button>
                    <button type="button" class="pd-nav-arrow pd-next" id="pd-next-btn" aria-label="Next image">&#10095;</button>
                    
                    <div class="pd-main-img-wrap" id="pd-main-img-trigger" title="<?= $isBn ? 'বড় করে দেখতে ক্লিক করুন' : 'Click to enlarge preview' ?>" style="cursor: zoom-in; position: relative;">
                        <img id="pd-main-image"
                             src="<?= View::e($gallery[0] ?? $project['image']) ?>"
                             alt="<?= View::e($project['title']) ?>"
                             loading="eager">
                        <div class="pd-zoom-hint" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                            <span><?= $isBn ? 'বড় করুন' : 'Click to Enlarge' ?></span>
                        </div>
                    </div>
                </div>

                <!-- 8 Thumbnail Grid Row -->
                <div class="pd-thumbs-row" id="pd-thumbs">
                    <?php foreach ($gallery as $tIdx => $thumb): ?>
                        <button type="button"
                                class="pd-thumb-item <?= $tIdx === 0 ? 'is-active' : '' ?>"
                                data-index="<?= $tIdx ?>"
                                data-src="<?= View::e($thumb) ?>"
                                aria-label="View photo <?= $tIdx + 1 ?>">
                            <img src="<?= View::e($thumb) ?>" alt="" loading="lazy">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column: Project Title, Location, Specs & Dual CTA -->
            <div class="pd-info-block">
                <span class="pd-kicker"><?= View::e($project['project_type']) ?></span>
                <h1 class="pd-title"><?= View::e($project['title']) ?></h1>
                
                <div class="pd-location-tag">
                    <span class="pin-icon">&#128205;</span>
                    <span><?= View::e($project['location']) ?></span>
                </div>

                <p class="pd-summary-lead"><?= View::e($project['summary']) ?></p>

                <!-- 6-Item Metadata Spec Grid (2 rows x 3 cols) -->
                <div class="pd-specs-grid">
                    <!-- 1. Category -->
                    <div class="pd-spec-box">
                        <div class="pd-spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div class="pd-spec-texts">
                            <span class="pd-spec-lbl"><?= Lang::get('category_lbl') ?></span>
                            <strong class="pd-spec-val"><?= View::e($project['category']) ?></strong>
                        </div>
                    </div>

                    <!-- 2. Area -->
                    <div class="pd-spec-box">
                        <div class="pd-spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        </div>
                        <div class="pd-spec-texts">
                            <span class="pd-spec-lbl"><?= Lang::get('area_lbl') ?></span>
                            <strong class="pd-spec-val"><?= View::e($project['area']) ?></strong>
                        </div>
                    </div>

                    <!-- 3. Year -->
                    <div class="pd-spec-box">
                        <div class="pd-spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div class="pd-spec-texts">
                            <span class="pd-spec-lbl"><?= Lang::get('year_lbl') ?></span>
                            <strong class="pd-spec-val"><?= View::e($project['year']) ?></strong>
                        </div>
                    </div>

                    <!-- 4. Rooms -->
                    <div class="pd-spec-box">
                        <div class="pd-spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                        </div>
                        <div class="pd-spec-texts">
                            <span class="pd-spec-lbl"><?= Lang::get('rooms_lbl') ?></span>
                            <strong class="pd-spec-val"><?= View::e($project['rooms']) ?></strong>
                        </div>
                    </div>

                    <!-- 5. Style -->
                    <div class="pd-spec-box">
                        <div class="pd-spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <div class="pd-spec-texts">
                            <span class="pd-spec-lbl"><?= Lang::get('style_lbl') ?></span>
                            <strong class="pd-spec-val"><?= View::e($project['style']) ?></strong>
                        </div>
                    </div>

                    <!-- 6. Status -->
                    <div class="pd-spec-box">
                        <div class="pd-spec-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        </div>
                        <div class="pd-spec-texts">
                            <span class="pd-spec-lbl"><?= Lang::get('status_lbl') ?></span>
                            <strong class="pd-spec-val"><?= View::e($project['status']) ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Dual Action Buttons -->
                <div class="pd-actions-row">
                    <a href="/#contact" class="btn btn-pill-crimson" data-quote-open>
                        <?= Lang::get('get_quote_btn') ?>
                    </a>
                    <a href="/#contact" class="btn btn-proposal-pdf">
                        <span><?= Lang::get('download_proposal') ?></span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- 3. Interactive Tabbed Navigation Bar -->
        <div class="pd-tabs-nav-bar">
            <button type="button" class="pd-tab-btn is-active" data-tab-target="details">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span><?= Lang::get('tab_details') ?></span>
            </button>
            <button type="button" class="pd-tab-btn" data-tab-target="gallery">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span><?= Lang::get('tab_gallery') ?></span>
            </button>
            <button type="button" class="pd-tab-btn" data-tab-target="floorplan">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                <span><?= Lang::get('tab_floorplan') ?></span>
            </button>
            <button type="button" class="pd-tab-btn" data-tab-target="materials">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <span><?= Lang::get('tab_materials') ?></span>
            </button>
            <button type="button" class="pd-tab-btn" data-tab-target="proposal">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                <span><?= Lang::get('tab_proposal') ?></span>
            </button>
        </div>

        <!-- 4. Lower Content Multi-Column Section (3 Columns on Desktop) -->
        <div class="pd-tab-content-wrapper">
            <!-- Tab 1: Project Details (Default Active) -->
            <div class="pd-tab-panel is-active" id="tab-panel-details">
                <div class="pd-details-3col-layout">
                    <!-- Column 1: Project Description & Key Features Checklist -->
                    <div class="pd-col-description">
                        <h2 class="pd-section-heading"><?= Lang::get('project_description') ?></h2>
                        <div class="pd-narrative">
                            <p><?= View::e($project['description']) ?></p>
                        </div>

                        <!-- Key Features Checklist with Red Checkmark Icons -->
                        <div class="pd-features-block">
                            <h3 class="pd-subheading"><?= Lang::get('key_features') ?></h3>
                            <ul class="pd-features-checklist">
                                <?php foreach ($project['key_features'] as $feature): ?>
                                    <li>
                                        <span class="feature-check-icon">&#10004;</span>
                                        <span><?= View::e($feature) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 2: Project Highlights Card -->
                    <div class="pd-col-highlights">
                        <div class="pd-highlights-card">
                            <h3 class="pd-card-heading"><?= Lang::get('project_highlights') ?></h3>
                            <div class="pd-highlights-list">
                                <?php foreach ($project['highlights'] as $highlight): ?>
                                    <div class="pd-highlight-row">
                                        <div class="highlight-icon-circle">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
                                        </div>
                                        <span class="highlight-label"><?= View::e($highlight['title']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3: Let's Work Together Deep Crimson Card -->
                    <div class="pd-col-workcard">
                        <div class="pd-work-together-card">
                            <h3 class="pd-wt-title"><?= Lang::get('lets_work_together') ?></h3>
                            <p class="pd-wt-sub"><?= Lang::get('lets_work_sub') ?></p>
                            <a href="/#contact" class="btn btn-wt-cta">
                                <?= Lang::get('contact_us_btn') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Gallery Grid Panel -->
            <div class="pd-tab-panel" id="tab-panel-gallery">
                <h2 class="pd-section-heading" style="margin-bottom: 1.5rem;"><?= $isBn ? 'প্রজেক্ট ফটো গ্যালারি' : 'Project Photo Gallery' ?></h2>
                <div class="portfolio-grid" style="margin-bottom: 2rem;">
                    <?php foreach ($gallery as $gIdx => $gImg): ?>
                        <div class="project-thumb" style="aspect-ratio: 16/10; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
                            <img src="<?= View::e($gImg) ?>" alt="<?= View::e($project['title']) ?> Gallery <?= $gIdx + 1 ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tab 3: Floor Plan Panel -->
            <div class="pd-tab-panel" id="tab-panel-floorplan">
                <h2 class="pd-section-heading" style="margin-bottom: 1.2rem;"><?= $isBn ? 'আর্কিটেকচারাল ফ্লোর প্ল্যান ও স্পেস জোনিং' : 'Architectural Floor Plan &amp; Spatial Zoning' ?></h2>
                <p style="color: var(--charcoal-muted); margin-bottom: 1.5rem;">
                    <?= $isBn 
                        ? 'অনুকূল সঞ্চালন ও কার্যকরী আভিজাত্যের জন্য প্রণীত দ্বিমাত্রিক ও ত্রিমাত্রিক স্পেস প্ল্যানিং।' 
                        : 'Detailed 2D layout and 3D space planning engineered for optimal circulation and functional elegance.' ?>
                </p>
                <div class="pd-floorplan-box" style="background: var(--surface-cool); border: 2px dashed var(--border-medium); border-radius: var(--radius-md); padding: 3rem; text-align: center;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="1.5" style="margin: 0 auto 1rem;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                    <h3 style="font-size: 1.2rem; color: var(--charcoal-deep); margin-bottom: 0.5rem;"><?= View::e($project['area']) ?> <?= $isBn ? 'আর্কিটেকচারাল প্ল্যান' : 'Architectural Plan' ?></h3>
                    <p style="font-size: 0.9rem; color: var(--charcoal-muted); max-width: 45ch; margin: 0 auto 1.5rem;">
                        <?= $isBn 
                            ? 'ক্লায়েন্টের গোপনীয়তার স্বার্থে ফ্লোর প্ল্যান ব্লুপ্রিন্ট ও সিএডি লেআউট কনসালটেশনের সময় উপস্থাপন করা হয়।' 
                            : 'Floor plan blueprints and structural CAD layouts are available upon consultation for client confidentiality.' ?>
                    </p>
                    <a href="/#contact" class="btn btn-pill-crimson">
                        <?= $isBn ? 'ব্লুপ্রিন্ট ও লেআউট রিকোয়েস্ট করুন &rarr;' : 'Request Blueprint &amp; Layout &rarr;' ?>
                    </a>
                </div>
            </div>

            <!-- Tab 4: Materials Panel -->
            <div class="pd-tab-panel" id="tab-panel-materials">
                <h2 class="pd-section-heading" style="margin-bottom: 1.2rem;"><?= $isBn ? 'উপকরণ ও ফিনিশিং বিবরণ' : 'Finishes &amp; Material Specifications' ?></h2>
                <div class="services-grid" style="grid-template-columns: repeat(2, 1fr); gap: 1.2rem; margin-bottom: 2rem;">
                    <?php foreach ($project['materials'] as $mat): ?>
                        <div class="service-card" style="padding: 1.4rem;">
                            <div class="service-icon-wrap" style="margin-bottom: 0.6rem;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            </div>
                            <h3 style="font-size: 1.05rem;"><?= View::e($mat) ?></h3>
                            <p style="font-size: 0.85rem; color: var(--charcoal-muted);">
                                <?= $isBn 
                                    ? 'দীর্ঘস্থায়িত্ব ও নান্দনিক উৎকর্ষতার জন্য পরীক্ষিত গ্রেড-এ আমদানিকৃত লাক্সারি উপাদান।' 
                                    : 'Grade-A imported luxury specification tested for high longevity and aesthetic perfection.' ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tab 5: Proposal Panel -->
            <div class="pd-tab-panel" id="tab-panel-proposal">
                <h2 class="pd-section-heading" style="margin-bottom: 1.2rem;"><?= $isBn ? 'প্রজেক্ট প্রপোজাল ও এস্টিমেশন প্যাকেজ' : 'Project Proposal &amp; Estimation Package' ?></h2>
                <div class="pd-floorplan-box" style="background: var(--surface-cool); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 3rem; text-align: center;">
                    <h3 style="font-size: 1.3rem; color: var(--charcoal-deep); margin-bottom: 0.5rem;">
                        <?= $isBn ? 'কাস্টম ইন্টেরিয়র প্রপোজাল ডাউনলোড করুন' : 'Download Custom Interior Proposal' ?>
                    </h3>
                    <p style="color: var(--charcoal-muted); margin-bottom: 1.5rem;">
                        <?= $isBn 
                            ? 'আপনার প্রোপার্টির আয়তন অনুযায়ী কাস্টমাইজড কোটেশন ও ম্যাটেরিয়াল ব্রেকডাউন জেনে নিন।' 
                            : 'Get a tailored quotation and detailed material breakdown customized to your property floor size.' ?>
                    </p>
                    <a href="/#contact" class="btn btn-pill-crimson">
                        <?= $isBn ? 'অন-সাইট পরিমাপের জন্য বুক করুন &rarr;' : 'Schedule On-Site Valuation &rarr;' ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Related Projects Section -->
        <?php if (!empty($related)): ?>
            <section class="pd-related-section">
                <div class="section-header-split">
                    <div>
                        <span class="section-kicker"><?= Lang::get('more_inspiration') ?></span>
                        <h2 class="section-h2" style="font-size: 2rem;"><?= Lang::get('related_projects') ?></h2>
                    </div>
                    <a href="/projects" class="section-top-link"><?= Lang::get('view_all_projects') ?> &rarr;</a>
                </div>

                <div class="portfolio-grid">
                    <?php foreach ($related as $rel): ?>
                        <article class="project-card">
                            <a href="/projects/<?= View::e($rel['slug']) ?>" class="project-card-link" aria-label="View <?= View::e($rel['title']) ?>">
                                <div class="project-thumb">
                                    <img src="<?= View::e($rel['image']) ?>" alt="<?= View::e($rel['title']) ?>" loading="lazy">
                                    <span class="project-cat-badge"><?= View::e($rel['category']) ?></span>
                                </div>
                                <div class="project-body">
                                    <h3 class="project-title"><?= View::e($rel['title']) ?></h3>
                                    <p class="project-location">&#128205; <?= View::e($rel['location']) ?></p>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</div>

<!-- Protected High-Res Image Lightbox Modal with Company Logo Watermark -->
<div class="pd-lightbox-modal" id="pd-lightbox" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Enlarged Project Image Preview">
    <div class="pd-lightbox-backdrop" id="pd-lightbox-backdrop"></div>
    <div class="pd-lightbox-dialog">
        <!-- Close Button -->
        <button type="button" class="pd-lightbox-close" id="pd-lightbox-close" aria-label="Close image preview">&times;</button>
        
        <!-- Previous & Next Navigation Arrows -->
        <button type="button" class="pd-lightbox-nav pd-lightbox-prev" id="pd-lightbox-prev" aria-label="Previous Image">&#10094;</button>
        <button type="button" class="pd-lightbox-nav pd-lightbox-next" id="pd-lightbox-next" aria-label="Next Image">&#10095;</button>

        <!-- Image Container with Screenshot Protection & Logo Watermark -->
        <div class="pd-lightbox-figure-wrap" id="pd-lightbox-figure" oncontextmenu="return false;">
            <img id="pd-lightbox-image" src="" alt="" draggable="false">

            <!-- Security Watermark Overlay Layer -->
            <div class="pd-security-watermark-overlay" aria-hidden="true">
                <!-- Center Watermark Emblem -->
                <div class="pd-watermark-center-stamp">
                    <img src="/assets/img/lily-logo.png" alt="Lily Interiors" class="pd-watermark-logo">
                    <span class="pd-watermark-badge"><?= $isBn ? 'লিলি ইন্টেরিয়র্স • কপিরাইট সংরক্ষিত ডিজাইন' : 'LILY INTERIORS &bull; COPYRIGHT PROTECTED &bull; OFFICIAL DESIGN' ?></span>
                </div>
                <!-- Diagonal Security Watermark Grid -->
                <div class="pd-watermark-repeater">
                    <span>LILY INTERIORS &bull; PROPRIETARY DESIGN</span>
                    <span>LILY INTERIORS &bull; PROPRIETARY DESIGN</span>
                    <span>LILY INTERIORS &bull; PROPRIETARY DESIGN</span>
                    <span>LILY INTERIORS &bull; PROPRIETARY DESIGN</span>
                </div>
            </div>
        </div>

        <!-- Lightbox Caption Bar -->
        <div class="pd-lightbox-caption">
            <span class="pd-lightbox-counter" id="pd-lightbox-counter">1 / <?= count($gallery) ?></span>
            <span class="pd-lightbox-sep">&bull;</span>
            <strong class="pd-lightbox-title"><?= View::e($project['title']) ?></strong>
            <span class="pd-lightbox-loc">&#128205; <?= View::e($project['location']) ?></span>
        </div>
    </div>
</div>

<!-- About Us Authority Snapshot -->
<?= View::renderPartial('partials.about') ?>

<!-- Trust & Achievement Statistics -->
<?= View::renderPartial('partials.stats') ?>

<!-- Breadcrumb & CreativeWork JSON-LD Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "<?= $isBn ? 'হোম' : 'Home' ?>",
          "item": "https://lilyinteriorsbd.com/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "<?= $isBn ? 'প্রজেক্টস' : 'Projects' ?>",
          "item": "https://lilyinteriorsbd.com/projects"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": <?= json_encode($project['title']) ?>,
          "item": "https://lilyinteriorsbd.com/projects/<?= View::e($project['slug']) ?>"
        }
      ]
    },
    {
      "@type": "CreativeWork",
      "name": <?= json_encode($project['title']) ?>,
      "headline": <?= json_encode($project['title'] . ' — ' . $project['location']) ?>,
      "description": <?= json_encode($project['summary']) ?>,
      "image": "https://lilyinteriorsbd.com<?= View::e($project['image']) ?>",
      "creator": {
        "@type": "Organization",
        "name": "Lily Interiors",
        "url": "https://lilyinteriorsbd.com"
      },
      "provider": {
        "@type": "Organization",
        "name": "Lily Interiors",
        "url": "https://lilyinteriorsbd.com"
      },
      "contentLocation": {
        "@type": "Place",
        "name": <?= json_encode($project['location']) ?>
      }
    }
  ]
}
</script>

