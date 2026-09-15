<?php

/**
 * Header component matching updated menu structure & mobile drawer specification:
 * 1. Home (/)
 * 2. Services (/#services)
 * 3. Portfolio (/#portfolio - Homepage portfolio section)
 * 4. Projects (/projects - Full projects directory)
 * 5. FAQ (/faq - Dedicated FAQ page)
 * Right Action: Get Free Consultation (/#contact)
 * 
 * Mobile Drawer:
 * - Centered Logo at the top
 * - Centered Bilingual Switch + Dark/Light Mode switch right beneath logo
 * - Centered Navigation Menu links
 * - Clean aligned icons & contact blocks
 */
use Lilyweb\Core\View;
use Lilyweb\Core\Config;
use Lilyweb\Core\Lang;

$siteName = (string) Config::get('app.name', 'Lily Interiors');
$active = $active ?? 'home';
$isBn = Lang::isBn();

$settings = [];
try {
    $pdo = \Lilyweb\Core\Database::connect();
    $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings`");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (\Throwable $e) {}

$phonePrimary = $settings['phone_primary'] ?? '+88 01734182694';
$emailPrimary = $settings['email_primary'] ?? 'lilyinteriorsbd@gmail.com';
$addressPrimary = $isBn 
    ? ($settings['address_bn'] ?? '৩৬ বীর উত্তম সি.আর দত্ত রোড, হাতিরপুল, ঢাকা-১২০৫') 
    : ($settings['address_en'] ?? '36 Bir Uttam C.R Dutta Road, Hatirpool, Dhaka-1205, Bangladesh');
$waNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801734182694');
$fbUrl = $settings['social_facebook'] ?? 'https://www.facebook.com/LilyInteriorsbd/';
$igUrl = $settings['social_instagram'] ?? 'https://www.instagram.com/lilyinteriors/';
$ytUrl = $settings['social_youtube'] ?? 'https://www.youtube.com/channel/UCkLkpvteAlhSFUkgzQy8p_g/videos';
$pinterestUrl = $settings['social_pinterest'] ?? 'https://www.pinterest.com/';

$navLinks = [
    'home' => [
        'label' => $isBn ? 'হোম' : 'Home',
        'href' => '/',
    ],
    'services' => [
        'label' => $isBn ? 'সার্ভিসসমূহ' : 'Services',
        'href' => '/#services',
    ],
    'portfolio' => [
        'label' => $isBn ? 'পোর্টফোলিও' : 'Portfolio',
        'href' => '/#portfolio',
    ],
    'projects' => [
        'label' => $isBn ? 'প্রজেক্টস' : 'Projects',
        'href' => '/projects',
    ],
    'faq' => [
        'label' => $isBn ? 'প্রশ্নোত্তর' : 'FAQ',
        'href' => '/faq',
    ],
    'about' => [
        'label' => $isBn ? 'আমাদের সম্পর্কে' : 'About',
        'href' => '/about',
    ],
];
?>
<header class="site-header">
    <!-- Top Contact, Language & Theme Utility Strip (Full-Width Desktop) -->
    <div class="top-strip">
        <div class="site-wrapper top-strip-inner">
            <!-- Left Contacts -->
            <div class="top-contacts">
                <a href="tel:<?= preg_replace('/\s+/', '', $phonePrimary) ?>" class="top-item">
                    <span class="icon-circle">&#9742;</span>
                    <span><?= View::e($phonePrimary) ?></span>
                </a>
                <a href="mailto:<?= View::e($emailPrimary) ?>" class="top-item">
                    <span class="icon-circle">&#9993;</span>
                    <span><?= View::e($emailPrimary) ?></span>
                </a>
                <span class="top-item top-location" title="<?= View::e($addressPrimary) ?>">
                    <span class="icon-circle">&#128205;</span>
                    <span><?= View::e($addressPrimary) ?></span>
                </span>
            </div>

            <!-- Right Controls: Language Switcher + Theme Toggle + Socials -->
            <div class="top-controls-wrap">
                <div class="header-toggles-cluster">
                    <!-- Simple Professional Language Toggle Button -->
                    <a href="?lang=<?= $isBn ? 'en' : 'bn' ?>" class="lang-toggle-btn" title="Switch Language to <?= $isBn ? 'English' : 'বাংলা' ?>" aria-label="Switch Language">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lang-globe-icon"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        <span class="lang-label"><?= $isBn ? 'বাংলা' : 'EN' ?></span>
                    </a>

                    <!-- Theme Switcher Button (Sun / Moon SVG Icons) -->
                    <button type="button" class="theme-toggle-btn" id="theme-toggle" aria-label="Toggle Light and Dark Mode" title="Toggle Light/Dark Theme">
                        <span class="theme-icon-sun" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                        </span>
                        <span class="theme-icon-moon" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                        </span>
                    </button>
                </div>

                <!-- Social Media Icons (Including WhatsApp) -->
                <div class="top-socials">
                    <a href="https://wa.me/<?= View::e($waNumber) ?>" aria-label="WhatsApp" target="_blank" rel="noopener" class="top-social-wa">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>
                    <a href="<?= View::e($fbUrl) ?>" aria-label="Facebook" target="_blank" rel="noopener">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="<?= View::e($igUrl) ?>" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="<?= View::e($pinterestUrl) ?>" aria-label="Pinterest" target="_blank" rel="noopener">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 12-5.373 12-12 0-6.628-5.393-12-12-12z"/></svg>
                    </a>
                    <a href="<?= View::e($ytUrl) ?>" aria-label="YouTube" target="_blank" rel="noopener">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="nav-bar">
        <div class="site-wrapper nav-inner">
            <a class="brand" href="/" aria-label="<?= View::e($siteName) ?> — Home">
                <picture>
                    <source srcset="/assets/img/lily-logo.webp" type="image/webp">
                    <img src="/assets/img/lily-logo.png" alt="<?= View::e($siteName) ?>" width="210" height="48" class="brand-logo" decoding="async">
                </picture>
            </a>

            <!-- Desktop Navigation -->
            <nav class="main-nav" aria-label="Main navigation">
                <ul>
                    <?php foreach ($navLinks as $key => $item): ?>
                        <li>
                            <a href="<?= View::e($item['href']) ?>"
                                class="<?= $active === $key ? 'is-active' : '' ?>"><?= View::e($item['label']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="nav-right">
                <a class="btn-pill-crimson" href="/#contact"><?= Lang::get('get_consultation_btn') ?></a>
                
                <!-- Mobile Drawer Hamburger Toggle Button -->
                <button type="button" class="nav-toggle" aria-label="Open navigation menu" aria-expanded="false" data-nav-toggle>
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Luxury Mobile Fullscreen Drawer Navigation -->
    <div class="mobile-drawer" id="mobile-drawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Mobile Navigation">
        <div class="mobile-drawer-backdrop" data-nav-close></div>
        
        <div class="mobile-drawer-content">
            <!-- Close Button (Top Right) -->
            <button class="mobile-drawer-close" type="button" aria-label="Close menu" data-nav-close>
                &times;
            </button>

            <div class="mobile-drawer-inner-wrap">
                <!-- Centered Header: Logo Top + Controls Below -->
                <div class="mobile-drawer-header-centered">
                    <a class="brand mobile-drawer-logo" href="/" data-nav-close>
                        <img src="/assets/img/lily-logo.png" alt="<?= View::e($siteName) ?>" width="190" height="42" class="brand-logo">
                    </a>
                    
                    <!-- Centered Language Switcher & Dark Mode Toggle -->
                    <div class="mobile-drawer-controls">
                        <a href="?lang=<?= $isBn ? 'en' : 'bn' ?>" class="lang-toggle-btn" title="Switch Language to <?= $isBn ? 'English' : 'বাংলা' ?>" aria-label="Switch Language">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lang-globe-icon"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <span class="lang-label"><?= $isBn ? 'বাংলা' : 'EN' ?></span>
                        </a>

                        <button type="button" class="theme-toggle-btn" data-theme-toggle-drawer aria-label="Toggle Light/Dark Theme">
                            <span class="theme-icon-sun" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                            </span>
                            <span class="theme-icon-moon" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Centered Navigation Links -->
                <nav class="mobile-drawer-nav">
                    <ul>
                        <?php foreach ($navLinks as $key => $item): ?>
                            <li>
                                <a href="<?= View::e($item['href']) ?>"
                                   class="mobile-nav-link <?= $active === $key ? 'is-active' : '' ?>"
                                   data-nav-close>
                                    <span><?= View::e($item['label']) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <!-- CTA Consultation Button -->
                <div class="mobile-drawer-cta">
                    <a class="btn btn-pill-crimson w-100" href="/#contact" data-nav-close>
                        <?= Lang::get('get_consultation_btn') ?>
                    </a>
                </div>

                <!-- Bottom Contact & Social Information Card -->
                <div class="mobile-drawer-footer">
                    <div class="drawer-contact-block">
                        <a href="tel:<?= preg_replace('/\s+/', '', $phonePrimary) ?>" class="drawer-contact-item">
                            <span class="d-icon">&#9742;</span>
                            <div>
                                <strong><?= $isBn ? 'সরাসরি কল করুন' : 'Call Us Directly' ?></strong>
                                <small><?= View::e($phonePrimary) ?></small>
                            </div>
                        </a>
                        
                        <a href="https://wa.me/<?= View::e($waNumber) ?>" target="_blank" rel="noopener" class="drawer-contact-item drawer-whatsapp">
                            <span class="d-icon wa-svg-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#25D366"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </span>
                            <div>
                                <strong><?= $isBn ? 'হোয়াটসঅ্যাপ চ্যাট' : 'WhatsApp Chat' ?></strong>
                                <small><?= $isBn ? 'ইনস্ট্যান্ট কনসালটেশন' : 'Instant Consultation' ?></small>
                            </div>
                        </a>

                        <a href="mailto:<?= View::e($emailPrimary) ?>" class="drawer-contact-item">
                            <span class="d-icon">&#9993;</span>
                            <div>
                                <strong><?= $isBn ? 'ইমেইল ইনকোয়ারি' : 'Email Inquiries' ?></strong>
                                <small><?= View::e($emailPrimary) ?></small>
                            </div>
                        </a>
                    </div>

                    <!-- Social Media Bar (With WhatsApp) -->
                    <div class="drawer-socials">
                        <a href="https://wa.me/<?= View::e($waNumber) ?>" aria-label="WhatsApp" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                        <a href="<?= View::e($fbUrl) ?>" aria-label="Facebook" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="<?= View::e($igUrl) ?>" aria-label="Instagram" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="<?= View::e($ytUrl) ?>" aria-label="YouTube" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                    
                    <p class="drawer-copyright">&copy; <?= date('Y') ?> <?= View::e($siteName) ?>. <?= Lang::get('all_rights') ?></p>
                </div>
            </div>
        </div>
    </div>
</header>