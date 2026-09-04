<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/categories" class="btn-back-highlight">
        <span>&larr; Back to Categories List</span>
    </a>
</div>

<form action="/admin/categories/save" method="POST">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($category['id'] ?? 0) ?>">

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
                <label class="form-label">Category Name (EN) <span class="req">*</span></label>
                <input type="text" name="name_en" class="form-input" required value="<?= Security::e($category['name_en']) ?>" placeholder="e.g. Living Room">
                <p class="form-help">Category pill label displayed on portfolio filters.</p>
            </div>

            <div class="form-group">
                <label class="form-label">URL Filter Slug</label>
                <input type="text" name="slug" class="form-input" value="<?= Security::e($category['slug']) ?>" placeholder="e.g. living-room">
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
                <label class="form-label">ক্যাটেগরি নাম (বাংলা) <span class="opt">(Optional)</span></label>
                <input type="text" name="name_bn" class="form-input" value="<?= Security::e($category['name_bn'] ?? '') ?>" placeholder="যেমন: লিভিং রুম">
                <p class="form-help">বাংলা ফিল্টারের নাম।</p>
            </div>
        </div>
    </div>

    <!-- Display & Ordering Settings -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Display & Ordering Settings</h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($category['sort_order'] ?? 1) ?>" min="0" max="99">
                <p class="form-help">Pill order index in filter bar.</p>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.8rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!empty($category['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <label for="is_active" style="font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">Active category (Show in portfolio filter bar)</label>
            </div>
        </div>

        <div style="margin-top: 1.75rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary">Save Category →</button>
            <a href="/admin/categories" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
