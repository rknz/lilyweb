<?php
use Lilyweb\Core\Lang;
$isBn = Lang::isBn();
?>
<!-- Performance & Cookie Consent Banner -->
<div id="cookie-notice" class="cookie-notice-banner" role="alert" aria-live="polite">
    <div class="cookie-notice-content">
        <div class="cookie-icon-wrap" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/><path d="M8.5 8.5v.01"/><path d="M16 15.5v.01"/><path d="M12 12v.01"/><path d="M11 17v.01"/><path d="M7 13v.01"/></svg>
        </div>
        <div class="cookie-text-block">
            <strong><?= $isBn ? 'ওয়েবসাইট পারফরম্যান্স ও কুকিজ' : 'Faster Browsing & Performance' ?></strong>
            <p>
                <?= $isBn 
                    ? 'আপনার ব্রাউজিং অভিজ্ঞতা উন্নত করতে এবং ওয়েবসাইট দ্রুত লোড হওয়ার জন্য আমরা প্রয়োজনীয় ব্রাউজার কুকিজ সংরক্ষণ করি।' 
                    : 'We store essential browser cookies and preferences to ensure lightning-fast page loading and seamless browsing performance.' ?>
            </p>
        </div>
        <div class="cookie-actions">
            <button type="button" id="cookie-accept-btn" class="btn btn-pill-crimson" style="padding: 0.5rem 1.25rem; font-size: 0.82rem;">
                <?= $isBn ? 'স্বীকার করুন' : 'Accept & Continue' ?>
            </button>
            <a href="/privacy-policy" style="color: var(--charcoal-muted); font-size: 0.78rem; text-decoration: underline; white-space: nowrap;">
                <?= $isBn ? 'বিস্তারিত' : 'Learn More' ?>
            </a>
            <button type="button" id="cookie-close-btn" class="cookie-close-btn" aria-label="Close cookie alert">✕</button>
        </div>
    </div>
</div>
