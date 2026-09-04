<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/services" class="btn-back-highlight">
        <span>&larr; Back to Services List</span>
    </a>
</div>

<div class="card-header-flex">
    <div>
        <h2 class="card-title"><?= $isEdit ? 'Edit Service: ' . Security::e($service['title_en']) : 'Add New Service' ?></h2>
        <p style="color: var(--text-muted); font-size: 0.86rem; font-weight: 600; margin-top: 0.25rem;">
            English is mandatory. Bangla is optional — if left blank, the frontend automatically falls back to clean auto-translation.
        </p>
    </div>
</div>

<form action="/admin/services/save" method="POST">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($service['id'] ?? 0) ?>">

    <div class="form-grid">
        <!-- English Column (Master) -->
        <div class="admin-card">
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.35rem; display: flex; align-items: center; gap: 0.6rem;">
                <span>🇬🇧 English Content</span>
                <span style="font-size: 0.74rem; color: #15803D; font-weight: 800; background: #DCFCE7; border: 1px solid #86EFAC; padding: 0.18rem 0.55rem; border-radius: 9999px;">Required Master</span>
            </h3>

            <div class="form-group">
                <label class="form-label">Service Title (EN) <span class="req">*</span></label>
                <input type="text" name="title_en" class="form-input" required value="<?= Security::e($service['title_en']) ?>" placeholder="e.g. Interior Design">
            </div>

            <div class="form-group">
                <label class="form-label">Service Badge Pill (EN) <span class="req">*</span></label>
                <input type="text" name="tag_badge_en" class="form-input" required value="<?= Security::e($service['tag_badge_en']) ?>" placeholder="e.g. Bespoke 3D">
            </div>

            <div class="form-group">
                <label class="form-label">Summary / Short Card Description (EN) <span class="req">*</span></label>
                <textarea name="summary_en" class="form-textarea" required placeholder="Card description"><?= Security::e($service['summary_en']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Full Detailed Description (EN)</label>
                <textarea name="description_en" class="form-textarea" style="min-height: 120px;" placeholder="Full service details for inner pages"><?= Security::e($service['description_en'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Bengali Column (Optional Fallback) -->
        <div class="admin-card">
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.35rem; display: flex; align-items: center; gap: 0.6rem;">
                <span>🇧🇩 বাংলা অনুবাদ (Bengali)</span>
                <span style="font-size: 0.74rem; color: var(--text-muted); font-weight: 700; background: var(--bg-card-alt); border: 1px solid var(--border-color); padding: 0.18rem 0.55rem; border-radius: 9999px;">ঐচ্ছিক / Optional Fallback</span>
            </h3>

            <div class="form-group">
                <label class="form-label">সার্ভিস টাইটেল (বাংলা) <span class="opt">(Optional)</span></label>
                <input type="text" name="title_bn" class="form-input" value="<?= Security::e($service['title_bn'] ?? '') ?>" placeholder="যেমন: ইন্টেরিয়র ডিজাইন">
            </div>

            <div class="form-group">
                <label class="form-label">ব্যাজ পিল (বাংলা) <span class="opt">(Optional)</span></label>
                <input type="text" name="tag_badge_bn" class="form-input" value="<?= Security::e($service['tag_badge_bn'] ?? '') ?>" placeholder="যেমন: আর্কিটেকচারাল">
            </div>

            <div class="form-group">
                <label class="form-label">কার্ড বিবরণ (বাংলা) <span class="opt">(Optional)</span></label>
                <textarea name="summary_bn" class="form-textarea" placeholder="বাংলা বিবরণ"><?= Security::e($service['summary_bn'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">বিস্তারিত বিবরণ (বাংলা)</label>
                <textarea name="description_bn" class="form-textarea" style="min-height: 120px;" placeholder="বাংলা সম্পূর্ণ বিবরণ"><?= Security::e($service['description_bn'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Technical & Icon Settings -->
    <div class="admin-card">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.35rem;">Technical & SVG Icon Settings</h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">URL Slug</label>
                <input type="text" name="slug" class="form-input" value="<?= Security::e($service['slug']) ?>" placeholder="e.g. interior-design">
            </div>

            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($service['sort_order'] ?? 1) ?>" min="0" max="99">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">SVG Icon Code <span class="req">*</span></label>
            <textarea name="icon_svg" class="form-textarea" style="font-family: monospace; font-size: 0.85rem; font-weight: 600;" required><?= Security::e($service['icon_svg']) ?></textarea>
            <p class="form-help">Clean SVG string with viewBox="0 0 24 24" and stroke="#C8102E".</p>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.25rem;">
            <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!empty($service['is_active'])) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--crimson);">
            <label for="is_active" style="font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">Active service (Visible on live homepage and services directory)</label>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary" style="padding: 0.85rem 2rem; font-size: 0.92rem;">Save & Update Live Site →</button>
            <a href="/admin/services" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
