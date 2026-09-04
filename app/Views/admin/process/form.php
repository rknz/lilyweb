<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/process" class="btn-back-highlight">
        <span>&larr; Back to Process List</span>
    </a>
</div>

<div class="card-header-flex">
    <div>
        <h2 class="card-title"><?= $isEdit ? 'Edit Process Step #' . Security::e($step['step_number']) : 'Add New Process Step' ?></h2>
        <p class="card-subtitle">English is mandatory. Bangla is optional — if left blank, the frontend automatically falls back to clean auto-translation.</p>
    </div>
</div>

<form action="/admin/process/save" method="POST">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($step['id'] ?? 0) ?>">

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
                <label class="form-label">Step Number (e.g. 01, 02) <span class="req">*</span></label>
                <input type="text" name="step_number" class="form-input" required value="<?= Security::e($step['step_number']) ?>" placeholder="01">
                <p class="form-help">Two-digit step label shown on the process line.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Step Title (EN) <span class="req">*</span></label>
                <input type="text" name="title_en" class="form-input" required value="<?= Security::e($step['title_en']) ?>" placeholder="e.g. Consultation">
                <p class="form-help">Primary step title.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Step Description (EN) <span class="req">*</span></label>
                <textarea name="description_en" class="form-textarea" style="min-height: 120px;" required placeholder="Step description copy"><?= Security::e($step['description_en']) ?></textarea>
                <p class="form-help">Concise summary of this workflow phase.</p>
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
                <label class="form-label">ধাপের শিরোনাম (বাংলা) <span class="opt">(Optional)</span></label>
                <input type="text" name="title_bn" class="form-input" value="<?= Security::e($step['title_bn'] ?? '') ?>" placeholder="যেমন: কনসালটেশন">
                <p class="form-help">বাংলা শিরোনাম।</p>
            </div>

            <div class="form-group">
                <label class="form-label">ধাপের বিবরণ (বাংলা) <span class="opt">(Optional)</span></label>
                <textarea name="description_bn" class="form-textarea" style="min-height: 120px;" placeholder="বাংলা বিবরণ"><?= Security::e($step['description_bn'] ?? '') ?></textarea>
                <p class="form-help">বাংলায় কাজের সংক্ষিপ্ত বিবরণ।</p>
            </div>
        </div>
    </div>

    <!-- Technical & Icon Settings -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Technical & SVG Icon Settings</h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($step['sort_order'] ?? 1) ?>" min="0" max="99">
                <p class="form-help">Sequence position (1 to 4).</p>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.8rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!empty($step['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <label for="is_active" style="font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">Active step (Visible on homepage timeline)</label>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">SVG Icon Code <span class="req">*</span></label>
            <textarea name="icon_svg" class="form-textarea" style="font-family: monospace; font-size: 0.85rem; font-weight: 600;" required><?= Security::e($step['icon_svg']) ?></textarea>
            <p class="form-help">Clean SVG string with viewBox="0 0 24 24" and stroke="#C8102E".</p>
        </div>

        <div style="margin-top: 1.75rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary">Save & Update Live Site →</button>
            <a href="/admin/process" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
