<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/stats" class="btn-back-highlight">
        <span>&larr; Back to Stats List</span>
    </a>
</div>

<div class="card-header-flex">
    <div>
        <h2 class="card-title"><?= $isEdit ? 'Edit Stat Card: ' . Security::e($stat['label_en']) : 'Add New Stat Card' ?></h2>
        <p class="card-subtitle">English is mandatory. Bangla is optional — if left blank, the frontend automatically falls back to clean auto-translation.</p>
    </div>
</div>

<form action="/admin/stats/save" method="POST">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($stat['id'] ?? 0) ?>">

    <div class="form-grid">
        <!-- English Column (Master) -->
        <div class="admin-card card-master-en">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.4rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.5rem;">
                    <span>🇬🇧 English Content</span>
                </h3>
                <span class="badge-pill-blue">Required Master</span>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Number Target Value <span class="req">*</span></label>
                    <input type="number" name="value_number" class="form-input" required value="<?= (int) ($stat['value_number'] ?? 100) ?>" min="0" max="99999">
                    <p class="form-help">Animated counting target (e.g. 250, 180, 8, 15, 100).</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Suffix Symbol</label>
                    <input type="text" name="suffix" class="form-input" value="<?= Security::e($stat['suffix'] ?? '+') ?>" placeholder="+ or %">
                    <p class="form-help">Appended symbol (e.g. + or %).</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Stat Label (EN) <span class="req">*</span></label>
                <input type="text" name="label_en" class="form-input" required value="<?= Security::e($stat['label_en']) ?>" placeholder="e.g. Projects Completed">
                <p class="form-help">Descriptive label below the animated number.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Internal Stat Key</label>
                <input type="text" name="stat_key" class="form-input" value="<?= Security::e($stat['stat_key'] ?? 'stat_1') ?>" placeholder="e.g. stat_1">
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
                <label class="form-label">স্ট্যাট লেবেল (বাংলা) <span class="opt">(Optional)</span></label>
                <input type="text" name="label_bn" class="form-input" value="<?= Security::e($stat['label_bn'] ?? '') ?>" placeholder="যেমন: বাস্তবায়িত প্রজেক্ট">
                <p class="form-help">বাংলা মোডে সংখ্যাগুলো স্বয়ংক্রিয়ভাবে বাংলায় (যেমন: ২৫০+) অ্যানিমেট হবে।</p>
            </div>
        </div>
    </div>

    <!-- Technical & Icon Settings -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Technical & SVG Icon Settings</h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($stat['sort_order'] ?? 1) ?>" min="0" max="99">
                <p class="form-help">Sequence order in the 4-column strip.</p>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.8rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!empty($stat['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <label for="is_active" style="font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">Active card (Visible on live stats strips)</label>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">SVG Icon Code <span class="req">*</span></label>
            <textarea name="icon_svg" class="form-textarea" style="font-family: monospace; font-size: 0.85rem; font-weight: 600;" required><?= Security::e($stat['icon_svg']) ?></textarea>
            <p class="form-help">Clean SVG string with viewBox="0 0 24 24" and stroke="#C8102E".</p>
        </div>

        <div style="margin-top: 1.75rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary">Save & Update Live Site →</button>
            <a href="/admin/stats" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
