<?php
use Lilyweb\Core\Security;
?>

<div class="form-grid">
    <!-- NAP Contact & Company Info -->
    <div class="admin-card card-master-en">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <span>Company Name, Phone & NAP Details</span>
        </h3>

        <form action="/admin/settings/save" method="POST">
            <?= Security::csrfField() ?>

            <div class="form-group">
                <label class="form-label">Brand / Company Name <span class="req">*</span></label>
                <input type="text" name="site_name" class="form-input" required value="<?= Security::e($settings['site_name'] ?? 'Lily Interiors') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Official Tagline</label>
                <input type="text" name="site_tagline" class="form-input" value="<?= Security::e($settings['site_tagline'] ?? 'Architectural Interior Design & Turnkey Solutions') ?>">
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Primary Hotline <span class="req">*</span></label>
                    <input type="text" name="phone_primary" class="form-input" required value="<?= Security::e($settings['phone_primary'] ?? '+88 01734182694') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Secondary Hotline</label>
                    <input type="text" name="phone_secondary" class="form-input" value="<?= Security::e($settings['phone_secondary'] ?? '+88 02 44612456') ?>">
                </div>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Official Email <span class="req">*</span></label>
                    <input type="email" name="email_primary" class="form-input" required value="<?= Security::e($settings['email_primary'] ?? 'lilyinteriorsbd@gmail.com') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Support / Inquiry Email</label>
                    <input type="email" name="email_support" class="form-input" value="<?= Security::e($settings['email_support'] ?? 'info@lilyinteriorsbd.com') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Official Office Address (EN) <span class="req">*</span></label>
                <input type="text" name="address_en" class="form-input" required value="<?= Security::e($settings['address_en'] ?? '36 Bir Uttam C.R Dutta Road, Hatirpool, Dhaka-1205, Bangladesh') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">অফিস ঠিকানা (বাংলা)</label>
                <input type="text" name="address_bn" class="form-input" value="<?= Security::e($settings['address_bn'] ?? '৩৬ বীর উত্তম সি.আর দত্ত রোড, হাতিরপুল, ঢাকা-১২০৫') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Business Working Hours (EN)</label>
                <input type="text" name="business_hours_en" class="form-input" value="<?= Security::e($settings['business_hours_en'] ?? 'Saturday - Thursday: 09:00 AM - 7:00 PM') ?>">
            </div>

            <div style="margin-top: 1.75rem;">
                <button type="submit" class="btn-primary">Save NAP Details →</button>
            </div>
        </form>
    </div>

    <!-- WhatsApp & Social Media -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2.2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            <span>WhatsApp & Social Media Integrations</span>
        </h3>

        <form action="/admin/settings/save" method="POST">
            <?= Security::csrfField() ?>

            <div class="form-group">
                <label class="form-label">WhatsApp Number (with country code) <span class="req">*</span></label>
                <input type="text" name="whatsapp_number" class="form-input" required value="<?= Security::e($settings['whatsapp_number'] ?? '8801734182694') ?>" placeholder="8801734182694">
                <p class="form-help">Digits only without plus sign for direct API links.</p>
            </div>

            <div class="form-group">
                <label class="form-label">WhatsApp Initial Greeting Message</label>
                <input type="text" name="whatsapp_message" class="form-input" value="<?= Security::e($settings['whatsapp_message'] ?? 'Hello Lily Interiors, I would like to schedule an interior design consultation.') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Facebook Page URL</label>
                <input type="text" name="social_facebook" class="form-input" value="<?= Security::e($settings['social_facebook'] ?? 'https://www.facebook.com/LilyInteriorsbd/') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Instagram Profile URL</label>
                <input type="text" name="social_instagram" class="form-input" value="<?= Security::e($settings['social_instagram'] ?? 'https://www.instagram.com/lilyinteriors/') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">YouTube Channel URL</label>
                <input type="text" name="social_youtube" class="form-input" value="<?= Security::e($settings['social_youtube'] ?? 'https://www.youtube.com/channel/UCkLkpvteAlhSFUkgzQy8p_g/videos') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Pinterest Profile URL</label>
                <input type="text" name="social_pinterest" class="form-input" value="<?= Security::e($settings['social_pinterest'] ?? 'https://pinterest.com/lilyinteriors') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">LinkedIn Profile URL</label>
                <input type="text" name="social_linkedin" class="form-input" value="<?= Security::e($settings['social_linkedin'] ?? '') ?>" placeholder="https://www.linkedin.com/company/...">
            </div>

            <div style="margin-top: 1.75rem;">
                <button type="submit" class="btn-primary">Save Social Media Links →</button>
            </div>
        </form>
    </div>
</div>

<!-- Owner Authentication & Security Credentials -->
<div class="admin-card card-specs" style="margin-top: 1.85rem;">
    <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        <span>Owner Account Security & Password</span>
    </h3>

    <form action="/admin/settings/owner" method="POST">
        <?= Security::csrfField() ?>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Display Name</label>
                <input type="text" name="display_name" class="form-input" required value="<?= Security::e($owner['display_name'] ?? 'Lily Interiors Owner') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Login Username</label>
                <input type="text" name="username" class="form-input" required value="<?= Security::e($owner['username'] ?? 'admin') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Owner Email</label>
                <input type="email" name="email" class="form-input" required value="<?= Security::e($owner['email'] ?? 'owner@lilyinteriorsbd.com') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Change Password <span class="opt">(Leave blank to keep current)</span></label>
                <input type="password" name="password" class="form-input" placeholder="Enter new strong password">
            </div>
        </div>

        <div style="margin-top: 1.75rem;">
            <button type="submit" class="btn-primary">Update Owner Security Credentials →</button>
        </div>
    </form>
</div>
