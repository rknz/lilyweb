<?php

/**
 * About Us Page.
 * Tells our story since 2017, what we do, our government registrations,
 * our guarantees and our track record. Fully localized EN/BN.
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\View;

$isBn = Lang::isBn();

$services = [
    ['icon' => 'lamp',        'title' => Lang::get('service_interior_title'),   'text' => Lang::get('service_interior_text')],
    ['icon' => 'home',        'title' => Lang::get('service_architecture_title'),'text' => Lang::get('service_architecture_text')],
    ['icon' => 'hammer',      'title' => Lang::get('service_renovation_title'),  'text' => Lang::get('service_renovation_text')],
    ['icon' => 'sofa',        'title' => Lang::get('service_furniture_title'),   'text' => Lang::get('service_furniture_text')],
    ['icon' => 'pencil',      'title' => Lang::get('service_space_title'),       'text' => Lang::get('service_space_text')],
    ['icon' => 'shield',      'title' => Lang::get('service_project_title'),     'text' => Lang::get('service_project_text')],
];

$stats = [
    ['val' => '250+', 'lbl' => Lang::get('stat_1_lbl')],
    ['val' => '180+', 'lbl' => Lang::get('stat_2_lbl')],
    ['val' => '8+',   'lbl' => Lang::get('stat_3_lbl')],
    ['val' => '15+',  'lbl' => Lang::get('stat_4_lbl')],
];

$guarantees = [
    ['icon' => 'check',       'title' => Lang::get('about_guarantee_item1_title'), 'text' => Lang::get('about_guarantee_item1_text')],
    ['icon' => 'clock',       'title' => Lang::get('about_guarantee_item2_title'), 'text' => Lang::get('about_guarantee_item2_text')],
    ['icon' => 'tag',         'title' => Lang::get('about_guarantee_item3_title'), 'text' => Lang::get('about_guarantee_item3_text')],
];

$registrations = [
    ['icon' => 'doc',    'title' => Lang::get('about_reg_item1'), 'sub' => Lang::get('about_reg_sub')],
    ['icon' => 'id',     'title' => Lang::get('about_reg_item2'), 'sub' => Lang::get('about_reg_sub')],
    ['icon' => 'percent','title' => Lang::get('about_reg_item3'), 'sub' => Lang::get('about_reg_sub')],
];

$iconSvg = function ($name) {
    switch ($name) {
        case 'lamp':    return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5A5.9 5.9 0 0 0 18 7a6 6 0 0 0-12 0c0 1.8.5 3 1.5 4.5.8.8 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>';
        case 'home':    return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V10"/></svg>';
        case 'hammer':  return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 12l-8.5 8.5a2.12 2.12 0 1 1-3-3L12 9"/><path d="M17.64 15L22 10.64"/><path d="M20.91 11.7l-6-3.9a1 1 0 0 0-1.2 0l-3.5 2.4a1 1 0 0 0 0 1.6l5 3.2a1 1 0 0 0 1.2 0l3.7-2.4a1 1 0 0 0-.2-1.1z"/></svg>';
        case 'sofa':    return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 11V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v5"/><path d="M3 11a2 2 0 0 1 4 0v2h10v-2a2 2 0 1 1 4 0v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/><path d="M4 20v-2M20 20v-2"/></svg>';
        case 'pencil':  return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>';
        case 'shield':  return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
        case 'check':   return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
        case 'clock':   return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
        case 'tag':     return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>';
        case 'doc':     return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="13" y2="17"/></svg>';
        case 'id':      return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><circle cx="7" cy="15" r="1.5"/><line x1="12" y1="15" x2="17" y2="15"/></svg>';
        case 'percent': return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>';
        default:        return '';
    }
};
?>
<!-- ============ ABOUT PAGE HERO ============ -->
<section class="section about-page-hero">
    <div class="site-wrapper">
        <div class="section-header-center" data-reveal>
            <span class="section-kicker"><?= View::e(Lang::get('about_page_kicker')) ?></span>
            <h1 class="section-h2"><?= View::e(Lang::get('about_page_h1')) ?></h1>
            <p class="section-sub-lead">
                <?= View::e(Lang::get('about_page_lead')) ?>
            </p>
        </div>
    </div>
</section>

<!-- ============ WHO WE ARE + EXPERIENCE BADGE ============ -->
<section class="section about-body">
    <div class="site-wrapper about-layout">
        <div class="about-copy" data-reveal>
            <span class="section-kicker"><?= $isBn ? 'আমাদের পরিচয়' : 'OUR IDENTITY' ?></span>
            <h2 class="section-h2"><?= View::e(Lang::get('about_who_title')) ?></h2>
            <p class="about-lead"><?= View::e(Lang::get('about_who_text')) ?></p>
            <div class="about-since-badge">
                <span class="asb-since"><?= View::e(Lang::get('about_since')) ?></span>
            </div>
        </div>
        <div class="about-media" data-reveal>
            <div class="about-dot-grid" aria-hidden="true"></div>
            <div class="about-img-main">
                <picture>
                    <source srcset="/assets/img/about-1.webp" type="image/webp">
                    <img src="/assets/img/about-1.jpg" alt="Lily Interior studio" width="480" height="540" loading="lazy" decoding="async">
                </picture>
            </div>
            <div class="about-experience-badge" data-reveal>
                <span class="exp-num"><?= View::e(Lang::get('about_experience_badge')) ?></span>
                <span class="exp-text"><?= View::e(Lang::get('about_experience_lbl')) ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ============ WHAT WE DO (SERVICES) ============ -->
<section class="section about-services">
    <div class="site-wrapper">
        <div class="section-header-center" data-reveal>
            <span class="section-kicker"><?= $isBn ? 'আমাদের দক্ষতা' : 'OUR EXPERTISE' ?></span>
            <h2 class="section-h2"><?= View::e(Lang::get('about_services_title')) ?></h2>
            <p class="section-sub-lead"><?= View::e(Lang::get('about_services_sub')) ?></p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $svc): ?>
                <article class="service-card" data-reveal>
                    <div class="service-top-row">
                        <span class="service-icon-pod"><?= $iconSvg($svc['icon']) ?></span>
                    </div>
                    <div class="service-body">
                        <h3 class="service-title"><?= View::e($svc['title']) ?></h3>
                        <p class="service-desc"><?= View::e($svc['text']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ TRACK RECORD STATS ============ -->
<section class="section about-stats">
    <div class="site-wrapper">
        <div class="section-header-center" data-reveal>
            <span class="section-kicker"><?= $isBn ? 'আমাদের অর্জন' : 'OUR ACHIEVEMENTS' ?></span>
            <h2 class="section-h2"><?= View::e(Lang::get('about_track_title')) ?></h2>
        </div>
        <div class="stats-grid">
            <?php foreach ($stats as $st): ?>
                <div class="stat-card" data-reveal>
                    <span class="stat-number"><?= View::e($st['val']) ?></span>
                    <span class="stat-label"><?= View::e($st['lbl']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ GOVERNMENT REGISTRATION ============ -->
<section class="section about-reg">
    <div class="site-wrapper">
        <div class="section-header-center" data-reveal>
            <span class="section-kicker"><?= $isBn ? 'সরকারি নিবন্ধন' : 'GOVERNMENT REGISTRATION' ?></span>
            <h2 class="section-h2"><?= View::e(Lang::get('about_reg_title')) ?></h2>
            <p class="section-sub-lead"><?= View::e(Lang::get('about_reg_sub')) ?></p>
        </div>
        <div class="reg-grid">
            <?php foreach ($registrations as $reg): ?>
                <div class="reg-card" data-reveal>
                    <span class="reg-icon"><?= $iconSvg($reg['icon']) ?></span>
                    <span class="reg-title"><?= View::e($reg['title']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="about-reg-text" data-reveal><?= View::e(Lang::get('about_reg_text')) ?></p>
    </div>
</section>

<!-- ============ OUR GUARANTEES ============ -->
<section class="section about-guarantee">
    <div class="site-wrapper">
        <div class="section-header-center" data-reveal>
            <span class="section-kicker"><?= $isBn ? 'আমাদের প্রতিশ্রুতি' : 'OUR COMMITMENT' ?></span>
            <h2 class="section-h2"><?= View::e(Lang::get('about_guarantee_title')) ?></h2>
        </div>
        <div class="guarantee-grid">
            <?php foreach ($guarantees as $g): ?>
                <div class="guarantee-card" data-reveal>
                    <span class="guarantee-icon"><?= $iconSvg($g['icon']) ?></span>
                    <h3 class="guarantee-title"><?= View::e($g['title']) ?></h3>
                    <p class="guarantee-text"><?= View::e($g['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="section about-cta">
    <div class="site-wrapper">
        <div class="cta-box" data-reveal>
            <h2 class="section-h2"><?= View::e(Lang::get('about_cta_title')) ?></h2>
            <p class="cta-text"><?= View::e(Lang::get('about_cta_text')) ?></p>
            <a class="btn-pill-crimson" href="/#contact"><?= Lang::get('about_cta_btn') ?></a>
        </div>
    </div>
</section>
