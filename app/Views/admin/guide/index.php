<?php
/**
 * Master CMS User Guide (ড্যাশবোর্ড ব্যবহার নির্দেশিকা)
 * Comprehensive Step-by-Step Guidelines for Non-Technical Management
 */
use Lilyweb\Core\Security;
?>
<style>
    .guide-hero-banner {
        background: radial-gradient(circle at 0% 0%, rgba(200, 16, 46, 0.14) 0%, transparent 60%), var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 2.2rem 2.4rem;
        margin-bottom: 2.2rem;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .guide-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(200, 16, 46, 0.12);
        border: 1px solid rgba(200, 16, 46, 0.3);
        color: var(--crimson);
        padding: 0.35rem 0.95rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 800;
        margin-bottom: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .guide-hero-title {
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 800;
        color: var(--text-heading);
        line-height: 1.25;
        margin-bottom: 0.65rem;
    }

    .guide-hero-sub {
        font-size: 0.96rem;
        color: var(--text-muted);
        max-width: 850px;
        line-height: 1.65;
    }

    /* Quick Master Navigation Matrix */
    .guide-matrix-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 2rem 2.2rem;
        margin-bottom: 2.5rem;
        box-shadow: var(--card-shadow);
    }

    .guide-matrix-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 1.25rem;
    }

    .guide-matrix-table th {
        background: var(--table-header-bg);
        color: var(--text-heading);
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.9rem 1.15rem;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }

    .guide-matrix-table td {
        padding: 1.1rem 1.15rem;
        font-size: 0.9rem;
        color: var(--text-main);
        border-bottom: 1px solid var(--border-subtle);
        vertical-align: middle;
    }

    .guide-matrix-table tr:hover td {
        background: var(--table-row-hover);
    }

    /* Modules Grid */
    .guide-modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 1.65rem;
        margin-bottom: 2.5rem;
    }

    .guide-module-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 1.85rem 2rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition-smooth);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .guide-module-card:hover {
        border-color: rgba(200, 16, 46, 0.4);
        transform: translateY(-3px);
        box-shadow: var(--card-shadow-hover);
    }

    .guide-mod-head {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .guide-mod-num {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 12px;
        background: var(--crimson-gradient);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px var(--crimson-glow);
    }

    .guide-mod-title-box h3 {
        font-size: 1.12rem;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 0.25rem;
        line-height: 1.25;
    }

    .guide-mod-route {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.76rem;
        font-weight: 700;
        color: var(--crimson);
        background: rgba(200, 16, 46, 0.08);
        padding: 0.15rem 0.55rem;
        border-radius: var(--radius-full);
    }

    .guide-steps-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        font-size: 0.88rem;
        color: var(--text-main);
        line-height: 1.6;
        flex: 1;
    }

    .guide-steps-list li {
        position: relative;
        padding-left: 1.4rem;
    }

    .guide-steps-list li::before {
        content: "✓";
        position: absolute;
        left: 0;
        top: 0;
        color: #22C55E;
        font-weight: 800;
        font-size: 0.92rem;
    }

    .guide-mod-action-btn {
        margin-top: 1.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: var(--bg-card-alt);
        border: 1px solid var(--border-color);
        color: var(--text-heading);
        font-size: 0.84rem;
        font-weight: 700;
        padding: 0.6rem 1.15rem;
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: var(--transition-smooth);
    }

    .guide-mod-action-btn:hover {
        background: var(--crimson-gradient);
        color: #FFFFFF !important;
        border-color: var(--crimson);
        box-shadow: 0 4px 14px var(--crimson-glow);
    }

    /* Pro Tips Container */
    .guide-tips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
        margin-top: 1rem;
    }

    .guide-tip-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.35rem 1.5rem;
        font-size: 0.88rem;
        color: var(--text-main);
        line-height: 1.6;
    }

    .guide-tip-card strong {
        color: var(--crimson);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.45rem;
    }
</style>

<!-- Top Return Action -->
<div class="page-actions">
    <a href="/admin" class="btn-back-highlight">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>ড্যাশবোর্ড ওভারভিউতে ফিরুন</span>
    </a>
</div>

