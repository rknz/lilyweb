<?php
use Lilyweb\Core\Security;
?>
<div class="card-header-flex">
    <div>
        <h2 class="card-title">About Us Section Management</h2>
        <p class="card-subtitle">Edits take effect immediately across the homepage, FAQ page, and project details sections.</p>
    </div>
</div>

<form action="/admin/about/save" method="POST">
    <?= Security::csrfField() ?>

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
                <label class="form-label">Section Kicker <span class="req">*</span></label>
                <input type="text" name="about_kicker_en" class="form-input" required value="<?= Security::e($about['about_kicker_en']) ?>" placeholder="e.g. ABOUT LILY INTERIORS">
                <p class="form-help">Small uppercase category eyebrow above the main headline.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Heading H2 <span class="req">*</span></label>
                <input type="text" name="about_heading_en" class="form-input" required value="<?= Security::e($about['about_heading_en']) ?>" placeholder="e.g. Designing Dreams, Building Reality">
                <p class="form-help">Primary bold headline of the about snapshot.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Core Narrative & Lead Paragraph <span class="req">*</span></label>
                <textarea name="about_lead_en" class="form-textarea" style="min-height: 140px;" required placeholder="Narrative copy"><?= Security::e($about['about_lead_en']) ?></textarea>
                <p class="form-help">Architectural philosophy, years of trust, and turnkey capabilities.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Action Link Text</label>
                <input type="text" name="about_link_text_en" class="form-input" value="<?= Security::e($about['about_link_text_en']) ?>" placeholder="More about our services &rarr;">
            </div>

            <div class="form-group">
                <label class="form-label">Experience Badge Label (EN)</label>
                <input type="text" name="about_exp_text_en" class="form-input" value="<?= Security::e($about['about_exp_text_en']) ?>" placeholder="Years of Architectural Craftsmanship">
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
                <label class="form-label">বাংলা কিকার <span class="opt">(Optional)</span></label>
                <input type="text" name="about_kicker_bn" class="form-input" value="<?= Security::e($about['about_kicker_bn']) ?>" placeholder="যেমন: লিলি ইন্টেরিয়র্স পরিচিতি">
                <p class="form-help">ইংরেজি কিকারের বাংলা রূপান্তর।</p>
            </div>

            <div class="form-group">
                <label class="form-label">বাংলা হেডিং H2 <span class="opt">(Optional)</span></label>
                <input type="text" name="about_heading_bn" class="form-input" value="<?= Security::e($about['about_heading_bn']) ?>" placeholder="যেমন: স্বপ্ন থেকে বাস্তবতায় রূপান্তর">
                <p class="form-help">মূল বাংলা শিরোনাম।</p>
            </div>

            <div class="form-group">
                <label class="form-label">বাংলা বিবরণ ও পরিচয় <span class="opt">(Optional)</span></label>
                <textarea name="about_lead_bn" class="form-textarea" style="min-height: 140px;" placeholder="বাংলা বিবরণ"><?= Security::e($about['about_lead_bn']) ?></textarea>
                <p class="form-help">লিলি ইন্টেরিয়র্সের মূল কাজের দর্শন ও সংক্ষিপ্ত বর্ণনা।</p>
            </div>

            <div class="form-group">
                <label class="form-label">বাংলা বাটন টেক্সট</label>
                <input type="text" name="about_link_text_bn" class="form-input" value="<?= Security::e($about['about_link_text_bn']) ?>" placeholder="আমাদের সেবা সম্পর্কে আরও জানুন &rarr;">
            </div>

            <div class="form-group">
                <label class="form-label">অভিজ্ঞতা ব্যাজ টেক্সট (বাংলা)</label>
                <input type="text" name="about_exp_text_bn" class="form-input" value="<?= Security::e($about['about_exp_text_bn']) ?>" placeholder="বছরের নিখুঁত স্থাপত্য ও নির্মাণের অভিজ্ঞতা">
            </div>
        </div>
    </div>

    <!-- Media & Layout Settings -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Images & Experience Badge Settings</h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Primary Image URL <span class="req">*</span></label>
                <input type="text" name="about_img_main" class="form-input" required value="<?= Security::e($about['about_img_main']) ?>">
                <p class="form-help">Main tall architectural frame (/assets/img/about-1.webp).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Secondary Overlapping Image URL <span class="req">*</span></label>
                <input type="text" name="about_img_secondary" class="form-input" required value="<?= Security::e($about['about_img_secondary']) ?>">
                <p class="form-help">Overlapping craftsmanship frame (/assets/img/about-2.webp).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Experience Badge Number</label>
                <input type="text" name="about_exp_years" class="form-input" value="<?= Security::e($about['about_exp_years']) ?>" placeholder="8+">
                <p class="form-help">e.g. 8+ (automatically converted to ৮+ in Bengali mode).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Action Link Target URL</label>
                <input type="text" name="about_link_url" class="form-input" value="<?= Security::e($about['about_link_url']) ?>" placeholder="#services">
                <p class="form-help">Target page anchor or URL.</p>
            </div>
        </div>

        <div style="margin-top: 1.75rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary">Save & Update Live Site →</button>
            <a href="/admin" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
