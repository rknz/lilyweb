<?php
/**
 * Official Privacy Policy Page — Lily Interiors
 * Complete Legal Documentation & Compliance
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();

$settings = [];
try {
    $pdo = \Lilyweb\Core\Database::connect();
    $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings`");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (\Throwable $e) {}

$legalEmail = $settings['email_primary'] ?? 'lilyinteriorsbd@gmail.com';
$legalPhone = $settings['phone_primary'] ?? '+88 01734182694';
$legalPhoneTel = preg_replace('/\s+/', '', $legalPhone);
$legalAddress = $isBn
    ? ($settings['address_bn'] ?? '৩৬ বীর উত্তম সি.আর দত্ত রোড, হাতিরপুল, ঢাকা-১২০৫')
    : ($settings['address_en'] ?? '36 Bir Uttam C.R Dutta Road, Hatirpool, Dhaka-1205, Bangladesh');
?>
<style>
    /* ========================================================
       LEGAL DOCUMENTATION & COMPLIANCE PAGES STYLING
       ======================================================== */
    .legal-page-hero {
        padding: 5rem 0 3.5rem;
        background: radial-gradient(circle at 50% 0%, rgba(200, 16, 46, 0.08) 0%, transparent 70%), var(--surface-cool, #F7F8F8);
        border-bottom: 1px solid var(--border-subtle, #E8E9EA);
        position: relative;
        text-align: center;
    }

    .legal-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(200, 16, 46, 0.1);
        border: 1px solid rgba(200, 16, 46, 0.3);
        color: #C8102E;
        padding: 0.4rem 1.1rem;
        border-radius: 9999px;
        font-size: 0.84rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 1.25rem;
    }

    .legal-title {
        font-size: clamp(2.2rem, 4.5vw, 3.2rem);
        font-weight: 800;
        color: var(--charcoal-deep, #111111);
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }

    .legal-subtitle {
        font-size: 1.05rem;
        color: var(--charcoal-muted, #62666B);
        max-width: 680px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .legal-meta-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
        font-size: 0.88rem;
        color: var(--charcoal-muted, #62666B);
        flex-wrap: wrap;
    }

    .legal-layout-grid {
        display: grid;
        grid-template-columns: 290px 1fr;
        gap: 3.5rem;
        padding: 4rem 0 6rem;
        align-items: start;
    }

    /* Sticky Table of Contents Sidebar */
    .legal-toc-sticky {
        position: sticky;
        top: 100px;
        background: var(--surface-white, #FFFFFF);
        border: 1.5px solid var(--border-subtle, #E8E9EA);
        border-radius: 18px;
        padding: 1.6rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        max-height: calc(100vh - 130px);
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .legal-toc-title {
        font-size: 0.88rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--charcoal-deep, #111111);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-subtle, #E8E9EA);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legal-toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .legal-toc-link {
        display: block;
        padding: 0.45rem 0.65rem;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--charcoal-muted, #62666B);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        line-height: 1.35;
    }

    .legal-toc-link:hover {
        color: #C8102E;
        background: rgba(200, 16, 46, 0.06);
        transform: translateX(3px);
    }

    /* Content Article Area */
    .legal-article {
        display: flex;
        flex-direction: column;
        gap: 2.2rem;
        color: var(--charcoal-text, #181818);
        font-size: 1rem;
        line-height: 1.75;
    }

    .legal-section-block {
        background: var(--surface-white, #FFFFFF);
        border: 1.5px solid var(--border-subtle, #E8E9EA);
        border-radius: 18px;
        padding: 2.2rem 2.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        scroll-margin-top: 110px;
        transition: all 0.2s ease;
    }

    .legal-section-block:hover {
        border-color: rgba(200, 16, 46, 0.35);
    }

    .legal-sec-num-head {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-subtle, #E8E9EA);
    }

    .legal-sec-badge {
        width: 36px !important;
        height: 36px !important;
        min-width: 36px !important;
        border-radius: 10px !important;
        background: linear-gradient(135deg, #E61E40 0%, #C8102E 100%) !important;
        color: #FFFFFF !important;
        font-weight: 800 !important;
        font-size: 0.96rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 14px rgba(200, 16, 46, 0.35) !important;
        flex-shrink: 0 !important;
        line-height: 1 !important;
    }

    .legal-sec-h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--charcoal-deep, #111111);
        line-height: 1.25;
        letter-spacing: -0.01em;
        margin: 0;
    }

    .legal-sec-h3 {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--charcoal-deep, #111111);
        margin: 1.6rem 0 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legal-ul {
        margin: 0.85rem 0 1.25rem 1.4rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .legal-ul li {
        color: var(--charcoal-text, #181818);
        font-size: 0.96rem;
    }

    .legal-ul li::marker {
        color: #C8102E;
    }

    .legal-callout-box {
        background: var(--surface-cool, #F7F8F8);
        border-left: 4px solid #C8102E;
        padding: 1.25rem 1.5rem;
        border-radius: 0 14px 14px 0;
        margin: 1.5rem 0;
        font-size: 0.94rem;
        color: var(--charcoal-text, #181818);
    }

    .legal-contact-card {
        background: var(--surface-cool, #F7F8F8);
        border: 1.5px solid var(--border-subtle, #E8E9EA);
        border-radius: 14px;
        padding: 1.5rem 1.8rem;
        margin-top: 1.25rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
    }

    .legal-contact-item h4 {
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--charcoal-muted, #62666B);
        margin-bottom: 0.35rem;
        letter-spacing: 0.05em;
    }

    .legal-contact-item p, .legal-contact-item a {
        font-size: 0.96rem;
        font-weight: 700;
        color: var(--charcoal-deep, #111111);
        text-decoration: none;
    }

    .legal-contact-item a:hover {
        color: #C8102E;
    }

    @media (max-width: 992px) {
        .legal-layout-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .legal-toc-sticky {
            position: relative;
            top: 0;
            max-height: 280px;
        }

        .legal-section-block {
            padding: 1.6rem 1.5rem;
        }
    }
</style>

<!-- Hero Section -->
<header class="legal-page-hero">
    <div class="site-wrapper">
        <div class="legal-badge-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>LEGAL &amp; DATA PRIVACY COMPLIANCE</span>
        </div>
        <h1 class="legal-title">Privacy Policy</h1>
        <p class="legal-subtitle">
            At Lily Interiors, we respect your privacy and are committed to protecting the personal information you provide when you visit our website, request an interior consultation, or use any of our services.
        </p>
        <div class="legal-meta-bar">
            <span>📅 <strong>Last Updated:</strong> September 2026</span>
            <span>📍 <strong>Jurisdiction:</strong> Bangladesh</span>
            <span>🏢 <strong>Entity:</strong> Lily Interiors</span>
        </div>
    </div>
</header>

<!-- Main Reading Workspace -->
<div class="site-wrapper">
    <div class="legal-layout-grid">
        <!-- Quick Jump Table of Contents Sidebar -->
        <aside class="legal-toc-sticky">
            <div class="legal-toc-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <span>Table of Contents</span>
            </div>
            <nav>
                <ul class="legal-toc-list">
                    <li><a href="#sec-1" class="legal-toc-link">1. About Lily Interiors</a></li>
                    <li><a href="#sec-2" class="legal-toc-link">2. Information We Collect</a></li>
                    <li><a href="#sec-3" class="legal-toc-link">3. How We Use Information</a></li>
                    <li><a href="#sec-4" class="legal-toc-link">4. Photos, Videos &amp; Portfolio</a></li>
                    <li><a href="#sec-5" class="legal-toc-link">5. Cookies &amp; Tracking</a></li>
                    <li><a href="#sec-6" class="legal-toc-link">6. Analytics &amp; Third Parties</a></li>
                    <li><a href="#sec-7" class="legal-toc-link">7. How We Share Information</a></li>
                    <li><a href="#sec-8" class="legal-toc-link">8. Protection of Information</a></li>
                    <li><a href="#sec-9" class="legal-toc-link">9. Data Retention</a></li>
                    <li><a href="#sec-10" class="legal-toc-link">10. Your Privacy Choices</a></li>
                    <li><a href="#sec-11" class="legal-toc-link">11. Marketing Communications</a></li>
                    <li><a href="#sec-12" class="legal-toc-link">12. Children's Privacy</a></li>
                    <li><a href="#sec-13" class="legal-toc-link">13. External Links</a></li>
                    <li><a href="#sec-14" class="legal-toc-link">14. Policy Changes</a></li>
                    <li><a href="#sec-15" class="legal-toc-link">15. Contact Us</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Comprehensive Legal Content Article -->
        <article class="legal-article">
            <div class="legal-callout-box">
                <p>
                    By using our website or submitting information through our contact, inquiry, quotation, consultation, or other forms, you acknowledge that you have read and understood this Privacy Policy.
                </p>
            </div>

            <!-- Section 1 -->
            <section class="legal-section-block" id="sec-1">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">1</div>
                    <h2 class="legal-sec-h2">About Lily Interiors</h2>
                </div>
                <p>
                    Lily Interiors is a premier interior design and turnkey interior solutions company operating in Bangladesh.
                </p>
                <p style="margin-top: 0.75rem;">
                    We provide a broad range of residential and commercial interior services, including interior design, renovation, office interiors, furniture design, kitchen solutions, wardrobes, cabinets, false ceilings, board-based interior works, SPL applications, imported materials, customized furniture, and turnkey interior projects.
                </p>
                <p style="margin-top: 0.75rem;">
                    For the purpose of this Privacy Policy, <em>"Lily Interiors"</em>, <em>"we"</em>, <em>"us"</em>, and <em>"our"</em> refer to Lily Interiors.
                </p>
            </section>

            <!-- Section 2 -->
            <section class="legal-section-block" id="sec-2">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">2</div>
                    <h2 class="legal-sec-h2">Information We Collect</h2>
                </div>
                <p>
                    Depending on how you interact with our website and services, we may collect different types of information.
                </p>

                <h3 class="legal-sec-h3">2.1 Information You Provide Directly</h3>
                <p>When you contact us, request a quotation, submit a consultation request, or communicate with us, you may voluntarily provide:</p>
                <ul class="legal-ul">
                    <li>Full name</li>
                    <li>Phone number &amp; WhatsApp number</li>
                    <li>Email address</li>
                    <li>Project location / Property address</li>
                    <li>Property type (Apartment, Duplex, Office, Penthouse, Commercial Space)</li>
                    <li>Project category &amp; Room type requirements</li>
                    <li>Preferred design style (Modern Luxury, Contemporary, Minimalist, Classic)</li>
                    <li>Approximate project requirements or budget estimation</li>
                    <li>Message, note, or specific inquiry details</li>
                    <li>Information contained in floor plans, documents, or files you voluntarily submit</li>
                    <li>Other details reasonably necessary to respond to your request</li>
                </ul>
                <div class="legal-callout-box" style="margin-top: 1rem;">
                    <em>Notice:</em> Please do not submit sensitive personal information unless it is genuinely required for your project consultation.
                </div>

                <h3 class="legal-sec-h3">2.2 Project Information</h3>
                <p>For the purpose of preparing architectural designs, 3D visualizations, quotations, proposals, site measurements, estimates, or project execution plans, we may collect:</p>
                <ul class="legal-ul">
                    <li>Property type and layout specifications</li>
                    <li>Room or space details (Living, Dining, Master Bed, Gourmet Kitchen)</li>
                    <li>Floor or unit structural details</li>
                    <li>Approximate measurements and square footage</li>
                    <li>Customized furniture, wardrobe, or cabinetry specifications</li>
                    <li>Material preferences (Boards, Laminates, Marble, Acrylic, SPL, Imported Hardware)</li>
                    <li>Design references and mood boards</li>
                    <li>Photos or video recordings of the property site</li>
                    <li>Architectural blueprints and CAD/PDF layout drawings</li>
                    <li>Target project timelines and handover expectations</li>
                </ul>

                <h3 class="legal-sec-h3">2.3 Automatically Collected Technical Information</h3>
                <p>When you visit our website, certain technical information may be collected automatically to maintain security, optimize performance, and ensure smooth rendering across devices:</p>
                <ul class="legal-ul">
                    <li>IP address and approximate geographic location</li>
                    <li>Browser type, version, and language</li>
                    <li>Device type, operating system, and screen resolution</li>
                    <li>Referring URL and navigation paths across pages</li>
                    <li>Date, timestamp, and duration of page visits</li>
                    <li>Technical error logs and performance metrics</li>
                </ul>
            </section>

            <!-- Section 3 -->
            <section class="legal-section-block" id="sec-3">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">3</div>
                    <h2 class="legal-sec-h2">How We Use Your Information</h2>
                </div>
                <p>We may use the information we collect for the following legitimate business and service purposes:</p>
                <ul class="legal-ul">
                    <li>Respond promptly to your messages and consultation inquiries</li>
                    <li>Contact you via Phone, WhatsApp, or Email regarding your interior project</li>
                    <li>Schedule on-site visits, measurements, and studio consultations</li>
                    <li>Prepare customized design proposals, BOQ (Bill of Quantities), and price quotations</li>
                    <li>Provide bespoke residential and commercial interior design and execution services</li>
                    <li>Coordinate material procurement, workshop fabrication, and on-site craftsmen</li>
                    <li>Communicate milestones, progress updates, and project handover schedules</li>
                    <li>Provide ongoing customer support and post-handover warranty assistance</li>
                    <li>Improve website usability, speed, dark/light theme experience, and responsiveness</li>
                    <li>Maintain website cybersecurity, prevent unauthorized access, and protect against fraud</li>
                    <li>Maintain accounting, transaction, and service records in accordance with law</li>
                    <li>Send relevant architectural showcases and promotional announcements where permitted</li>
                </ul>
            </section>

            <!-- Section 4 -->
            <section class="legal-section-block" id="sec-4">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">4</div>
                    <h2 class="legal-sec-h2">Project Photos, Videos &amp; Portfolio Content</h2>
                </div>
                <p>
                    Lily Interiors photographs and records completed and ongoing interior projects for technical documentation, architectural showcase, portfolio presentation, social media, and marketing.
                </p>
                <p style="margin-top: 0.75rem;">
                    We take reasonable care to protect client privacy and avoid publishing sensitive personal items, confidential documents, or private family portraits without consent.
                </p>
                <div class="legal-callout-box">
                    If you believe that any image or project reference published on our website contains information that should not be publicly displayed, please contact us immediately at <a href="mailto:<?= Security::e($legalEmail) ?>" style="color: #C8102E; font-weight: 700;"><?= Security::e($legalEmail) ?></a> so we can review and address the matter.
                </div>
            </section>

            <!-- Section 5 -->
            <section class="legal-section-block" id="sec-5">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">5</div>
                    <h2 class="legal-sec-h2">Cookies &amp; Similar Technologies</h2>
                </div>
                <p>
                    Our website uses essential cookies and local storage to remember your preferences (such as Dark/Light mode selection and language preferences), analyze aggregate traffic, and ensure seamless navigation.
                </p>
                <ul class="legal-ul">
                    <li><strong>Essential Cookies:</strong> Required for website navigation, security, and CSRF protection.</li>
                    <li><strong>Preference Cookies:</strong> Stores user theme (Dark/Light mode) and language settings.</li>
                    <li><strong>Analytics &amp; Performance:</strong> Helps us understand page engagement and loading times.</li>
                </ul>
                <p>You can manage or disable cookies through your browser settings at any time, though some interactive features may experience limitations.</p>
            </section>

            <!-- Section 6 -->
            <section class="legal-section-block" id="sec-6">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">6</div>
                    <h2 class="legal-sec-h2">Analytics &amp; Third-Party Services</h2>
                </div>
                <p>
                    We may utilize trusted third-party services for website analytics (e.g. Google Analytics 4), cloud hosting, email delivery, interactive maps, and security monitoring.
                </p>
                <p style="margin-top: 0.75rem;">
                    These third-party providers process technical data strictly in accordance with their respective privacy policies and are not authorized to use your personal information for independent purposes.
                </p>
            </section>

            <!-- Section 7 -->
            <section class="legal-section-block" id="sec-7">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">7</div>
                    <h2 class="legal-sec-h2">How We Share Information</h2>
                </div>
                <p>
                    <strong>Lily Interiors does not sell, rent, or trade your personal information to third-party marketers.</strong>
                </p>
                <p style="margin-top: 0.75rem;">Information is shared solely on a need-to-know basis with:</p>
                <ul class="legal-ul">
                    <li>Authorized architects, interior designers, and site engineers</li>
                    <li>Specialized craftsmen, contractors, and fabrication workshop teams</li>
                    <li>Verified material suppliers and logistics handlers (for site delivery)</li>
                    <li>Secure cloud hosting, IT infrastructure, and technical service providers</li>
                    <li>Legal, financial, or regulatory authorities where strictly required by Bangladesh law</li>
                </ul>
            </section>

            <!-- Section 8 -->
            <section class="legal-section-block" id="sec-8">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">8</div>
                    <h2 class="legal-sec-h2">Protection of Your Information</h2>
                </div>
                <p>
                    We enforce strict technical and operational safeguards to protect your personal and project information against unauthorized access, loss, alteration, or misuse:
                </p>
                <ul class="legal-ul">
                    <li>SSL / TLS encrypted data transmission (HTTPS)</li>
                    <li>Database parameterization and strong CSRF protection</li>
                    <li>Restricted administrative access with multi-layered authentication</li>
                    <li>Encrypted database backups and automated integrity checks</li>
                </ul>
                <p style="margin-top: 0.75rem;">
                    While we maintain rigorous cybersecurity protocols, please note that no electronic transmission over the internet can be guaranteed to be 100% impenetrable.
                </p>
            </section>

            <!-- Section 9 -->
            <section class="legal-section-block" id="sec-9">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">9</div>
                    <h2 class="legal-sec-h2">Data Retention</h2>
                </div>
                <p>
                    We retain client inquiry and project records only for as long as necessary to complete design execution, maintain warranty and post-handover support, resolve commercial matters, and comply with accounting and statutory obligations under Bangladesh law. When no longer required, data is securely anonymized or deleted.
                </p>
            </section>

            <!-- Section 10 -->
            <section class="legal-section-block" id="sec-10">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">10</div>
                    <h2 class="legal-sec-h2">Your Privacy Choices</h2>
                </div>
                <p>You have the right to:</p>
                <ul class="legal-ul">
                    <li>Request a copy of the personal information we hold about you</li>
                    <li>Request correction or updating of inaccurate project details</li>
                    <li>Request deletion of non-essential records where legally permissible</li>
                    <li>Opt out of non-essential marketing communications</li>
                </ul>
                <p>To exercise any of these choices, please submit a request to our management team via email.</p>
            </section>

            <!-- Section 11 -->
            <section class="legal-section-block" id="sec-11">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">11</div>
                    <h2 class="legal-sec-h2">Marketing Communications</h2>
                </div>
                <p>
                    We only send project consultation updates, design catalogs, or promotional announcements when you have engaged with us or requested information. You may opt out of non-essential promotional messages at any time by replying directly or contacting us.
                </p>
            </section>

            <!-- Section 12 -->
            <section class="legal-section-block" id="sec-12">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">12</div>
                    <h2 class="legal-sec-h2">Children's Privacy</h2>
                </div>
                <p>
                    Our services and website are intended exclusively for adult homeowners, business executives, property owners, and commercial decision-makers. We do not knowingly collect personal data from individuals under 18 years of age.
                </p>
            </section>

            <!-- Section 13 -->
            <section class="legal-section-block" id="sec-13">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">13</div>
                    <h2 class="legal-sec-h2">External Links</h2>
                </div>
                <p>
                    Our website may contain links to external platforms (such as Google Maps, Facebook, Instagram, YouTube, LinkedIn, or supplier catalogs). Lily Interiors is not responsible for the independent privacy practices, terms, or content of third-party websites.
                </p>
            </section>

            <!-- Section 14 -->
            <section class="legal-section-block" id="sec-14">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">14</div>
                    <h2 class="legal-sec-h2">Changes to This Privacy Policy</h2>
                </div>
                <p>
                    Lily Interiors may update this Privacy Policy periodically to reflect service expansions, technological advancements, or regulatory updates. Any modifications will be published on this page with an updated <em>"Last Updated"</em> date.
                </p>
            </section>

            <!-- Section 15 -->
            <section class="legal-section-block" id="sec-15">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">15</div>
                    <h2 class="legal-sec-h2">Contact Us</h2>
                </div>
                <p>
                    For any questions, concerns, or privacy requests regarding your personal information, please reach out to our official studio office:
                </p>

                <div class="legal-contact-card">
                    <div class="legal-contact-item">
                        <h4>Direct Email</h4>
                        <p><a href="mailto:<?= Security::e($legalEmail) ?>"><?= Security::e($legalEmail) ?></a></p>
                    </div>
                    <div class="legal-contact-item">
                        <h4>Phone &amp; WhatsApp</h4>
                        <p><a href="tel:<?= Security::e($legalPhoneTel) ?>"><?= Security::e($legalPhone) ?></a></p>
                    </div>
                    <div class="legal-contact-item">
                        <h4>Studio Address</h4>
                        <p><?= Security::e($legalAddress) ?></p>
                    </div>
                </div>
            </section>
        </article>
    </div>
</div>
