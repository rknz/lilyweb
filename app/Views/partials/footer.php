<?php

/**
 * Distinctive Architectural Footer matching FINAL_DESKTOP_UI.png.
 * 5-Column Layout: Arched Window Vignette + Brand Bio + Explore + Help + Newsletter.
 */
use Lilyweb\Core\View;
use Lilyweb\Core\Config;
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;

$siteName = (string) Config::get('app.name', 'Lily Interiors');
$isBn = Lang::isBn();

$socials = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings`");
    $socials = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (\Throwable $e) {
    // Fail-safe
}
$fbUrl = $socials['social_facebook'] ?? 'https://www.facebook.com/LilyInteriorsbd/';
$igUrl = $socials['social_instagram'] ?? 'https://www.instagram.com/lilyinteriors/';
$pinterestUrl = $socials['social_pinterest'] ?? 'https://www.pinterest.com/';
$ytUrl = $socials['social_youtube'] ?? 'https://www.youtube.com/channel/UCkLkpvteAlhSFUkgzQy8p_g/videos';
?>
<footer class="site-footer">
    <div class="site-wrapper">
        <!-- 5-Column Architectural Footer Grid -->
        <div class="footer-layout">
            <!-- Col 1: Arched Vignette Window -->
            <div class="footer-arch-frame" aria-hidden="true">
                <picture>
                    <source srcset="/assets/img/footer-arch.webp" type="image/webp">
                    <img src="/assets/img/footer-arch.jpg" alt="Architectural arch detail" loading="lazy" decoding="async" width="150" height="190">
                </picture>
            </div>

            <!-- Col 2: Brand & Bio -->
            <div class="footer-col-brand">
                <a class="brand footer-brand-logo" href="/" aria-label="<?= View::e($siteName) ?>">
                    <picture>
                        <source srcset="/assets/img/lily-logo.webp" type="image/webp">
                        <img src="/assets/img/lily-logo.png" alt="<?= View::e($siteName) ?>" width="190" height="42" class="brand-logo footer-logo-img" loading="lazy" decoding="async">
                    </picture>
                </a>
                <p class="footer-bio"><?= Lang::get('footer_bio') ?></p>
                
                <!-- Social Media Icons -->
                <div class="footer-socials">
                    <a href="<?= View::e($fbUrl) ?>" aria-label="Facebook" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="<?= View::e($igUrl) ?>" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="<?= View::e($pinterestUrl) ?>" aria-label="Pinterest" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 12-5.373 12-12 0-6.628-5.393-12-12-12z"/></svg>
                    </a>
                    <a href="<?= View::e($ytUrl) ?>" aria-label="YouTube" target="_blank" rel="noopener">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 3: Explore Links -->
            <div class="footer-links-col">
                <h4 class="footer-heading"><?= Lang::get('explore') ?></h4>
                <ul>
                    <li><a href="/"><?= Lang::get('nav_home') ?></a></li>
                    <li><a href="/#services"><?= Lang::get('nav_services') ?></a></li>
                    <li><a href="/#portfolio"><?= $isBn ? 'পোর্টফোলিও' : 'Portfolio' ?></a></li>
                    <li><a href="/projects"><?= Lang::get('nav_projects') ?></a></li>
                    <li><a href="/about"><?= Lang::get('nav_about') ?></a></li>
                    <li><a href="/#process"><?= Lang::get('nav_process') ?></a></li>
                </ul>
            </div>

            <!-- Col 4: Help & Support Links -->
            <div class="footer-links-col">
                <h4 class="footer-heading"><?= Lang::get('help_support') ?></h4>
                <ul>
                    <li><a href="/faq"><?= Lang::get('nav_faq') ?></a></li>
                    <li><a href="/privacy-policy"><?= $isBn ? 'গোপনীয়তা নীতি' : 'Privacy Policy' ?></a></li>
                    <li><a href="/terms"><?= $isBn ? 'শর্তাবলী' : 'Terms &amp; Conditions' ?></a></li>
                    <li><a href="/#contact"><?= Lang::get('nav_contact') ?></a></li>
                </ul>
            </div>

            <!-- Col 5: Newsletter -->
            <div class="footer-newsletter">
                <h4 class="footer-heading"><?= Lang::get('stay_updated') ?></h4>
                <p class="newsletter-sub"><?= Lang::get('newsletter_sub') ?></p>
                <form action="/newsletter" method="post" class="newsletter-form">
                    <input type="email" name="email" placeholder="<?= Lang::get('newsletter_ph') ?>" required>
                    <button type="submit" aria-label="Subscribe">&rarr;</button>
                </form>
            </div>
        </div>

        <!-- Sub-Footer / Copyright -->
        <div class="footer-sub-bar">
            <p>&copy; <?= date('Y') ?> <?= View::e($siteName) ?>. <?= Lang::get('all_rights') ?></p>
            <p><?= Lang::get('designed_with_love') ?></p>
        </div>
    </div>
</footer>