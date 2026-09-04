<?php
/**
 * Official Terms & Conditions Page — Lily Interiors
 * Complete Legal Documentation & Commercial Terms
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
       LEGAL DOCUMENTATION & TERMS OF SERVICE STYLING
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
        max-width: 720px;
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
        padding: 0.42rem 0.65rem;
        font-size: 0.82rem;
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
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span>TERMS &amp; CONDITIONS OF SERVICE</span>
        </div>
        <h1 class="legal-title">Terms &amp; Conditions</h1>
        <p class="legal-subtitle">
            Welcome to the official website of Lily Interiors. These Terms &amp; Conditions govern your use of our website, project inquiries, design consultations, quotations, and interior execution services.
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
                <span>Navigation (25 Sections)</span>
            </div>
            <nav>
                <ul class="legal-toc-list">
                    <li><a href="#term-1" class="legal-toc-link">1. About Our Services</a></li>
                    <li><a href="#term-2" class="legal-toc-link">2. Website Information</a></li>
                    <li><a href="#term-3" class="legal-toc-link">3. Project Images &amp; Portfolio</a></li>
                    <li><a href="#term-4" class="legal-toc-link">4. Project Inquiry</a></li>
                    <li><a href="#term-5" class="legal-toc-link">5. Consultation</a></li>
                    <li><a href="#term-6" class="legal-toc-link">6. Quotations and Estimates</a></li>
                    <li><a href="#term-7" class="legal-toc-link">7. Materials &amp; Availability</a></li>
                    <li><a href="#term-8" class="legal-toc-link">8. Material Variation</a></li>
                    <li><a href="#term-9" class="legal-toc-link">9. Design Changes</a></li>
                    <li><a href="#term-10" class="legal-toc-link">10. Project Timeline</a></li>
                    <li><a href="#term-11" class="legal-toc-link">11. Site Conditions</a></li>
                    <li><a href="#term-12" class="legal-toc-link">12. Client Responsibilities</a></li>
                    <li><a href="#term-13" class="legal-toc-link">13. Payment Terms</a></li>
                    <li><a href="#term-14" class="legal-toc-link">14. Cancellation &amp; Termination</a></li>
                    <li><a href="#term-15" class="legal-toc-link">15. Warranty and Defects</a></li>
                    <li><a href="#term-16" class="legal-toc-link">16. Intellectual Property</a></li>
                    <li><a href="#term-17" class="legal-toc-link">17. Client-Supplied Materials</a></li>
                    <li><a href="#term-18" class="legal-toc-link">18. Website Availability</a></li>
                    <li><a href="#term-19" class="legal-toc-link">19. Prohibited Use</a></li>
                    <li><a href="#term-20" class="legal-toc-link">20. Third-Party Services</a></li>
                    <li><a href="#term-21" class="legal-toc-link">21. Limitation of Information</a></li>
                    <li><a href="#term-22" class="legal-toc-link">22. Force Majeure</a></li>
                    <li><a href="#term-23" class="legal-toc-link">23. Changes to These Terms</a></li>
                    <li><a href="#term-24" class="legal-toc-link">24. Governing Law</a></li>
                    <li><a href="#term-25" class="legal-toc-link">25. Contact Information</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Comprehensive Legal Content Article -->
        <article class="legal-article">
            <div class="legal-callout-box">
                <p>
                    By accessing or using our website, you agree to comply with these Terms &amp; Conditions. If you do not agree with these terms, please do not use the website.
                </p>
            </div>

            <!-- Section 1 -->
            <section class="legal-section-block" id="term-1">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">1</div>
                    <h2 class="legal-sec-h2">About Our Services</h2>
                </div>
                <p>Lily Interiors provides interior design and interior-related execution services in Bangladesh, including but not limited to:</p>
                <ul class="legal-ul">
                    <li>Residential Interior Design (Luxury Apartments, Duplexes, Penthouses)</li>
                    <li>Commercial Interior Design (Corporate Offices, Executive Suites, Retail Spaces)</li>
                    <li>Turnkey Renovation &amp; Architectural Remodeling</li>
                    <li>Gourmet Modular Kitchen Design, Fabrication &amp; Installation</li>
                    <li>Customized Wardrobes, Walk-in Closets &amp; Architectural Cabinetry</li>
                    <li>Bespoke Custom Furniture Manufacturing &amp; Upholstery</li>
                    <li>Acoustic &amp; Decorative False Ceiling Designs &amp; Lighting Engineering</li>
                    <li>Board-based Interior Works, Veneered Boards &amp; Precision CNC Fabrication</li>
                    <li>SPL (Solid Phenolic Laminate) High-Durability Interior Applications</li>
                    <li>Imported Luxury Hardware, Fittings, Quartz &amp; Sintered Stone Surfaces</li>
                    <li>Turnkey Interior Projects, Site Supervision &amp; Comprehensive Project Management</li>
                </ul>
                <p style="margin-top: 0.75rem;">
                    The availability of a particular service, material, product, or solution may depend on specific project requirements, site location, engineering feasibility, and supplier stock.
                </p>
            </section>

            <!-- Section 2 -->
            <section class="legal-section-block" id="term-2">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">2</div>
                    <h2 class="legal-sec-h2">Website Information</h2>
                </div>
                <p>
                    We make reasonable efforts to ensure that information published on our website is useful, accurate, and inspiring. However, website content includes general architectural descriptions, conceptual renderings, sample photographs, estimated price ranges, and promotional showcases.
                </p>
                <p style="margin-top: 0.75rem;">
                    Website content should not automatically be interpreted as a binding contractual commitment or fixed quotation unless expressly stated in an official signed proposal or written contract.
                </p>
            </section>

            <!-- Section 3 -->
            <section class="legal-section-block" id="term-3">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">3</div>
                    <h2 class="legal-sec-h2">Project Images and Portfolio</h2>
                </div>
                <p>
                    Images displayed in our portfolio gallery represent completed luxury projects, 3D photorealistic renderings, or selected craftsmanship details.
                </p>
                <p style="margin-top: 0.75rem;">Actual executed results for your unique property may differ depending on:</p>
                <ul class="legal-ul">
                    <li>Specific site dimensions and architectural structural conditions</li>
                    <li>Custom client selections of wood grains, veneers, marbles, fabrics, and paint shades</li>
                    <li>Natural and artificial ambient lighting variations</li>
                    <li>Building management guidelines and property structural limitations</li>
                </ul>
            </section>

            <!-- Section 4 -->
            <section class="legal-section-block" id="term-4">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">4</div>
                    <h2 class="legal-sec-h2">Project Inquiry</h2>
                </div>
                <p>
                    Submitting an inquiry through our website form does not automatically create a contract between the visitor and Lily Interiors. An inquiry allows our team to review your requirements, schedule a site measurement, and initiate discussions.
                </p>
                <p style="margin-top: 0.75rem;">
                    A formal business relationship begins only after the official proposal, BOQ quotation, commercial milestones, and work order are formally accepted and signed by both parties.
                </p>
            </section>

            <!-- Section 5 -->
            <section class="legal-section-block" id="term-5">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">5</div>
                    <h2 class="legal-sec-h2">Consultation</h2>
                </div>
                <p>Our comprehensive architectural consultation involves discussions regarding:</p>
                <ul class="legal-ul">
                    <li>Design preferences, functional requirements, and space optimization</li>
                    <li>Space planning, floor layout adjustments, and circulation flow</li>
                    <li>Material grading, imported accessories, and fabrication standards</li>
                    <li>Realistic budget modeling and milestone timeline planning</li>
                </ul>
                <p>Any preliminary conceptual advice or ballpark estimations provided during initial discussions remain subject to on-site verification.</p>
            </section>

            <!-- Section 6 -->
            <section class="legal-section-block" id="term-6">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">6</div>
                    <h2 class="legal-sec-h2">Quotations and Estimates</h2>
                </div>
                <p>
                    Official quotations provided by Lily Interiors are based on detailed architectural site measurements and specified material selections. The final project cost may be adjusted if there are:
                </p>
                <ul class="legal-ul">
                    <li>Actual on-site dimensional variations upon structural bare-shell verification</li>
                    <li>Client-requested material upgrades (e.g. Italian marble, imported Austrian Blum hardware)</li>
                    <li>Additional scopes of work added during execution</li>
                    <li>Applicable government taxes, VAT, or official customs tariff changes on imported supplies</li>
                </ul>
            </section>

            <!-- Section 7 -->
            <section class="legal-section-block" id="term-7">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">7</div>
                    <h2 class="legal-sec-h2">Materials and Product Availability</h2>
                </div>
                <p>
                    We procure high-grade local and imported materials (commercial ply, HPL, acrylic boards, solid wood, European profile handles, quartz countertops). In the rare event that a specific imported hardware model or veneer code is discontinued by the manufacturer, an equal or superior grade alternative will be presented to the client for mutual approval.
                </p>
            </section>

            <!-- Section 8 -->
            <section class="legal-section-block" id="term-8">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">8</div>
                    <h2 class="legal-sec-h2">Color, Texture and Material Variation</h2>
                </div>
                <p>
                    Natural materials (such as authentic wood veneers, marble slabs, granite, and top-grain leathers) possess organic variations in grain patterns, shade nuances, and texture. Display monitors may also render colors slightly differently from physical material swatches under warm or cool indoor lighting.
                </p>
            </section>

            <!-- Section 9 -->
            <section class="legal-section-block" id="term-9">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">9</div>
                    <h2 class="legal-sec-h2">Design Changes</h2>
                </div>
                <p>
                    Clients may request modifications during the 2D/3D design phase. Modifications requested after factory fabrication or structural on-site installation has begun may incur additional material or labor charges, which will be communicated in writing before proceeding.
                </p>
            </section>

            <!-- Section 10 -->
            <section class="legal-section-block" id="term-10">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">10</div>
                    <h2 class="legal-sec-h2">Project Timeline</h2>
                </div>
                <p>
                    Project execution durations (e.g. 45 to 90 days depending on scope) are outlined in the project agreement and are contingent on timely milestone approvals, uninterrupted site access, utility availability (electricity/water on site), and agreed payment schedules.
                </p>
            </section>

            <!-- Section 11 -->
            <section class="legal-section-block" id="term-11">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">11</div>
                    <h2 class="legal-sec-h2">Site Conditions</h2>
                </div>
                <p>
                    Clients are responsible for providing unobstructed site access. Hidden structural defects, concealed plumbing leaks, or faulty pre-existing civil work discovered during demolition or renovation may require remedial engineering, which will be documented and discussed promptly.
                </p>
            </section>

            <!-- Section 12 -->
            <section class="legal-section-block" id="term-12">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">12</div>
                    <h2 class="legal-sec-h2">Client Responsibilities</h2>
                </div>
                <p>To ensure smooth execution, clients are expected to:</p>
                <ul class="legal-ul">
                    <li>Provide clear project specifications and review architectural drawings promptly</li>
                    <li>Facilitate building society / housing society permissions where required</li>
                    <li>Fulfill agreed contractual milestone payments on schedule</li>
                </ul>
            </section>

            <!-- Section 13 -->
            <section class="legal-section-block" id="term-13">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">13</div>
                    <h2 class="legal-sec-h2">Payment Terms</h2>
                </div>
                <p>
                    Payment terms, booking advances, progress installments, and final handover balances are governed strictly by the approved work order schedule. All payments should be executed via official bank transfers, account payee cheques, or designated company payment gateways.
                </p>
            </section>

            <!-- Section 14 -->
            <section class="legal-section-block" id="term-14">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">14</div>
                    <h2 class="legal-sec-h2">Cancellation and Termination</h2>
                </div>
                <p>
                    In the event of project cancellation after work order confirmation, costs already incurred for bespoke CAD/3D drafting, customized factory fabrication, imported material procurement, and labor commitments will be accounted for in accordance with the contract terms.
                </p>
            </section>

            <!-- Section 15 -->
            <section class="legal-section-block" id="term-15">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">15</div>
                    <h2 class="legal-sec-h2">Warranty and Defects</h2>
                </div>
                <p>
                    Lily Interiors provides comprehensive post-handover craftsmanship warranties on modular cabinetry, furniture joinery, and structural fittings as specified in your agreement. Third-party electrical appliances, lights, and sanitary fittings carry their respective original manufacturer warranties.
                </p>
            </section>

            <!-- Section 16 -->
            <section class="legal-section-block" id="term-16">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">16</div>
                    <h2 class="legal-sec-h2">Intellectual Property</h2>
                </div>
                <p>
                    All architectural concepts, 3D renderings, floor layouts, website branding, graphics, logos, and original written copy produced by Lily Interiors remain the intellectual property of Lily Interiors and may not be reproduced without written permission.
                </p>
            </section>

            <!-- Section 17 -->
            <section class="legal-section-block" id="term-17">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">17</div>
                    <h2 class="legal-sec-h2">Client-Supplied Materials</h2>
                </div>
                <p>
                    If a client provides specific appliances, fixtures, or heirloom furniture pieces, our design team will advise on integration and measurements; however, warranty for client-supplied items remains with the respective supplier.
                </p>
            </section>

            <!-- Section 18 -->
            <section class="legal-section-block" id="term-18">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">18</div>
                    <h2 class="legal-sec-h2">Website Availability</h2>
                </div>
                <p>
                    We strive to keep our digital platform operational 24/7. Temporary downtime may occasionally occur due to routine server maintenance, security upgrades, or network infrastructure improvements.
                </p>
            </section>

            <!-- Section 19 -->
            <section class="legal-section-block" id="term-19">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">19</div>
                    <h2 class="legal-sec-h2">Prohibited Use</h2>
                </div>
                <p>
                    Visitors must not attempt unauthorized access, deploy automated scrapers, transmit malicious code, or submit fraudulent inquiries through our online portal.
                </p>
            </section>

            <!-- Section 20 -->
            <section class="legal-section-block" id="term-20">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">20</div>
                    <h2 class="legal-sec-h2">Third-Party Services and Links</h2>
                </div>
                <p>
                    External links provided for map navigation, social media channels, or material manufacturer catalogs operate independently, and Lily Interiors is not liable for third-party platform policies.
                </p>
            </section>

            <!-- Section 21 -->
            <section class="legal-section-block" id="term-21">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">21</div>
                    <h2 class="legal-sec-h2">Limitation of General Website Information</h2>
                </div>
                <p>
                    Website content serves primarily for informational, conceptual, and portfolio showcases. Binding commitments are governed by finalized project agreements and architectural contracts.
                </p>
            </section>

            <!-- Section 22 -->
            <section class="legal-section-block" id="term-22">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">22</div>
                    <h2 class="legal-sec-h2">Force Majeure</h2>
                </div>
                <p>
                    Lily Interiors is not liable for execution delays caused by unforeseen events beyond reasonable human control, including natural disasters, severe weather events, national supply chain disruptions, or government restrictions.
                </p>
            </section>

            <!-- Section 23 -->
            <section class="legal-section-block" id="term-23">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">23</div>
                    <h2 class="legal-sec-h2">Changes to These Terms</h2>
                </div>
                <p>
                    Lily Interiors reserves the right to revise these Terms &amp; Conditions when necessary. Updated terms become effective immediately upon publication on this page.
                </p>
            </section>

            <!-- Section 24 -->
            <section class="legal-section-block" id="term-24">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">24</div>
                    <h2 class="legal-sec-h2">Governing Law</h2>
                </div>
                <p>
                    These Terms &amp; Conditions and all contractual engagements shall be construed and governed in accordance with the laws of the People's Republic of Bangladesh.
                </p>
            </section>

            <!-- Section 25 -->
            <section class="legal-section-block" id="term-25">
                <div class="legal-sec-num-head">
                    <div class="legal-sec-badge">25</div>
                    <h2 class="legal-sec-h2">Contact Information</h2>
                </div>
                <p>
                    For inquiries regarding these Terms &amp; Conditions or our interior architectural services, please contact our management team:
                </p>

                <div class="legal-contact-card">
                    <div class="legal-contact-item">
                        <h4>Official Email</h4>
                        <p><a href="mailto:<?= Security::e($legalEmail) ?>"><?= Security::e($legalEmail) ?></a></p>
                    </div>
                    <div class="legal-contact-item">
                        <h4>Phone &amp; WhatsApp</h4>
                        <p><a href="tel:<?= Security::e($legalPhoneTel) ?>"><?= Security::e($legalPhone) ?></a></p>
                    </div>
                    <div class="legal-contact-item">
                        <h4>Studio Office</h4>
                        <p><?= Security::e($legalAddress) ?></p>
                    </div>
                </div>
            </section>
        </article>
    </div>
</div>