<!-- Header Welcome Banner -->
<div class="guide-hero-banner">
    <div class="guide-hero-badge">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <span>CMS EXECUTIVE MANUAL</span>
    </div>
    <h1 class="guide-hero-title">📖 ড্যাশবোর্ড ব্যবহার নির্দেশিকা ও সহজ গাইডলাইন</h1>
    <p class="guide-hero-sub">
        লিলি ইন্টেরিয়র্স (Lily Interiors) ওয়েবসাইটের অ্যাডমিন প্যানেলটি সম্পূর্ণ নন-টেকনিক্যাল এবং ইউজার-ফ্রেন্ডলি করে তৈরি করা হয়েছে। কোনো কোডিং বা কারিগরি জ্ঞান ছাড়াই আপনি পুরো ওয়েবসাইটের লেখা, ছবি, স্লাইডার, প্রজেক্ট, গ্রাহকদের মেসেজ ও ফোন নম্বর যেকোনো সময় এক ক্লিকে পরিবর্তন বা আপডেট করতে পারবেন।
    </p>
</div>

<!-- Quick Master Action Matrix (কোথায় গেলে কী পরিবর্তন হবে?) -->
<div class="guide-matrix-card">
    <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.6rem;">
        <span>🎯 কুইক মাস্টার চার্ট: কোথায় গেলে কী পরিবর্তন করবেন?</span>
    </h2>
    <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 0.25rem;">আপনার প্রয়োজনীয় কাজ অনুযায়ী নিচের সাইডবার মেনুগুলোতে প্রবেশ করুন:</p>

    <div style="overflow-x: auto;">
        <table class="guide-matrix-table">
            <thead>
                <tr>
                    <th style="width: 28%;">সাইডবার মেনু</th>
                    <th style="width: 48%;">কী কী পরিবর্তন করা যায়?</th>
                    <th style="width: 24%; text-align: right;">সরাসরি লিংক</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">🖼️ Homepage (All Sections)</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">মাস্টার হোমপেজ বিল্ডার</div>
                    </td>
                    <td>
                        হোমপেজের ৮টি সেকশন (হিরো স্লাইডার, পরিসংখ্যান, About Us, ৬টি সার্ভিস কার্ড, কাজের ৪টি ধাপ, ক্লায়েন্ট রিভিউ ও কন্টাক্ট ইনফো)।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/homepage" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">🏠 Projects Portfolio</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">প্রজেক্ট গ্যালারি</div>
                    </td>
                    <td>
                        নতুন প্রজেক্ট তৈরি, কাভার ও গ্যালারি ফটো আপলোড, বাজেট, স্কয়ার ফিট, লোকেশন ও ক্লায়েন্ট নাম এডিট করা।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/projects" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">🏷️ Project Categories</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">ক্যাটাগরি ম্যানেজমেন্ট</div>
                    </td>
                    <td>
                        Living Room, Luxury Kitchen, Duplex, Office ইত্যাদি ক্যাটাগরি তৈরি, ফিল্টার ও সাজানো।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/categories" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">📁 Media Library</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">ফটো গ্যালারি হাব</div>
                    </td>
                    <td>
                        ওয়েবসাইটে আপলোড করা সব ছবি একনজরে দেখা, সার্চ করা এবং সরাসরি নতুন ছবি আপলোড করে রাখা।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/media" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">✉️ Contact Inquiries</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">গ্রাহকদের মেসেজ ও লিড</div>
                    </td>
                    <td>
                        ভিজিটরদের পাঠানো কনসালটেশন রিকোয়েস্ট দেখা, ১-ক্লিকে ফোন কল বা WhatsApp চ্যাট শুরু করা।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/contacts" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">⚙️ Site Settings</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">গ্লোবাল সেটিংস</div>
                    </td>
                    <td>
                        অফিস ঠিকানা (Official Office Address), হটলাইন ফোন, হোয়াটসঅ্যাপ নম্বর, ইমেইল ও এডমিন পাসওয়ার্ড।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/settings" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">🔍 Google SEO &amp; AI</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">সার্চ ইঞ্জিন র‍্যাংকিং</div>
                    </td>
                    <td>
                        গুগলে সার্চ রেজাল্টের টাইটেল ও বিবরণ, ফেসবুক শেয়ার ইমেজ (OG Image), গুগল অ্যানালিটিক্স কোড।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/seo" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="color: var(--text-heading); font-size: 0.95rem;">💾 Backup &amp; Restore</strong>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">ডাটা নিরাপত্তা</div>
                    </td>
                    <td>
                        ১-ক্লিকে সম্পূর্ণ ওয়েবসাইটের ডাটাবেজ ব্যাকআপ ফাইল (SQL) ডাউনলোড করে কম্পিউটারে সংরক্ষণ করা।
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/backup" class="btn-sm btn-primary">যান &rarr;</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Detailed Step-by-Step Module Guide Cards -->
<h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem;">
    <span>📘 প্রতিটি সেকশনের বিস্তারিত কার্যপ্রণালী ও স্টেপসমূহ:</span>
