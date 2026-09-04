<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/testimonials" class="btn-back-highlight">
        <span>&larr; Back to Reviews List</span>
    </a>
</div>

<div class="card-header-flex">
    <div>
        <h2 class="card-title"><?= $isEdit ? 'Edit Review: ' . Security::e($testimonial['author_name']) : 'Add New Client Review' ?></h2>
        <p class="card-subtitle">English is mandatory. Bangla is optional — if left blank, the frontend automatically falls back to clean auto-translation.</p>
    </div>
</div>

<form action="/admin/testimonials/save" method="POST">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($testimonial['id'] ?? 0) ?>">

    <div class="form-grid">
        <!-- English Column (Master) -->
        <div class="admin-card card-master-en">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.4rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.5rem;">
                    <span>🇬🇧 English Content</span>
                </h3>
                <span class="badge-pill-blue">Required Master</span>
            </div>

            <div class="form-group">
                <label class="form-label">Client Name <span class="req">*</span></label>
                <input type="text" name="author_name" class="form-input" required value="<?= Security::e($testimonial['author_name']) ?>" placeholder="e.g. Engr. Mahmudur Rahman">
                <p class="form-help">Client or property owner name.</p>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Client Role (EN)</label>
                    <input type="text" name="author_role_en" class="form-input" value="<?= Security::e($testimonial['author_role_en'] ?? '') ?>" placeholder="e.g. Duplex Homeowner">
                </div>
                <div class="form-group">
                    <label class="form-label">Location (EN)</label>
                    <input type="text" name="author_location_en" class="form-input" value="<?= Security::e($testimonial['author_location_en'] ?? '') ?>" placeholder="e.g. Gulshan 2, Dhaka">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Project Tag (EN)</label>
                <input type="text" name="project_tag_en" class="form-input" value="<?= Security::e($testimonial['project_tag_en'] ?? '') ?>" placeholder="e.g. Gulshan 2 Duplex • 3,200 sqft">
            </div>

            <div class="form-group">
                <label class="form-label">Client Review Quote (EN) <span class="req">*</span></label>
                <textarea name="content_en" class="form-textarea" style="min-height: 120px;" required placeholder="The testimonial text..."><?= Security::e($testimonial['content_en']) ?></textarea>
                <p class="form-help">Client's authentic feedback on design execution and handover.</p>
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

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">ক্লায়েন্ট ভূমিকা (বাংলা) <span class="opt">(Optional)</span></label>
                    <input type="text" name="author_role_bn" class="form-input" value="<?= Security::e($testimonial['author_role_bn'] ?? '') ?>" placeholder="যেমন: ডুপ্লেক্স হোমওনার">
                </div>
                <div class="form-group">
                    <label class="form-label">লোকেশন (বাংলা) <span class="opt">(Optional)</span></label>
                    <input type="text" name="author_location_bn" class="form-input" value="<?= Security::e($testimonial['author_location_bn'] ?? '') ?>" placeholder="যেমন: গুলশান ২, ঢাকা">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">প্রজেক্ট ট্যাগ (বাংলা) <span class="opt">(Optional)</span></label>
                <input type="text" name="project_tag_bn" class="form-input" value="<?= Security::e($testimonial['project_tag_bn'] ?? '') ?>" placeholder="যেমন: গুলশান ২ ডুপ্লেক্স • ৩,২০০ বর্গফুট">
            </div>

            <div class="form-group">
                <label class="form-label">রিভিউ মন্তব্য (বাংলা) <span class="opt">(Optional)</span></label>
                <textarea name="content_bn" class="form-textarea" style="min-height: 120px;" placeholder="বাংলা প্রশংসাপত্র"><?= Security::e($testimonial['content_bn'] ?? '') ?></textarea>
                <p class="form-help">বাংলায় প্রশংসামূলক মন্তব্য।</p>
            </div>
        </div>
    </div>

    <!-- Rating & Display Settings -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Rating & Display Settings</h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Initials (Leave blank for auto)</label>
                <input type="text" name="author_initials" class="form-input" value="<?= Security::e($testimonial['author_initials'] ?? '') ?>" placeholder="MR">
                <p class="form-help">Avatar initials badge (e.g. MR, FK).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Rating Score (1.0 to 5.0)</label>
                <input type="number" step="0.1" min="1.0" max="5.0" name="rating_score" class="form-input" value="<?= (float) ($testimonial['rating_score'] ?? 5.0) ?>">
                <p class="form-help">Stars rating out of 5.0.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($testimonial['sort_order'] ?? 1) ?>" min="0" max="99">
                <p class="form-help">Carousel order index.</p>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.8rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!empty($testimonial['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <label for="is_active" style="font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">Active review (Visible on live carousel)</label>
            </div>
        </div>

        <div style="margin-top: 1.75rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary">Save & Update Live Site →</button>
            <a href="/admin/testimonials" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
