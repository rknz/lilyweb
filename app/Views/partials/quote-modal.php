<?php

/**
 * Quote / Consultation Request Modal.
 *
 * Triggered by the "Get Quote" button in the floating actions or hero section.
 * Posts to the existing /contact route with CSRF protection.
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();
?>
<div id="quote-modal" class="quote-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="quote-modal-title" hidden>
    <div class="quote-modal-card">
        <button type="button" class="quote-modal-close" id="quote-modal-close" aria-label="Close">&times;</button>
        <h3 id="quote-modal-title" class="quote-modal-heading">
            <?= $isBn ? 'ফ্রি কনসালটেশন অনুরোধ করুন' : 'Request a Free Consultation' ?>
        </h3>
        <p class="quote-modal-sub">
            <?= $isBn ? 'আমাদের সিনিয়র আর্কিটেক্ট দ্রুত যোগাযোগ করবেন।' : 'Our senior architect will reach out to you shortly.' ?>
        </p>

        <form action="/contact" method="post" class="quote-modal-form">
            <?= Security::csrfField() ?>
            <div style="position:absolute;left:-9999px;top:-9999px;opacity:0;" aria-hidden="true">
                <label for="q-website">Website</label>
                <input type="text" name="website_url" id="q-website" tabindex="-1" autocomplete="off">
            </div>
            <div class="form-field">
                <input type="text" name="name" id="q-name" placeholder="<?= $isBn ? 'আপনার পুরো নাম' : 'Your Full Name' ?>" required>
            </div>
            <div class="form-field">
                <input type="tel" name="phone" id="q-phone" placeholder="<?= $isBn ? 'মোবাইল নম্বর' : 'Phone Number' ?>" required>
            </div>
            <div class="form-field">
                <input type="email" name="email" id="q-email" placeholder="<?= $isBn ? 'ইমেইল (ঐচ্ছিক)' : 'Email (optional)' ?>">
            </div>
            <div class="form-field">
                <select name="service" id="q-service">
                    <option value=""><?= $isBn ? 'সেবা নির্বাচন করুন' : 'Select a Service' ?></option>
                    <option value="Interior Design Consultation"><?= $isBn ? 'ইন্টেরিয়র ডিজাইন কনসালটেশন' : 'Interior Design Consultation' ?></option>
                    <option value="Residential Project"><?= $isBn ? 'রেসিডেন্সিয়াল প্রজেক্ট' : 'Residential Project' ?></option>
                    <option value="Commercial Project"><?= $isBn ? 'কমার্শিয়াল প্রজেক্ট' : 'Commercial Project' ?></option>
                    <option value="Full Home Interior"><?= $isBn ? 'সম্পূর্ণ বাসা ইন্টেরিয়র' : 'Full Home Interior' ?></option>
                    <option value="Kitchen Design"><?= $isBn ? 'কিচেন ডিজাইন' : 'Kitchen Design' ?></option>
                </select>
            </div>
            <div class="form-field">
                <textarea name="message" id="q-message" rows="3" placeholder="<?= $isBn ? 'আপনার প্রকল্পের সংক্ষিপ্ত বিবরণ' : 'Brief description of your project' ?>"></textarea>
            </div>
            <div class="form-submit-wrap">
                <button type="submit" class="btn btn-pill-crimson">
                    <?= $isBn ? 'এখনই জমা দিন' : 'Submit Now' ?>
                </button>
            </div>
        </form>
    </div>
</div>
