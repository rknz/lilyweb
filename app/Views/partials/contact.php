<?php

/**
 * Contact section matching FINAL_DESKTOP_UI.png (3-Column Layout: Info + Dark Crimson Form + Interactive Google Maps).
 */
use Lilyweb\Core\Lang;

$isBn = Lang::isBn();

$settings = [];
try {
    $pdo = \Lilyweb\Core\Database::connect();
    $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings`");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (\Throwable $e) {}

$phonePrimary = $settings['phone_primary'] ?? '+88 01734182694';
$emailPrimary = $settings['email_primary'] ?? 'lilyinteriorsbd@gmail.com';
$addressVal = $isBn
    ? ($settings['address_bn'] ?? '৩৬ বীর উত্তম সি.আর দত্ত রোড, হাতিরপুল, ঢাকা-১২০৫')
    : ($settings['address_en'] ?? '36 Bir Uttam C.R Dutta Road, Hatirpool, Dhaka-1205, Bangladesh');
?>
<section class="section contact" id="contact" aria-labelledby="contact-title">
    <div class="site-wrapper contact-layout">
        <!-- Col 1: NAP Contact Details -->
        <div class="contact-info" data-reveal>
            <span class="section-kicker"><?= Lang::get('contact_kicker') ?></span>
            <h2 id="contact-title" class="section-h2"><?= Lang::get('contact_h2') ?></h2>
            <p class="contact-sub"><?= Lang::get('contact_sub') ?></p>

            <div class="contact-methods-list">
                <!-- Phone -->
                <div class="contact-method-item">
                    <div class="method-icon-circle" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <span class="method-label"><?= Lang::get('call_us') ?></span>
                        <a href="tel:<?= preg_replace('/\s+/', '', $phonePrimary) ?>" class="method-val"><?= \Lilyweb\Core\View::e($phonePrimary) ?></a>
                    </div>
                </div>

                <!-- Email -->
                <div class="contact-method-item">
                    <div class="method-icon-circle" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div>
                        <span class="method-label"><?= Lang::get('email_us') ?></span>
                        <a href="mailto:<?= \Lilyweb\Core\View::e($emailPrimary) ?>" class="method-val"><?= \Lilyweb\Core\View::e($emailPrimary) ?></a>
                    </div>
                </div>

                <!-- Location -->
                <div class="contact-method-item">
                    <div class="method-icon-circle" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <span class="method-label"><?= Lang::get('visit_us') ?></span>
                        <span class="method-val"><?= \Lilyweb\Core\View::e($addressVal) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 2: Deep Crimson Form Card -->
        <div class="contact-form-col" data-reveal>
            <div class="contact-form-card">
                <h3 class="form-title"><?= Lang::get('send_message_title') ?></h3>
                
                <form action="/contact" method="post" class="consultation-form">
                    <?= \Lilyweb\Core\Security::csrfField() ?>
                    <!-- Honeypot: bots fill this, humans leave it empty -->
                    <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0;" aria-hidden="true">
                        <label for="website-url">Website</label>
                        <input type="text" name="website_url" id="website-url" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="form-field">
                        <input type="text" name="name" id="c-name" placeholder="<?= Lang::get('your_name_ph') ?>" required>
                    </div>

                    <div class="form-row-dual">
                        <div class="form-field" style="margin-bottom: 0;">
                            <input type="tel" name="phone" id="c-phone" placeholder="<?= Lang::get('phone_number_ph') ?>" required>
                        </div>
                        <div class="form-field" style="margin-bottom: 0;">
                            <input type="email" name="email" id="c-email" placeholder="<?= Lang::get('email_address_ph') ?>" required>
                        </div>
                    </div>

                    <div class="form-field">
                        <textarea name="message" id="c-message" rows="4" placeholder="<?= Lang::get('your_message_ph') ?>" required></textarea>
                    </div>

                    <div class="form-submit-wrap">
                        <button type="submit" class="btn btn-pill-white">
                            <?= Lang::get('send_message_btn') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Col 3: Interactive Studio Google Map Frame -->
        <div class="contact-map-wrapper" data-reveal>
            <div class="contact-map-frame">
                <div class="map-badge-top">
                    <span class="map-pin">&#128205;</span>
                    <strong><?= $isBn ? 'লিলি ইন্টেরিয়র্স স্টুডিও' : 'Lily Interiors Studio' ?></strong>
                    <small>Dhanmondi 27, Dhaka</small>
                </div>
                <iframe 
                    title="Lily Interiors Dhaka Location Map"
                    src="https://maps.google.com/maps?q=Dhanmondi+27,+Dhaka,+Bangladesh&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    width="100%" 
                    height="100%" 
                    style="border:0; display:block;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>
