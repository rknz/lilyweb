<?php
use Lilyweb\Core\Security;
?>
<style>
    /* ========================================================
       MASTER HOMEPAGE SECTIONS BUILDER (OBSIDIAN LUXURY SUITE)
       ======================================================== */
    
    /* Top Quick Navigation Pill Bar */
    .hp-quick-nav-bar {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        padding: 0.75rem 1.15rem;
        border-radius: var(--radius-lg);
        margin-bottom: 2rem;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        box-shadow: var(--card-shadow);
        position: sticky;
        top: 86px;
        z-index: 40;
        backdrop-filter: blur(12px);
    }

    .hp-quick-nav-bar::-webkit-scrollbar { display: none; }

    .hp-nav-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.42rem 0.85rem;
        background: var(--bg-card-alt);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-full);
        color: var(--text-heading);
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 700;
        transition: var(--transition-smooth);
    }

    .hp-nav-pill:hover, .hp-nav-pill.is-active {
        background: var(--crimson-gradient) !important;
        color: #FFFFFF !important;
        border-color: var(--crimson) !important;
        box-shadow: 0 4px 14px var(--crimson-glow) !important;
    }

    /* Section Cards Stack */
    .hp-sections-stack {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .hp-section-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 1.85rem 1.85rem;
        box-shadow: var(--card-shadow);
        position: relative;
        scroll-margin-top: 150px;
        transition: var(--transition-smooth);
    }

    .hp-section-card:hover {
        border-color: rgba(200, 16, 46, 0.35);
        box-shadow: var(--card-shadow-hover);
    }

    .hp-sec-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1.15rem;
        border-bottom: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hp-sec-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .hp-sec-badge-num {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-md);
        background: var(--crimson-gradient);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 0.92rem;
        display: grid;
        place-items: center;
        box-shadow: 0 4px 12px var(--crimson-glow);
        flex-shrink: 0;
    }

    .hp-sec-title-text h2 {
        font-size: 1.18rem;
        font-weight: 800;
        color: var(--text-heading);
        line-height: 1.2;
    }

    .hp-sec-title-text p {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.15rem;
    }

    .hp-sec-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-sec-action {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: var(--bg-card-alt);
        border: 1.5px solid var(--border-color);
        color: var(--text-heading);
        padding: 0.45rem 0.95rem;
        border-radius: var(--radius-md);
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition-smooth);
    }

    .btn-sec-action:hover {
        border-color: var(--crimson);
        color: var(--crimson);
        transform: translateY(-2px);
    }

    .btn-sec-primary {
        background: var(--crimson-gradient);
        color: #FFFFFF !important;
        border-color: transparent;
        box-shadow: 0 4px 14px var(--crimson-glow);
    }

    .btn-sec-primary:hover {
        box-shadow: 0 6px 20px rgba(200, 16, 46, 0.6);
        color: #FFFFFF !important;
    }

    /* Grid Items inside Sections */
    .hp-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.15rem;
    }

    .hp-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.15rem;
    }

    .hp-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .hp-item-card {
        background: var(--bg-card-alt);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.15rem;
        position: relative;
        transition: var(--transition-smooth);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .hp-item-card:hover {
        transform: translateY(-3px);
        border-color: var(--crimson);
    }

    .hp-item-cover {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: var(--radius-md);
        margin-bottom: 0.85rem;
        background: #000;
    }

    .hp-item-title {
        font-size: 0.92rem;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 0.35rem;
        line-height: 1.3;
    }

    .hp-item-desc {
        font-size: 0.78rem;
        color: var(--text-muted);
        line-height: 1.45;
        margin-bottom: 0.85rem;
    }

    .hp-item-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-subtle);
    }

    /* Form Styles */
    .hp-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1.15rem;
    }

    .hp-form-label {
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--text-heading);
    }

    .hp-form-input, .hp-form-textarea {
        background: var(--bg-input);
        border: 1.5px solid var(--border-color);
        color: var(--text-heading);
        padding: 0.65rem 0.95rem;
        border-radius: var(--radius-md);
        font-size: 0.88rem;
        font-family: inherit;
        outline: none;
        transition: var(--transition-smooth);
    }

    .hp-form-input:focus, .hp-form-textarea:focus {
        border-color: var(--crimson);
        box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.15);
    }

    .badge-live-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(34, 197, 94, 0.12);
        color: #22C55E;
        font-size: 0.74rem;
        font-weight: 800;
        padding: 0.18rem 0.55rem;
        border-radius: var(--radius-full);
    }

    @media (max-width: 1200px) {
        .hp-grid-4 { grid-template-columns: repeat(2, 1fr); }
        .hp-grid-3 { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .hp-grid-4, .hp-grid-3, .hp-grid-2 { grid-template-columns: 1fr; }
        .hp-section-card { padding: 1.25rem; }
    }
</style>

<!-- Floating Section Navigator -->
<div class="hp-quick-nav-bar">
    <span style="font-size: 0.82rem; font-weight: 800; color: var(--text-muted); margin-right: 0.35rem;">JUMP TO:</span>
    <a href="#sec-hero" class="hp-nav-pill">🖼️ Hero Slider</a>
    <a href="#sec-stats" class="hp-nav-pill">🏆 Achievement Stats</a>
    <a href="#sec-about" class="hp-nav-pill">🏢 About Us</a>
    <a href="#sec-services" class="hp-nav-pill">🛠️ Services (6 Cards)</a>
    <a href="#sec-process" class="hp-nav-pill">🔄 Process Steps</a>
    <a href="#sec-portfolio" class="hp-nav-pill">🎨 Portfolio</a>
    <a href="#sec-testimonials" class="hp-nav-pill">💬 Testimonials</a>
    <a href="#sec-contact" class="hp-nav-pill">📞 Contact CTA</a>
</div>

<div class="hp-sections-stack">
    <!-- ========================================================
         SECTION 1: HERO SLIDER
         ======================================================== -->
    <div class="hp-section-card" id="sec-hero">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">1</div>
                <div class="hp-sec-title-text">
                    <h2>Hero Action Slider Section</h2>
                    <p>Manage the dynamic full-screen luxury banner slides on the website front-page.</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <span class="badge-live-tag">● <?= count($heroSlides) ?> Active Slides</span>
                <a href="/admin/hero/create" class="btn-sec-action btn-sec-primary">+ Add New Slide</a>
                <a href="/admin/hero" class="btn-sec-action">Full Manager &rarr;</a>
            </div>
        </div>

        <div class="hp-grid-3">
            <?php foreach ($heroSlides as $slide): 
                $slideTitle = trim(($slide['title_prefix_en'] ?? '') . ' ' . ($slide['title_highlight_en'] ?? '') . ' ' . ($slide['title_suffix_en'] ?? ''));
                if (empty($slideTitle)) $slideTitle = 'Hero Slide #' . $slide['id'];
            ?>
                <div class="hp-item-card">
                    <img src="<?= Security::e($slide['image_url']) ?>" alt="" class="hp-item-cover">
                    <div class="hp-item-title"><?= Security::e($slideTitle) ?></div>
                    <div class="hp-item-desc"><?= Security::e(mb_strimwidth($slide['subtitle_en'] ?? '', 0, 80, '...')) ?></div>
                    <div class="hp-item-footer">
                        <span style="font-size: 0.74rem; font-weight: 700; color: var(--text-muted);">Sort: #<?= (int)$slide['sort_order'] ?></span>
                        <a href="/admin/hero/edit/<?= (int)$slide['id'] ?>" class="btn-sec-action" style="padding: 0.25rem 0.65rem;">Edit Slide</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ========================================================
         SECTION 2: ACHIEVEMENT STATS
         ======================================================== -->
    <div class="hp-section-card" id="sec-stats">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">2</div>
                <div class="hp-sec-title-text">
                    <h2>Achievement Stats &amp; Counters</h2>
                    <p>Live metrics displayed immediately below the hero section (Years, Projects, Satisfaction, Awards).</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <a href="/admin/stats" class="btn-sec-action">Manage Stats &rarr;</a>
            </div>
        </div>

        <div class="hp-grid-4">
            <?php foreach ($statsList as $st): ?>
                <div class="hp-item-card" style="text-align: center; align-items: center;">
                    <div style="font-size: 2.2rem; font-weight: 800; color: var(--crimson); line-height: 1; margin-bottom: 0.35rem;">
                        <?= Security::e(($st['value_number'] ?? '0') . ($st['suffix'] ?? '+')) ?>
                    </div>
                    <div class="hp-item-title" style="margin-bottom: 0.2rem;"><?= Security::e($st['label_en']) ?></div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);"><?= Security::e($st['label_bn']) ?></div>
                    <div class="hp-item-footer" style="width: 100%; margin-top: 0.85rem;">
                        <span class="badge-live-tag">Active</span>
                        <a href="/admin/stats/edit/<?= (int)$st['id'] ?>" class="btn-sec-action" style="padding: 0.2rem 0.55rem;">Edit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ========================================================
         SECTION 3: ABOUT US SECTION
         ======================================================== -->
    <div class="hp-section-card" id="sec-about">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">3</div>
                <div class="hp-sec-title-text">
                    <h2>About Us Section Content</h2>
                    <p>Company story, architectural philosophy, kicker badges, and craftsmanship experience years.</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <a href="/admin/about" class="btn-sec-action btn-sec-primary">Full About Editor &rarr;</a>
            </div>
        </div>

        <form action="/admin/about/save" method="POST">
            <?= Security::csrfField() ?>
            <div class="hp-grid-2">
                <div class="hp-form-group">
                    <label class="hp-form-label">English Heading</label>
                    <input type="text" name="about_heading_en" value="<?= Security::e($about['about_heading_en']) ?>" class="hp-form-input">
                </div>
                <div class="hp-form-group">
                    <label class="hp-form-label">Bengali Heading (বাংলা শিরোনাম)</label>
                    <input type="text" name="about_heading_bn" value="<?= Security::e($about['about_heading_bn']) ?>" class="hp-form-input">
                </div>
            </div>

            <div class="hp-grid-2">
                <div class="hp-form-group">
                    <label class="hp-form-label">English Story Lead</label>
                    <textarea name="about_lead_en" rows="3" class="hp-form-textarea"><?= Security::e($about['about_lead_en']) ?></textarea>
                </div>
                <div class="hp-form-group">
                    <label class="hp-form-label">Bengali Story Lead (বাংলা বিবরণ)</label>
                    <textarea name="about_lead_bn" rows="3" class="hp-form-textarea"><?= Security::e($about['about_lead_bn']) ?></textarea>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                <button type="submit" class="btn-sec-action btn-sec-primary" style="padding: 0.6rem 1.4rem;">Save About Changes</button>
            </div>
        </form>
    </div>

    <!-- ========================================================
         SECTION 4: SERVICES (6 CARDS)
         ======================================================== -->
    <div class="hp-section-card" id="sec-services">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">4</div>
                <div class="hp-sec-title-text">
                    <h2>Our Services (6 Interactive Feature Cards)</h2>
                    <p>Interior design architectural disciplines shown on the homepage with custom icons &amp; details.</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <span class="badge-live-tag">● <?= count($services) ?> Services Live</span>
                <a href="/admin/services/create" class="btn-sec-action btn-sec-primary">+ Add Service</a>
                <a href="/admin/services" class="btn-sec-action">Full Services Manager &rarr;</a>
            </div>
        </div>

        <div class="hp-grid-3">
            <?php foreach ($services as $srv): ?>
                <div class="hp-item-card">
                    <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.65rem;">
                        <span style="font-size: 1.4rem;">✨</span>
                        <div class="hp-item-title" style="margin: 0;"><?= Security::e($srv['title_en']) ?></div>
                    </div>
                    <div class="hp-item-desc"><?= Security::e(mb_strimwidth($srv['summary_en'] ?? $srv['description_en'] ?? '', 0, 95, '...')) ?></div>
                    <div class="hp-item-footer">
                        <span style="font-size: 0.74rem; font-weight: 700; color: var(--text-muted);">/services/<?= Security::e($srv['slug']) ?></span>
                        <a href="/admin/services/edit/<?= (int)$srv['id'] ?>" class="btn-sec-action" style="padding: 0.25rem 0.65rem;">Edit Card</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ========================================================
         SECTION 5: PROCESS STEPS (4 STEPS)
         ======================================================== -->
    <div class="hp-section-card" id="sec-process">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">5</div>
                <div class="hp-sec-title-text">
                    <h2>Execution Process Steps (4 Stages)</h2>
                    <p>Step 1 Consultation &rarr; Step 2 Concept &rarr; Step 3 3D Visualization &rarr; Step 4 Handover.</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <a href="/admin/process" class="btn-sec-action">Manage Process Steps &rarr;</a>
            </div>
        </div>

        <div class="hp-grid-4">
            <?php foreach ($processSteps as $step): ?>
                <div class="hp-item-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.1rem; font-weight: 800; color: var(--crimson);">Step <?= (int)$step['step_number'] ?></span>
                        <span style="font-size: 1.2rem;">📌</span>
                    </div>
                    <div class="hp-item-title"><?= Security::e($step['title_en']) ?></div>
                    <div class="hp-item-desc"><?= Security::e(mb_strimwidth($step['description_en'] ?? '', 0, 85, '...')) ?></div>
                    <div class="hp-item-footer">
                        <a href="/admin/process/edit/<?= (int)$step['id'] ?>" class="btn-sec-action" style="padding: 0.2rem 0.55rem; width: 100%; text-align: center; justify-content: center;">Edit Step</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ========================================================
         SECTION 6: FEATURED PORTFOLIO
         ======================================================== -->
    <div class="hp-section-card" id="sec-portfolio">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">6</div>
                <div class="hp-sec-title-text">
                    <h2>Featured Portfolio Projects Showcase</h2>
                    <p>Architectural luxury works highlighted on the homepage gallery grid.</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <a href="/admin/projects/create" class="btn-sec-action btn-sec-primary">+ Add Project</a>
                <a href="/admin/projects" class="btn-sec-action">Full Portfolio &rarr;</a>
            </div>
        </div>

        <div class="hp-grid-3">
            <?php foreach ($featuredProjects as $proj): ?>
                <div class="hp-item-card">
                    <img src="<?= Security::e($proj['cover_image']) ?>" alt="" class="hp-item-cover">
                    <div class="hp-item-title"><?= Security::e($proj['title_en']) ?></div>
                    <div style="font-size: 0.74rem; color: var(--text-muted); margin-bottom: 0.65rem;">📍 <?= Security::e($proj['location_en'] ?? 'Dhaka, Bangladesh') ?></div>
                    <div class="hp-item-footer">
                        <span class="badge-live-tag">Published</span>
                        <a href="/admin/projects/edit/<?= (int)$proj['id'] ?>" class="btn-sec-action" style="padding: 0.25rem 0.65rem;">Edit Project</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ========================================================
         SECTION 7: CLIENT TESTIMONIALS
         ======================================================== -->
    <div class="hp-section-card" id="sec-testimonials">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">7</div>
                <div class="hp-sec-title-text">
                    <h2>Client Testimonials &amp; Reviews</h2>
                    <p>Verified client testimonials with 5-star ratings and residential/commercial reviews.</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <a href="/admin/testimonials/create" class="btn-sec-action btn-sec-primary">+ Add Review</a>
                <a href="/admin/testimonials" class="btn-sec-action">Full Reviews Manager &rarr;</a>
            </div>
        </div>

        <div class="hp-grid-3">
            <?php foreach ($testimonials as $t): ?>
                <div class="hp-item-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: #F59E0B; font-size: 0.88rem; font-weight: 800;">
                            <?= str_repeat('★', (int)($t['rating_score'] ?? 5)) ?>
                        </span>
                        <span style="font-size: 0.72rem; color: var(--text-muted);"><?= Security::e($t['project_tag_en'] ?? 'Residential') ?></span>
                    </div>
                    <div class="hp-item-title"><?= Security::e($t['author_name']) ?></div>
                    <div class="hp-item-desc">“<?= Security::e(mb_strimwidth($t['content_en'] ?? '', 0, 95, '...')) ?>”</div>
                    <div class="hp-item-footer">
                        <span style="font-size: 0.72rem; color: var(--text-muted);"><?= Security::e($t['author_role_en'] ?? 'Client') ?></span>
                        <a href="/admin/testimonials/edit/<?= (int)$t['id'] ?>" class="btn-sec-action" style="padding: 0.25rem 0.65rem;">Edit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ========================================================
         SECTION 8: CONTACT & CONSULTATION CTA
         ======================================================== -->
    <div class="hp-section-card" id="sec-contact">
        <div class="hp-sec-header">
            <div class="hp-sec-title-wrap">
                <div class="hp-sec-badge-num">8</div>
                <div class="hp-sec-title-text">
                    <h2>Homepage Contact &amp; Consultation CTA</h2>
                    <p>Direct consultation phone numbers, WhatsApp, email, and office address settings.</p>
                </div>
            </div>
            <div class="hp-sec-actions">
                <a href="/admin/settings" class="btn-sec-action btn-sec-primary">Full Global Settings &rarr;</a>
            </div>
        </div>

        <div class="hp-grid-2">
            <div class="hp-item-card">
                <div class="hp-item-title">📞 Phone &amp; Direct Support</div>
                <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.25rem;">
                    <?= Security::e($contactSettings['contact_phone'] ?? '+880 1711-000000') ?>
                </div>
                <div class="hp-item-desc">WhatsApp: <?= Security::e($contactSettings['contact_whatsapp'] ?? '+880 1711-000000') ?></div>
            </div>

            <div class="hp-item-card">
                <div class="hp-item-title">📍 Official Office Address</div>
                <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.25rem;">
                    <?= Security::e($contactSettings['contact_address_en'] ?? 'Dhaka, Bangladesh') ?>
                </div>
                <div class="hp-item-desc">Email: <?= Security::e($contactSettings['contact_email'] ?? 'info@lilyinteriors.com') ?></div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var pills = document.querySelectorAll('.hp-nav-pill');
        var sections = document.querySelectorAll('.hp-section-card');

        function updateActivePill() {
            var scrollPos = window.scrollY + 200;
            var currentSectionId = '';

            sections.forEach(function(sec) {
                var top = sec.offsetTop;
                var height = sec.offsetHeight;
                if (scrollPos >= top && scrollPos < top + height) {
                    currentSectionId = sec.getAttribute('id');
                }
            });

            if (!currentSectionId && sections.length > 0 && scrollPos < sections[0].offsetTop) {
                currentSectionId = sections[0].getAttribute('id');
            }

            pills.forEach(function(pill) {
                var targetHref = pill.getAttribute('href').replace('#', '');
                if (targetHref === currentSectionId) {
                    pill.classList.add('is-active');
                    pill.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                } else {
                    pill.classList.remove('is-active');
                }
            });
        }

        window.addEventListener('scroll', updateActivePill, { passive: true });
        pills.forEach(function(pill) {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = this.getAttribute('href').replace('#', '');
                var targetSec = document.getElementById(targetId);
                if (targetSec) {
                    var offset = 140;
                    var bodyRect = document.body.getBoundingClientRect().top;
                    var elementRect = targetSec.getBoundingClientRect().top;
                    var elementPosition = elementRect - bodyRect;
                    var offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    pills.forEach(function(p) { p.classList.remove('is-active'); });
                    this.classList.add('is-active');
                }
            });
        });

        // Initial check
        updateActivePill();
    })();
</script>