</h2>

<div class="guide-modules-grid">

    <!-- Step 1: Dashboard Overview -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">01</div>
            <div class="guide-mod-title-box">
                <h3>📊 ড্যাশবোর্ড ওভারভিউ</h3>
                <span class="guide-mod-route">মেনু: Dashboard Overview (/admin)</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>ভিজিটর ট্রাফিক গ্রাফ</strong>: প্রতিদিন কতজন নতুন মানুষ ওয়েবসাইট দেখছেন তা রিয়েল-টাইম গ্রাফে দৃশ্যমান।</li>
            <li><strong>৪টি হেলথ স্কোর</strong>: এসইও স্কোর (SEO Health), পেজ স্পিড (Page Speed), লিড কনভার্সন এবং ডাটাবেজ নিরাপত্তা স্ট্যাটাস।</li>
            <li><strong>রিসেন্ট প্রজেক্টস ও লিডস</strong>: ওয়েবসাইটের সাম্প্রতিক প্রজেক্ট ও গ্রাহকদের পাঠানো লেটেস্ট মেসেজগুলো একনজরে দেখতে পাবেন।</li>
        </ul>
        <a href="/admin" class="guide-mod-action-btn">ড্যাশবোর্ড ওভারভিউ খুলুন &rarr;</a>
    </div>

    <!-- Step 2: Homepage All Sections Master Hub -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">02</div>
            <div class="guide-mod-title-box">
                <h3>🖼️ হোমপেজ মাস্টার বিল্ডার</h3>
                <span class="guide-mod-route">মেনু: Homepage (All Sections)</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>৮টি সেকশন এক পেজে</strong>: সাইডবার ছোট করে হোমপেজের সমস্ত ৮টি সেকশনকে এই একটি পেজের ভেতর সাজানো হয়েছে।</li>
            <li><strong>অটো স্ক্রলস্পাই পিল বার</strong>: পেজ স্ক্রল করার সাথে সাথে উপরের সংশ্লিষ্ট মেনু পিল বাটনটি লাল হয়ে সিলেক্টেড থাকবে।</li>
            <li><strong>সরাসরি এডিট সুবিধা</strong>: হিরো স্লাইডার, পরিসংখ্যান সংখ্যা, About Us, ৬টি সার্ভিস, ৪টি কাজের ধাপ ও গ্রাহক রিভিউ সরাসরি এখান থেকেই পরিবর্তনযোগ্য।</li>
        </ul>
        <a href="/admin/homepage" class="guide-mod-action-btn">হোমপেজ বিল্ডার খুলুন &rarr;</a>
    </div>

    <!-- Step 3: Direct Image Uploader & Media Library -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">03</div>
            <div class="guide-mod-title-box">
                <h3>📤 ইনস্ট্যান্ট ফটো আপলোডার ও পিকার</h3>
                <span class="guide-mod-route">সব ফর্ম ও প্রজেক্ট ক্রিয়েশনে যুক্ত</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>ইউআরএল মুখস্ত করার ঝামেলা নেই</strong>: প্রজেক্ট বা স্লাইডার তৈরির সময় এখন আর কোথাও গিয়ে ছবির লিংক কপি করতে হবে না।</li>
            <li><strong>Upload Photo Directly বাটন</strong>: যেকোনো ছবি আপনার কম্পিউটার বা মোবাইল থেকে তৎক্ষণাৎ সিলেক্ট করে আপলোড করুন।</li>
            <li><strong>Choose from Media Library বাটন</strong>: এক ক্লিকে মিডিয়া লাইব্রেরির পপআপ খুলে আগে আপলোড করা যেকোনো ছবি সিলেক্ট করে দিন।</li>
        </ul>
        <a href="/admin/media" class="guide-mod-action-btn">মিডিয়া লাইব্রেরি দেখুন &rarr;</a>
    </div>

    <!-- Step 4: Projects Portfolio -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">04</div>
            <div class="guide-mod-title-box">
                <h3>🎨 প্রজেক্ট পোর্টফোলিও আপলোড</h3>
                <span class="guide-mod-route">মেনু: Projects Portfolio (/admin/projects)</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>নতুন প্রজেক্ট যোগ</strong>: <code>+ Add New Project</code> বাটনে ক্লিক করে প্রজেক্টের নাম, এরিয়া (Sq Ft), লোকেশন (যেমন: গুলশান, বনানী) দিন।</li>
            <li><strong>কাভার ও গ্যালারি ফটো</strong>: প্রজেক্টের প্রধান কাভার ছবি ও ভেতরের একাধিক আর্কিটেকচারাল ফটো একসাথে যুক্ত করুন।</li>
            <li><strong>স্ট্যাটাস নিয়ন্ত্রণ</strong>: কাজ চলাকালীন ড্রাফট (Draft) রাখতে পারেন, কাজ শেষ হলে পাবলিশ (Published) করে দিন।</li>
        </ul>
        <a href="/admin/projects" class="guide-mod-action-btn">প্রজেক্ট তালিকা দেখুন &rarr;</a>
    </div>

    <!-- Step 5: Contact Inquiries CRM -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">05</div>
            <div class="guide-mod-title-box">
                <h3>📞 কাস্টমার মেসেজ ও লিড ফলোআপ</h3>
                <span class="guide-mod-route">মেনু: Contact Inquiries (/admin/contacts)</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>নতুন ইনকোয়ারি ব্যাজ</strong>: কোনো ভিজিটর মেসেজ দিলে সাইডবারে স্বয়ংক্রিয়ভাবে লাল ব্যাজ (যেমন: <code>1</code>) ফুটে উঠবে।</li>
            <li><strong>সরাসরি কল ও হোয়াটসঅ্যাপ</strong>: গ্রাহকের নামের পাশে থাকা বাটনে চাপ দিলেই তৎক্ষণাৎ সরাসরি কল বা হোয়াটসঅ্যাপ চ্যাট ওপেন হবে।</li>
            <li><strong>ফলোআপ নোট</strong>: ক্লায়েন্টের সাথে কথা হওয়ার পর 'In Discussion' বা 'Closed' স্ট্যাটাস দিয়ে সংরক্ষণ করুন।</li>
        </ul>
        <a href="/admin/contacts" class="guide-mod-action-btn">ইনকোয়ারিস দেখুন &rarr;</a>
    </div>

    <!-- Step 6: Site Settings & Official Address -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">06</div>
            <div class="guide-mod-title-box">
                <h3>🏢 অফিস ঠিকানা ও যোগাযোগ সেটিংস</h3>
                <span class="guide-mod-route">মেনু: Site Settings (/admin/settings)</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>Official Office Address</strong>: কোম্পানির বাংলা ও ইংরেজি অফিস ঠিকানা পরিবর্তন করার ডেডিকেটেড ফিল্ড।</li>
            <li><strong>হটলাইন ও হোয়াটসঅ্যাপ</strong>: ওয়েবসাইটের হেডারে ও ফুটারে প্রদর্শিত ফোন নম্বর ও হোয়াটসঅ্যাপ পরিবর্তন।</li>
            <li><strong>পাসওয়ার্ড পরিবর্তন</strong>: অ্যাডমিন অ্যাকাউন্টের নিরাপত্তা নিশ্চিত করতে এখান থেকে পাসওয়ার্ড পরিবর্তন করুন।</li>
        </ul>
        <a href="/admin/settings" class="guide-mod-action-btn">সাইট সেটিংস খুলুন &rarr;</a>
    </div>

    <!-- Step 7: Google SEO & Meta Tuning -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">07</div>
            <div class="guide-mod-title-box">
                <h3>🔍 গুগল এসইও ও মেটা ডাটা</h3>
                <span class="guide-mod-route">মেনু: Google SEO &amp; AI (/admin/seo)</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>গুগল সার্চ প্রিভিউ</strong>: মানুষ গুগলে 'Interior Design Dhaka' লিখে সার্চ দিলে আপনার সাইটের কী টাইটেল ও ডেসক্রিপশন আসবে তা সেট করুন।</li>
            <li><strong>সোশ্যাল মিডিয়া থাম্বনেইল</strong>: ফেসবুকে লিংক শেয়ার করলে যে সুন্দর বড় ছবিটি দেখা যাবে (OG Image) তা সেট করুন।</li>
            <li><strong>ট্র্যাকিং কোড</strong>: Google Analytics 4 (GA4) আইডি ও গুগল ভেরিফিকেশন কোড এক ক্লিকে বসানোর সুবিধা।</li>
        </ul>
        <a href="/admin/seo" class="guide-mod-action-btn">গুগল এসইও কন্ট্রোল খুলুন &rarr;</a>
    </div>

    <!-- Step 8: Database Backup & Sign Out -->
    <div class="guide-module-card">
        <div class="guide-mod-head">
            <div class="guide-mod-num">08</div>
            <div class="guide-mod-title-box">
                <h3>💾 ডাটাবেজ ব্যাকআপ ও সাইন আউট</h3>
                <span class="guide-mod-route">মেনু: Backup &amp; Restore (/admin/backup)</span>
            </div>
        </div>
        <ul class="guide-steps-list">
            <li><strong>১-ক্লিক ব্যাকআপ</strong>: প্রতি সপ্তাহে একবার 'Generate Database Backup' বাটনে চাপ দিয়ে ব্যাকআপ ফাইল আপনার কম্পিউটারে ডাউনলোড করে রাখুন।</li>
            <li><strong>নিরাপদ সাইন আউট (Sign Out)</strong>: কাজ শেষ হলে সাইডবারের নিচে অথবা টপবারের প্রোফাইল ড্রপডাউনের <strong>Sign Out</strong> বাটনে ক্লিক করে লগআউট করুন।</li>
            <li><strong>ডার্ক/লাইট মোড</strong>: টপবারের <code>Light Mode / Dark Mode</code> বাটনে ক্লিক করে ড্যাশবোর্ডের থিম পরিবর্তন করতে পারেন।</li>
        </ul>
        <a href="/admin/backup" class="guide-mod-action-btn">ব্যাকআপ পেজ খুলুন &rarr;</a>
    </div>

