<?php
use Lilyweb\Core\Security;
?>
<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/hero" class="btn-back-highlight">
        <span>&larr; Back to Hero Slides List</span>
    </a>
</div>

<div class="card-header-flex">
    <div>
        <h2 class="card-title"><?= $isEdit ? 'Edit Hero Slide #' . Security::e($slide['step_number'] ?? '01') : 'Add New Hero Slide' ?></h2>
        <p class="card-subtitle">
            English is mandatory. Bangla is optional — if left blank, the frontend automatically falls back to clean auto-translation.
        </p>
    </div>
</div>

<form action="/admin/hero/save" method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($slide['id'] ?? 0) ?>">

    <div class="form-grid">
        <!-- English Column (Mandatory) -->
        <div class="admin-card card-master-en">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.4rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.5rem;">
                    <span>🇬🇧 English Content</span>
                </h3>
                <span class="badge-pill-blue">Required Master</span>
            </div>

            <div class="form-group">
                <label class="form-label">Step Number <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="step_number" class="form-input" required value="<?= Security::e($slide['step_number'] ?? '01') ?>" placeholder="e.g. 01">
                <p class="form-help">Two-digit stepper indicator on the left side of the hero slider.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Top Kicker Pill <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="kicker_en" class="form-input" required value="<?= Security::e($slide['kicker_en'] ?? '') ?>" placeholder="e.g. DESIGN • CREATE • INSPIRE">
            </div>

            <div class="form-group">
                <label class="form-label">Title Prefix <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="title_prefix_en" class="form-input" required value="<?= Security::e($slide['title_prefix_en'] ?? '') ?>" placeholder="e.g. We Design Spaces That">
            </div>

            <div class="form-group">
                <label class="form-label">Title Highlight Word (Crimson) <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="title_highlight_en" class="form-input" required value="<?= Security::e($slide['title_highlight_en'] ?? '') ?>" placeholder="e.g. Inspire">
            </div>

            <div class="form-group">
                <label class="form-label">Title Suffix <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="title_suffix_en" class="form-input" required value="<?= Security::e($slide['title_suffix_en'] ?? '') ?>" placeholder="e.g. Life">
            </div>

            <div class="form-group">
                <label class="form-label">Subtitle Description <span class="req" style="color: var(--crimson);">*</span></label>
                <textarea name="subtitle_en" class="form-textarea" required placeholder="Subtitle narrative"><?= Security::e($slide['subtitle_en'] ?? '') ?></textarea>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Room Type (EN)</label>
                    <input type="text" name="badge_room_en" class="form-input" value="<?= Security::e($slide['badge_room_en'] ?? 'Modern Living Room') ?>" placeholder="e.g. Modern Living Room">
                </div>
                <div class="form-group">
                    <label class="form-label">Location (EN)</label>
                    <input type="text" name="badge_location_en" class="form-input" value="<?= Security::e($slide['badge_location_en'] ?? 'Gulshan 2, Dhaka') ?>" placeholder="e.g. Gulshan 2, Dhaka">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">CTA Button Text (EN)</label>
                <input type="text" name="cta_text_en" class="form-input" value="<?= Security::e($slide['cta_text_en'] ?? 'Explore Projects →') ?>">
            </div>
        </div>

        <!-- Bengali Column (Optional Fallback) -->
        <div class="admin-card card-optional-bn">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.4rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.5rem;">
                    <span>🇧🇩 বাংলা অনুবাদ (Bengali)</span>
                </h3>
                <span class="badge-pill-crimson">ঐচ্ছিক অনুবাদ</span>
            </div>

            <div class="form-group">
                <label class="form-label">টপ কিকার পিল (বাংলা) <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <input type="text" name="kicker_bn" class="form-input" value="<?= Security::e($slide['kicker_bn'] ?? '') ?>" placeholder="যেমন: ডিজাইন • সৃষ্টি • নান্দনিকতা">
            </div>

            <div class="form-group">
                <label class="form-label">টাইটেল শুরুর অংশ <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <input type="text" name="title_prefix_bn" class="form-input" value="<?= Security::e($slide['title_prefix_bn'] ?? '') ?>" placeholder="যেমন: আমরা সাজাই সেই সব স্থান">
            </div>

            <div class="form-group">
                <label class="form-label">হাইলাইট শব্দ (লাল) <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <input type="text" name="title_highlight_bn" class="form-input" value="<?= Security::e($slide['title_highlight_bn'] ?? '') ?>" placeholder="যেমন: যা অনুপ্রাণিত">
            </div>

            <div class="form-group">
                <label class="form-label">টাইটেল শেষের অংশ <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <input type="text" name="title_suffix_bn" class="form-input" value="<?= Security::e($slide['title_suffix_bn'] ?? '') ?>" placeholder="যেমন: করে জীবনকে">
            </div>

            <div class="form-group">
                <label class="form-label">বাংলা সাবটাইটেল <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <textarea name="subtitle_bn" class="form-textarea" placeholder="বাংলা সাবটাইটেল বর্ণনা"><?= Security::e($slide['subtitle_bn'] ?? '') ?></textarea>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">রুমের ধরন (বাংলা)</label>
                    <input type="text" name="badge_room_bn" class="form-input" value="<?= Security::e($slide['badge_room_bn'] ?? '') ?>" placeholder="যেমন: মডার্ন লিভিং রুম">
                </div>
                <div class="form-group">
                    <label class="form-label">লোকেশন (বাংলা)</label>
                    <input type="text" name="badge_location_bn" class="form-input" value="<?= Security::e($slide['badge_location_bn'] ?? '') ?>" placeholder="যেমন: গুলশান ২, ঢাকা">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">বাংলা বাটন টেক্সট</label>
                <input type="text" name="cta_text_bn" class="form-input" value="<?= Security::e($slide['cta_text_bn'] ?? 'প্রজেক্টসমূহ দেখুন →') ?>">
            </div>
        </div>
    </div>

    <!-- Media & Technical Settings with Instant Photo Uploader -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Slide Visual Image, URL &amp; Ordering Settings</h3>
        
        <!-- Slide Image with Instant Upload & Media Picker -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label">Slide Visual Image (16:9 Landscape) <span class="req" style="color: var(--crimson);">*</span></label>
            <div class="media-uploader-box" data-input-name="image_url">
                <input type="hidden" name="image_url" value="<?= Security::e($slide['image_url'] ?? '/assets/img/hero-living-room.webp') ?>">
                <input type="file" class="hidden-file-input" accept="image/*" style="display: none;">
                
                <div class="media-uploader-actions">
                    <button type="button" class="btn-uploader-upload">
                        <span>📤 Upload Slide Photo</span>
                    </button>
                    <button type="button" class="btn-uploader-picker">
                        <span>📁 Choose from Media Library</span>
                    </button>
                    <span style="font-size: 0.78rem; color: var(--text-muted);">Direct instant file upload or pick from media library.</span>
                </div>

                <div class="media-preview-container">
                    <?php if (!empty($slide['image_url'])): ?>
                        <img src="<?= Security::e($slide['image_url']) ?>" alt="" class="media-preview-thumb">
                        <div class="media-preview-meta">
                            <div class="media-preview-url"><?= Security::e($slide['image_url']) ?></div>
                        </div>
                        <button type="button" class="btn-media-remove">Remove</button>
                    <?php else: ?>
                        <p style="font-size: 0.78rem; color: var(--text-muted);">No slide image selected. Click Upload or Choose above.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Target CTA URL <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="cta_url" class="form-input" required value="<?= Security::e($slide['cta_url'] ?? '#portfolio') ?>" placeholder="#portfolio or /projects">
            </div>

            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($slide['sort_order'] ?? 1) ?>" min="0" max="99">
                <p class="form-help">Order in which the slide appears in the autoplay loop (1, 2, 3...).</p>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.8rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!empty($slide['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <label for="is_active" style="font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">Active slide (Visible in hero carousel)</label>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.6rem; font-size: 0.92rem;">Save &amp; Update Hero Slider &rarr;</button>
            <a href="/admin/hero" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
