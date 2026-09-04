<?php
use Lilyweb\Core\Security;
?>
<div class="page-actions">
    <a href="/sitemap.xml" target="_blank" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.45rem;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        <span>Live sitemap.xml ↗</span>
    </a>
    <a href="/robots.txt" target="_blank" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.45rem;">
        <span>robots.txt ↗</span>
    </a>
</div>

<form action="/admin/seo/save" method="POST">
    <?= Security::csrfField() ?>

    <div class="form-grid">
        <!-- Google Search Console & Meta Verification -->
        <div class="admin-card card-master-en">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>Google & Search Engine Verification</span>
            </h3>

            <div class="form-group">
                <label class="form-label">Google Search Console Verification Tag / Code</label>
                <input type="text" name="google_site_verification" class="form-input" value="<?= Security::e($seo['google_site_verification']) ?>" placeholder="e.g. google-site-verification-token-string">
                <p class="form-help">Automatically rendered in &lt;meta name="google-site-verification" content="..."&gt; across all public pages.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Bing Webmaster Tools Verification</label>
                <input type="text" name="bing_site_verification" class="form-input" value="<?= Security::e($seo['bing_site_verification']) ?>" placeholder="Bing verification code">
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Google Analytics 4 (GA4 ID)</label>
                    <input type="text" name="google_analytics_id" class="form-input" value="<?= Security::e($seo['google_analytics_id']) ?>" placeholder="G-XXXXXXXXXX">
                </div>

                <div class="form-group">
                    <label class="form-label">Google Tag Manager (GTM ID)</label>
                    <input type="text" name="google_tag_manager_id" class="form-input" value="<?= Security::e($seo['google_tag_manager_id']) ?>" placeholder="GTM-XXXXXXX">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Default OpenGraph / Social Share Image URL</label>
                <input type="text" name="og_image_url" class="form-input" value="<?= Security::e($seo['og_image_url']) ?>" placeholder="/assets/img/hero-living-room.webp">
            </div>
        </div>

        <!-- AI Search Optimization (GEO - Generative Engine Optimization) -->
        <div class="admin-card card-specs">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2.2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                <span>AI Search Optimization (GEO for Gemini & ChatGPT)</span>
            </h3>

            <div class="form-group">
                <label class="form-label">AI Overview Synopsis (Structured Brand Summary for LLMs) <span class="req">*</span></label>
                <textarea name="ai_company_synopsis" class="form-textarea" style="min-height: 110px;" required><?= Security::e($seo['ai_company_synopsis']) ?></textarea>
                <p class="form-help">Structured knowledge injected into Schema.org JSON-LD & meta tags for AI search engines like Google Gemini, ChatGPT Search & Perplexity.</p>
            </div>

            <div class="form-group">
                <label class="form-label">GEO Target Geographic Locations</label>
                <input type="text" name="ai_target_locations" class="form-input" value="<?= Security::e($seo['ai_target_locations']) ?>" placeholder="Gulshan, Banani, Dhanmondi, Uttara, Bashundhara, Dhaka">
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Schema Price Range</label>
                    <input type="text" name="schema_price_range" class="form-input" value="<?= Security::e($seo['schema_price_range']) ?>" placeholder="$$$">
                </div>

                <div class="form-group">
                    <label class="form-label">Schema Aggregate Rating</label>
                    <input type="text" name="schema_rating_val" class="form-input" value="<?= Security::e($seo['schema_rating_val']) ?>" placeholder="4.9">
                </div>

                <div class="form-group">
                    <label class="form-label">Total Verified Reviews Count</label>
                    <input type="text" name="schema_review_count" class="form-input" value="<?= Security::e($seo['schema_review_count']) ?>" placeholder="150">
                </div>
            </div>
        </div>
    </div>

    <!-- Master Meta Titles & Descriptions -->
    <div class="admin-card card-specs" style="margin-top: 1.85rem;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Master Search Engine Title & Description Tags</h3>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Homepage Meta Title (EN) <span class="req">*</span></label>
                <input type="text" name="seo_meta_title_en" class="form-input" required value="<?= Security::e($seo['seo_meta_title_en']) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Homepage Meta Title (বাংলা)</label>
                <input type="text" name="seo_meta_title_bn" class="form-input" value="<?= Security::e($seo['seo_meta_title_bn']) ?>">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Homepage Meta Description (EN) <span class="req">*</span></label>
                <textarea name="seo_meta_desc_en" class="form-textarea" required><?= Security::e($seo['seo_meta_desc_en']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Homepage Meta Description (বাংলা)</label>
                <textarea name="seo_meta_desc_bn" class="form-textarea"><?= Security::e($seo['seo_meta_desc_bn']) ?></textarea>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Custom Header Scripts (&lt;head&gt; injections)</label>
                <textarea name="custom_head_scripts" class="form-textarea" style="font-family: monospace; font-size: 0.82rem;" placeholder="<!-- Custom pixel or head code -->"><?= Security::e($seo['custom_head_scripts']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Custom Body Scripts (Closing &lt;/body&gt; injections)</label>
                <textarea name="custom_body_scripts" class="form-textarea" style="font-family: monospace; font-size: 0.82rem;" placeholder="<!-- Custom chat widget or body code -->"><?= Security::e($seo['custom_body_scripts']) ?></textarea>
            </div>
        </div>

        <div style="margin-top: 1.75rem;">
            <button type="submit" class="btn-primary">Save SEO & Search Engine Optimization Settings →</button>
        </div>
    </div>
</form>