</div>

<!-- Pro Tips Box for Non-Technical Management -->
<div class="admin-card" style="background: var(--bg-card-alt); border: 1.5px solid var(--border-color); padding: 2rem 2.2rem;">
    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-heading); margin-bottom: 0.6rem; display: flex; align-items: center; gap: 0.5rem;">
        <span>💡 নন-টেকনিক্যাল অ্যাডমিনদের জন্য কিছু দরকারি প্রো-টিপস:</span>
    </h3>
    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.25rem;">এই সহজ নিয়মগুলো অনুসরণ করলে ওয়েবসাইট সবসময় দ্রুতগতির ও আকর্ষণীয় থাকবে:</p>

    <div class="guide-tips-grid">
        <div class="guide-tip-card">
            <strong>📸 ১. ছবি আপলোডের টিপস</strong>
            প্রজেক্ট বা স্লাইডারের জন্য যেকোনো ছবি আপলোড করলে ওয়েবসাইট স্বয়ংক্রিয়ভাবে সেটিকে সুপার-ফাস্ট <strong>WebP</strong> ফরম্যাটে অপটিমাইজ করে নেয়, তাই ওয়েবসাইট কখনো ভারী বা স্লো হবে না।
        </div>
        <div class="guide-tip-card">
            <strong>🌐 ২. বাংলা ও ইংরেজি ভাষা</strong>
            আপনি যদি কোনো সেকশনে শুধু ইংরেজি লেখা দেন এবং বাংলা ফাঁকা রাখেন, ওয়েবসাইট স্বয়ংক্রিয়ভাবে ইংরেজি লেখাটিই প্রদর্শন করবে। কোনো সমস্যা বা এরর হবে না।
        </div>
        <div class="guide-tip-card">
            <strong>📱 ৩. মোবাইল থেকে ড্যাশবোর্ড চালানো</strong>
            আপনি যেকোনো সময় আপনার স্মার্টফোনের ব্রাউজার থেকে এই ড্যাশবোর্ডে লগইন করে ক্লায়েন্টদের মেসেজ পড়তে, হোয়াটসঅ্যাপে রিপ্লাই দিতে বা নতুন ছবি আপলোড করতে পারবেন।
        </div>
        <div class="guide-tip-card">
            <strong>🔒 ৪. ডাটা ও সাইট নিরাপত্তা</strong>
            লিলি ইন্টেরিয়র্সের সম্পূর্ণ সিস্টেম সিএসআরএফ (CSRF) ও এসকিউএল ইনজেকশন প্রোটেকশন দ্বারা সুরক্ষিত। কাজ শেষ হলে সবসময় <strong>Sign Out</strong> বাটনে ক্লিক করে বের হওয়া নিরাপদ।
        </div>
    </div>
</div>
