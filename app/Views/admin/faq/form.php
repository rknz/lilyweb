<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/faq" class="btn-back-highlight">
        <span>&larr; Back to FAQ List</span>
    </a>
</div>

<div class="card-header-flex">
    <div>
        <h2 class="card-title"><?= $isEdit ? 'Edit FAQ Item' : 'Add New FAQ Item' ?></h2>
        <p class="card-subtitle">English is mandatory. Bangla is optional — if left blank, the frontend automatically falls back to clean auto-translation.</p>
    </div>
</div>

<form action="/admin/faq/save" method="POST">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($faq['id'] ?? 0) ?>">

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
                <label class="form-label">Question (EN) <span class="req">*</span></label>
                <input type="text" name="question_en" class="form-input" required value="<?= Security::e($faq['question_en']) ?>" placeholder="e.g. How does the initial design consultation work?">
                <p class="form-help">Clear client question phrase.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Answer (EN) <span class="req">*</span></label>
                <textarea name="answer_en" class="form-textarea" style="min-height: 140px;" required placeholder="Detailed answer..."><?= Security::e($faq['answer_en']) ?></textarea>
                <p class="form-help">Comprehensive answer for clients and search engines.</p>
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
                <label class="form-label">প্রশ্ন (বাংলা) <span class="opt">(Optional)</span></label>
                <input type="text" name="question_bn" class="form-input" value="<?= Security::e($faq['question_bn'] ?? '') ?>" placeholder="যেমন: প্রাথমিক ডিজাইন কনসালটেশন কীভাবে কাজ করে?">
                <p class="form-help">বাংলা প্রশ্ন।</p>
            </div>

            <div class="form-group">
                <label class="form-label">উত্তর (বাংলা) <span class="opt">(Optional)</span></label>
                <textarea name="answer_bn" class="form-textarea" style="min-height: 140px;" placeholder="বাংলা উত্তর..."><?= Security::e($faq['answer_bn'] ?? '') ?></textarea>
                <p class="form-help">বাংলায় বিস্তারিত উত্তর।</p>
            </div>
        </div>
    </div>

    <!-- Category & Display Settings -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Category & SEO Settings</h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-input">
                    <option value="general" <?= ($faq['category'] ?? '') === 'general' ? 'selected' : '' ?>>General</option>
                    <option value="services" <?= ($faq['category'] ?? '') === 'services' ? 'selected' : '' ?>>Services & Design</option>
                    <option value="costing" <?= ($faq['category'] ?? '') === 'costing' ? 'selected' : '' ?>>Costing & Budget</option>
                    <option value="execution" <?= ($faq['category'] ?? '') === 'execution' ? 'selected' : '' ?>>Timeline & Execution</option>
                    <option value="materials" <?= ($faq['category'] ?? '') === 'materials' ? 'selected' : '' ?>>Materials & Warranty</option>
                </select>
                <p class="form-help">Topic classification for FAQ groups.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($faq['sort_order'] ?? 1) ?>" min="0" max="99">
                <p class="form-help">Order in accordion list.</p>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.8rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!empty($faq['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <label for="is_active" style="font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">Active FAQ (Include on FAQ accordion & Schema.org JSON-LD)</label>
            </div>
        </div>

        <div style="margin-top: 1.75rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary">Save & Update Live Site →</button>
            <a href="/admin/faq" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
