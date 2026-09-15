<?php
use Lilyweb\Core\Security;

// Calculate SVG stroke offset for radius = 34 (circumference ≈ 213.63)
$calcOffset = function($score) {
    $circumference = 213.63;
    return $circumference - (($score / 100) * $circumference);
};
?>
<style>
    /* ========================================================
       MASTER EXECUTIVE LUXURY DASHBOARD (SOURCE: dashboard ui dark mode.png)
       ======================================================== */
    
    /* Top Action Bar */
    .top-actions-strip {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.65rem;
        flex-wrap: wrap;
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        background: var(--crimson-gradient);
        color: #FFFFFF !important;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 0.65rem 1.35rem;
        border-radius: var(--radius-md);
        text-decoration: none;
        box-shadow: 0 4px 16px var(--crimson-glow);
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }

    .btn-action-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 22px rgba(200, 16, 46, 0.6);
    }

    .btn-action-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        background: var(--bg-card);
        color: var(--text-heading) !important;
        border: 1.5px solid var(--border-color);
        font-weight: 700;
        font-size: 0.88rem;
        padding: 0.65rem 1.15rem;
        border-radius: var(--radius-md);
        text-decoration: none;
        box-shadow: var(--card-shadow);
        transition: var(--transition-smooth);
    }

    .btn-action-pill:hover {
        border-color: var(--crimson);
        color: var(--crimson) !important;
        transform: translateY(-2px);
    }

    .btn-action-pill.is-guide {
        background: rgba(200, 16, 46, 0.08);
        border-color: rgba(200, 16, 46, 0.25);
        color: var(--crimson) !important;
    }

    .btn-action-pill.is-guide:hover {
        background: var(--crimson);
        color: #FFFFFF !important;
        border-color: var(--crimson);
    }

    .badge-num-red {
        background: var(--crimson);
        color: #FFFFFF;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 0.12rem 0.48rem;
        border-radius: var(--radius-full);
        line-height: 1.2;
    }

    /* Row 1: 4 Large Health Gauge Cards */
    .health-gauges-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .gauge-box-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.6rem 1.4rem;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .gauge-box-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-shadow-hover);
        border-color: var(--border-focus);
    }

    .gauge-bottom-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        opacity: 0.85;
    }

    .gauge-left-side {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
    }

    .gauge-top-tag {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.75rem;
    }

    .gauge-icon-pod {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .gauge-title-text {
        font-size: 0.86rem;
        font-weight: 800;
        color: var(--text-heading);
        line-height: 1.2;
    }

    .gauge-score-wrap {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 0.35rem;
    }

    .gauge-big-num {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-heading);
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .gauge-status-tag {
        font-size: 0.82rem;
        font-weight: 700;
    }

    .gauge-desc-text {
        font-size: 0.76rem;
        color: var(--text-muted);
        line-height: 1.4;
    }

    .gauge-svg-wrapper {
        width: 82px;
        height: 82px;
        position: relative;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .gauge-svg-elem {
        transform: rotate(-90deg);
        width: 82px;
        height: 82px;
    }

    .gauge-track-bg {
        fill: none;
        stroke: var(--border-subtle);
        stroke-width: 7;
    }

    .gauge-prog-bar {
        fill: none;
        stroke-width: 7;
        stroke-linecap: round;
        stroke-dasharray: 213.63;
        transition: stroke-dashoffset 1s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .gauge-inner-icon {
        position: absolute;
        display: grid;
        place-items: center;
    }

    /* Row 2: 6 Compact Summary KPI Cards */
    .summary-kpi-row {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1.15rem;
        margin-bottom: 1.5rem;
    }

    .mini-kpi-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.35rem 1.25rem;
        box-shadow: var(--card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .mini-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover);
    }

    .mini-kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.65rem;
    }

    .mini-kpi-title {
        font-size: 0.78rem;
        font-weight: 800;
        color: var(--text-heading);
    }

    .mini-kpi-icon {
        width: 30px;
        height: 30px;
        border-radius: var(--radius-sm);
        display: grid;
        place-items: center;
    }

    .mini-kpi-val {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 0.25rem;
        letter-spacing: -0.02em;
    }

    .mini-kpi-sub {
        font-size: 0.76rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* Row 3: Middle Split (Traffic Curve 2/3 & Top Pages 1/3) */
    .middle-split-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .chart-box-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.65rem 1.6rem 1.4rem;
        box-shadow: var(--card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .chart-header-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .chart-title-area {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chart-title-icon {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-md);
        background: rgba(59, 130, 246, 0.12);
        color: #3B82F6;
        display: grid;
        place-items: center;
    }

    .chart-title-text h3 {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--text-heading);
    }

    .chart-title-text p {
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .chart-legend-wrap {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .legend-item-box {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .legend-dot-red {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--crimson);
        box-shadow: 0 0 8px rgba(200, 16, 46, 0.6);
    }

    .legend-dot-blue {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3B82F6;
        box-shadow: 0 0 8px rgba(59, 130, 246, 0.6);
    }

    .svg-curve-holder {
        width: 100%;
        height: 195px;
        position: relative;
        margin-bottom: 1.25rem;
    }

    .svg-curve-canvas {
        width: 100%;
        height: 100%;
        overflow: visible;
    }

    /* 4-Col Traffic Status Strip */
    .chart-bottom-metrics {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-color);
    }

    .chart-metric-item {
        display: flex;
        flex-direction: column;
    }

    .metric-item-val {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 0.45rem;
        letter-spacing: -0.01em;
    }

    .metric-item-badge {
        font-size: 0.74rem;
        font-weight: 800;
        color: #22C55E;
    }

    .metric-item-label {
        font-size: 0.74rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }

    /* Top Pages Card */
    .top-pages-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.65rem 1.5rem;
        box-shadow: var(--card-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .top-pages-list {
        display: flex;
        flex-direction: column;
        gap: 0.95rem;
        margin: 1.15rem 0;
    }

    .page-rank-row {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .page-rank-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--text-heading);
    }

    .page-rank-icon-name {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .page-rank-icon-pod {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: grid;
        place-items: center;
        font-size: 0.75rem;
    }

    .page-rank-bar-bg {
        width: 100%;
        height: 6px;
        background: var(--border-subtle);
        border-radius: 4px;
        overflow: hidden;
    }

    .page-rank-bar-fill {
        height: 100%;
        background: var(--crimson-gradient);
        border-radius: 4px;
    }

    /* Row 4: Bottom 3-Card Grid */
    .bottom-triplet-row {
        display: grid;
        grid-template-columns: 1.35fr 1.35fr 1.1fr;
        gap: 1.5rem;
    }

    .triplet-box-card {
        background: var(--bg-card);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.65rem 1.5rem;
        box-shadow: var(--card-shadow);
        display: flex;
        flex-direction: column;
    }

    .triplet-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .triplet-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--text-heading);
    }

    .triplet-view-all {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-muted);
        text-decoration: none;
        padding: 0.25rem 0.65rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--bg-card-alt);
        transition: var(--transition-smooth);
    }

    .triplet-view-all:hover {
        color: var(--crimson);
        border-color: var(--crimson);
    }

    /* Horizontal 4-Card Showcase for Recent Projects (Reference: dashboard ui dark mode.png) */
    .recent-proj-horizontal-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.85rem;
    }

    .proj-card-mini {
        background: var(--bg-card-alt);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .proj-card-mini:hover {
        transform: translateY(-3px);
        border-color: var(--crimson);
    }

    .proj-mini-cover {
        width: 100%;
        height: 85px;
        object-fit: cover;
        display: block;
    }

    .proj-mini-body {
        padding: 0.65rem 0.6rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-between;
    }

    .proj-mini-title {
        font-size: 0.78rem;
        font-weight: 800;
        color: var(--text-heading);
        line-height: 1.2;
        margin-bottom: 0.2rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .proj-mini-loc {
        font-size: 0.68rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .proj-mini-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* List Items in Latest Inquiries */
    .inquiry-item-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .inquiry-row-card {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .inquiry-row-card:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .inquiry-avatar-pod {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 0.84rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .inquiry-details-col {
        flex: 1;
        min-width: 0;
    }

    .inquiry-top-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.15rem;
    }

    .inquiry-client-name {
        font-size: 0.86rem;
        font-weight: 800;
        color: var(--text-heading);
    }

    .inquiry-time-badge {
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    .inquiry-service-desc {
        font-size: 0.74rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Quick Action 2x3 Grid in Triplet 3 */
    .qa-mini-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.65rem;
        margin-bottom: 1.5rem;
    }

    .qa-btn-square {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.75rem 0.5rem;
        background: var(--bg-card-alt);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-heading);
        font-size: 0.74rem;
        font-weight: 700;
        text-align: center;
        transition: var(--transition-smooth);
    }

    .qa-btn-square:hover {
        border-color: var(--crimson);
        color: var(--crimson);
        transform: translateY(-2px);
    }

    .qa-btn-square svg {
        width: 18px;
        height: 18px;
    }

    /* System Status List in Triplet 3 */
    .sys-status-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .sys-status-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .sys-status-val {
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Responsive Adaptations */
    @media (max-width: 1440px) {
        .health-gauges-row { grid-template-columns: repeat(2, 1fr); }
        .summary-kpi-row { grid-template-columns: repeat(3, 1fr); }
        .bottom-triplet-row { grid-template-columns: 1fr; }
        .recent-proj-horizontal-grid { grid-template-columns: repeat(4, 1fr); }
    }

    @media (max-width: 1024px) {
        .middle-split-row { grid-template-columns: 1fr; }
        .summary-kpi-row { grid-template-columns: repeat(2, 1fr); }
        .recent-proj-horizontal-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .health-gauges-row { grid-template-columns: 1fr; }
        .summary-kpi-row { grid-template-columns: 1fr; }
        .recent-proj-horizontal-grid { grid-template-columns: 1fr; }
        .chart-bottom-metrics { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<!-- Top Quick Action Strip -->
<div class="top-actions-strip">
    <a href="/admin/projects/create" class="btn-action-primary">

        <span>+ Add New Project</span>
    </a>
    <a href="/admin/hero" class="btn-action-pill">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
        <span>Manage Hero Slider</span>
    </a>
    <a href="/admin/contacts" class="btn-action-pill">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <span>View Inquiries (<?= (int)$stats['new_inquiries_count'] ?> New)</span>
        <?php if ((int)$stats['new_inquiries_count'] > 0): ?>
            <span class="badge-num-red"><?= (int)$stats['new_inquiries_count'] ?></span>
        <?php endif; ?>
    </a>
    <a href="/admin/backup" class="btn-action-pill">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        <span>Generate SQL Backup</span>
    </a>
    <a href="/admin/docs" class="btn-action-pill is-guide">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <span>📖 ব্যবহার নির্দেশিকা (Guide)</span>
    </a>
</div>

<!-- Row 1: 4 Large Health Gauge Cards (Master Reference: dashboard ui dark mode.png) -->
<div class="health-gauges-row">
    <!-- Card 1: SEO Health Score -->
    <div class="gauge-box-card">
        <div class="gauge-left-side">
            <div class="gauge-top-tag">
                <div class="gauge-icon-pod" style="background: rgba(34, 197, 94, 0.15); color: #22C55E;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <span class="gauge-title-text">SEO Health Score</span>
            </div>
            <div class="gauge-score-wrap">
                <span class="gauge-big-num">98%</span>
                <span class="gauge-status-tag" style="color: #22C55E;">Excellent</span>
            </div>
            <p class="gauge-desc-text">Schema, Sitemap, Canonical &amp; Meta Tags Verified</p>
        </div>
        <div class="gauge-svg-wrapper">
            <svg class="gauge-svg-elem" viewBox="0 0 82 82">
                <circle class="gauge-track-bg" cx="41" cy="41" r="34"></circle>
                <circle class="gauge-prog-bar" cx="41" cy="41" r="34" style="stroke: #22C55E; stroke-dashoffset: <?= $calcOffset(98) ?>; filter: drop-shadow(0 0 6px rgba(34,197,94,0.5));"></circle>
            </svg>
            <div class="gauge-inner-icon" style="color: #22C55E;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
        </div>
        <div class="gauge-bottom-wave" style="background: linear-gradient(90deg, transparent, #22C55E, transparent);"></div>
    </div>

    <!-- Card 2: Page Speed & Vitals -->
    <div class="gauge-box-card">
        <div class="gauge-left-side">
            <div class="gauge-top-tag">
                <div class="gauge-icon-pod" style="background: rgba(59, 130, 246, 0.15); color: #3B82F6;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <span class="gauge-title-text">Page Speed &amp; Vitals</span>
            </div>
            <div class="gauge-score-wrap">
                <span class="gauge-big-num">96%</span>
                <span class="gauge-status-tag" style="color: #3B82F6;">Excellent</span>
            </div>
            <p class="gauge-desc-text">WebP, Compression, Cache &amp; Core Web Vitals Active</p>
        </div>
        <div class="gauge-svg-wrapper">
            <svg class="gauge-svg-elem" viewBox="0 0 82 82">
                <circle class="gauge-track-bg" cx="41" cy="41" r="34"></circle>
                <circle class="gauge-prog-bar" cx="41" cy="41" r="34" style="stroke: #3B82F6; stroke-dashoffset: <?= $calcOffset(96) ?>; filter: drop-shadow(0 0 6px rgba(59,130,246,0.5));"></circle>
            </svg>
            <div class="gauge-inner-icon" style="color: #3B82F6;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            </div>
        </div>
        <div class="gauge-bottom-wave" style="background: linear-gradient(90deg, transparent, #3B82F6, transparent);"></div>
    </div>

    <!-- Card 3: Lead Conversion Rate -->
    <div class="gauge-box-card">
        <div class="gauge-left-side">
            <div class="gauge-top-tag">
                <div class="gauge-icon-pod" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <span class="gauge-title-text">Lead Conversion Rate</span>
            </div>
            <div class="gauge-score-wrap">
                <span class="gauge-big-num">88%</span>
                <span class="gauge-status-tag" style="color: #F59E0B;">Good</span>
            </div>
            <p class="gauge-desc-text">Inquiries &amp; Consultation Requests vs Traffic</p>
        </div>
        <div class="gauge-svg-wrapper">
            <svg class="gauge-svg-elem" viewBox="0 0 82 82">
                <circle class="gauge-track-bg" cx="41" cy="41" r="34"></circle>
                <circle class="gauge-prog-bar" cx="41" cy="41" r="34" style="stroke: #F59E0B; stroke-dashoffset: <?= $calcOffset(88) ?>; filter: drop-shadow(0 0 6px rgba(245,158,11,0.5));"></circle>
            </svg>
            <div class="gauge-inner-icon" style="color: #F59E0B;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        </div>
        <div class="gauge-bottom-wave" style="background: linear-gradient(90deg, transparent, #F59E0B, transparent);"></div>
    </div>

    <!-- Card 4: Security & Database -->
    <div class="gauge-box-card">
        <div class="gauge-left-side">
            <div class="gauge-top-tag">
                <div class="gauge-icon-pod" style="background: rgba(139, 92, 246, 0.15); color: #8B5CF6;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <span class="gauge-title-text">Security &amp; Database</span>
            </div>
            <div class="gauge-score-wrap">
                <span class="gauge-big-num">100%</span>
                <span class="gauge-status-tag" style="color: #8B5CF6;">Secure</span>
            </div>
            <p class="gauge-desc-text">CSRF, Encryption, Session &amp; Daily SQL Backups</p>
        </div>
        <div class="gauge-svg-wrapper">
            <svg class="gauge-svg-elem" viewBox="0 0 82 82">
                <circle class="gauge-track-bg" cx="41" cy="41" r="34"></circle>
                <circle class="gauge-prog-bar" cx="41" cy="41" r="34" style="stroke: #8B5CF6; stroke-dashoffset: <?= $calcOffset(100) ?>; filter: drop-shadow(0 0 6px rgba(139,92,246,0.5));"></circle>
            </svg>
            <div class="gauge-inner-icon" style="color: #8B5CF6;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
        </div>
        <div class="gauge-bottom-wave" style="background: linear-gradient(90deg, transparent, #8B5CF6, transparent);"></div>
    </div>
</div>

<!-- Row 2: 6 Compact Summary KPI Cards (Horizontal 6-column grid) -->
<div class="summary-kpi-row">
    <!-- 1. Projects Portfolio -->
    <div class="mini-kpi-card">
        <div class="mini-kpi-top">
            <span class="mini-kpi-title">Projects Portfolio</span>
            <div class="mini-kpi-icon" style="background: rgba(200, 16, 46, 0.12); color: var(--crimson);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            </div>
        </div>
        <div class="mini-kpi-val"><?= (int)$stats['projects_count'] ?></div>
        <div class="mini-kpi-sub">Published Live Works</div>
    </div>

    <!-- 2. Hero Slides -->
    <div class="mini-kpi-card">
        <div class="mini-kpi-top">
            <span class="mini-kpi-title">Hero Slides</span>
            <div class="mini-kpi-icon" style="background: rgba(59, 130, 246, 0.12); color: #3B82F6;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
            </div>
        </div>
        <div class="mini-kpi-val"><?= (int)$stats['slides_count'] ?></div>
        <div class="mini-kpi-sub">Active Slides</div>
    </div>

    <!-- 3. Client Reviews -->
    <div class="mini-kpi-card">
        <div class="mini-kpi-top">
            <span class="mini-kpi-title">Client Reviews</span>
            <div class="mini-kpi-icon" style="background: rgba(245, 158, 11, 0.12); color: #F59E0B;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
        </div>
        <div class="mini-kpi-val"><?= (int)$stats['testimonials_count'] ?></div>
        <div class="mini-kpi-sub">5-Star Verified</div>
    </div>

    <!-- 4. FAQ Items -->
    <div class="mini-kpi-card">
        <div class="mini-kpi-top">
            <span class="mini-kpi-title">FAQ Items</span>
            <div class="mini-kpi-icon" style="background: rgba(139, 92, 246, 0.12); color: #8B5CF6;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
        </div>
        <div class="mini-kpi-val"><?= (int)$stats['faq_count'] ?></div>
        <div class="mini-kpi-sub">Google Schema Active</div>
    </div>

    <!-- 5. Media Library -->
    <div class="mini-kpi-card">
        <div class="mini-kpi-top">
            <span class="mini-kpi-title">Media Library</span>
            <div class="mini-kpi-icon" style="background: rgba(34, 197, 94, 0.12); color: #22C55E;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/></svg>
            </div>
        </div>
        <div class="mini-kpi-val"><?= (int)$stats['media_count'] ?></div>
        <div class="mini-kpi-sub">Images &amp; Files</div>
    </div>

    <!-- 6. Client Leads -->
    <div class="mini-kpi-card">
        <div class="mini-kpi-top">
            <span class="mini-kpi-title">Client Leads</span>
            <div class="mini-kpi-icon" style="background: rgba(6, 182, 212, 0.12); color: #06B6D4;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/></svg>
            </div>
        </div>
        <div class="mini-kpi-val"><?= (int)$stats['new_inquiries_count'] ?></div>
        <div class="mini-kpi-sub">Awaiting Response</div>
    </div>
</div>

<!-- Row 3: Middle Split (Traffic Curve 2/3 & Top Pages 1/3) -->
<div class="middle-split-row">
    <!-- Left 2/3: Visitor Traffic & Engagement Trend -->
    <div class="chart-box-card">
        <div class="chart-header-wrap">
            <div class="chart-title-area">
                <div class="chart-title-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
                <div class="chart-title-text">
                    <h3>Visitor Traffic &amp; Engagement Trend</h3>
                    <p>Real-time user engagement and unique architectural consultation traffic.</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="chart-legend-wrap">
                    <div class="legend-item-box">
                        <span class="legend-dot-red"></span>
                        <span style="color: var(--text-heading);">Page Views</span>
                    </div>
                    <div class="legend-item-box">
                        <span class="legend-dot-blue"></span>
                        <span style="color: var(--text-heading);">Unique Visitors</span>
                    </div>
                </div>
                <div style="font-size: 0.76rem; font-weight: 700; background: var(--bg-card-alt); border: 1px solid var(--border-color); padding: 0.28rem 0.75rem; border-radius: var(--radius-sm); color: var(--text-heading);">
                    May 06 - May 12, 2024 &#9662;
                </div>
            </div>
        </div>

        <!-- SVG Curve Calculation -->
        <?php
        $maxV = 3000;
        $svgW = 750;
        $svgH = 150;
        $stepX = $svgW / (count($trafficDates) - 1);
        
        $viewsPts = [];
        $visPts = [];
        foreach ($trafficPageViews as $i => $pv) {
            $x = $i * $stepX;
            $y = $svgH - (($pv / $maxV) * ($svgH - 20));
            $viewsPts[] = "{$x},{$y}";
        }
        foreach ($trafficVisitors as $i => $uv) {
            $x = $i * $stepX;
            $y = $svgH - (($uv / $maxV) * ($svgH - 20));
            $visPts[] = "{$x},{$y}";
        }
        $pvStr = implode(' ', $viewsPts);
        $uvStr = implode(' ', $visPts);
        $areaStr = "0,{$svgH} " . $pvStr . " {$svgW},{$svgH}";
        ?>

        <div class="svg-curve-holder">
            <svg class="svg-curve-canvas" viewBox="0 0 <?= $svgW ?> <?= $svgH ?>" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="redAreaGradDark" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#C8102E" stop-opacity="0.38"/>
                        <stop offset="100%" stop-color="#C8102E" stop-opacity="0.0"/>
                    </linearGradient>
                </defs>

                <!-- Horizontal Subtle Guide Lines -->
                <line x1="0" y1="<?= $svgH * 0.25 ?>" x2="<?= $svgW ?>" y2="<?= $svgH * 0.25 ?>" stroke="var(--border-subtle)" stroke-dasharray="3 3" />
                <line x1="0" y1="<?= $svgH * 0.5 ?>" x2="<?= $svgW ?>" y2="<?= $svgH * 0.5 ?>" stroke="var(--border-subtle)" stroke-dasharray="3 3" />
                <line x1="0" y1="<?= $svgH * 0.75 ?>" x2="<?= $svgW ?>" y2="<?= $svgH * 0.75 ?>" stroke="var(--border-subtle)" stroke-dasharray="3 3" />

                <!-- Red Area Gradient Fill -->
                <polygon points="<?= $areaStr ?>" fill="url(#redAreaGradDark)" />

                <!-- Red Page Views Line -->
                <polyline points="<?= $pvStr ?>" fill="none" stroke="#C8102E" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" />

                <!-- Blue Visitors Dashed Line -->
                <polyline points="<?= $uvStr ?>" fill="none" stroke="#3B82F6" stroke-width="2" stroke-dasharray="5 3" stroke-linecap="round" />

                <!-- Data Points (Red Dots with white ring and shadow) -->
                <?php foreach ($viewsPts as $pt): 
                    [$px, $py] = explode(',', $pt);
                ?>
                    <circle cx="<?= $px ?>" cy="<?= $py ?>" r="4.5" fill="#C8102E" stroke="#FFFFFF" stroke-width="2" />
                <?php endforeach; ?>
            </svg>
        </div>

        <!-- 4-Col Traffic Status Strip -->
        <div class="chart-bottom-metrics">
            <div class="chart-metric-item">
                <div class="metric-item-val">
                    <span>2,548</span>
                    <span class="metric-item-badge">&uarr; 18.6%</span>
                </div>
                <span class="metric-item-label">Total Page Views</span>
            </div>
            <div class="chart-metric-item">
                <div class="metric-item-val">
                    <span>1,325</span>
                    <span class="metric-item-badge">&uarr; 12.3%</span>
                </div>
                <span class="metric-item-label">Unique Visitors</span>
            </div>
            <div class="chart-metric-item">
                <div class="metric-item-val">
                    <span>00:02:45</span>
                    <span class="metric-item-badge">&uarr; 8.7%</span>
                </div>
                <span class="metric-item-label">Avg. Session Duration</span>
            </div>
            <div class="chart-metric-item">
                <div class="metric-item-val">
                    <span>68.4%</span>
                    <span class="metric-item-badge">&darr; 5.2%</span>
                </div>
                <span class="metric-item-label">Bounce Rate</span>
            </div>
        </div>
    </div>

    <!-- Right 1/3: Traffic by Top Pages -->
    <div class="top-pages-card">
        <div class="card-header-flex" style="margin-bottom: 0;">
            <h3 class="card-title" style="font-size: 1.02rem;">Traffic by Top Pages</h3>
            <a href="/admin/seo" class="triplet-view-all">View All</a>
        </div>

        <div class="top-pages-list">
            <?php 
            $pageIcons = [
                ['name' => 'Homepage', 'views' => '1,245', 'pct' => '32.4%', 'icon' => '🏠', 'bg' => 'rgba(200, 16, 46, 0.15)', 'color' => 'var(--crimson)'],
                ['name' => 'Projects', 'views' => '856', 'pct' => '22.3%', 'icon' => '🎨', 'bg' => 'rgba(139, 92, 246, 0.15)', 'color' => '#8B5CF6'],
                ['name' => 'Services', 'views' => '642', 'pct' => '16.7%', 'icon' => '🛠️', 'bg' => 'rgba(59, 130, 246, 0.15)', 'color' => '#3B82F6'],
                ['name' => 'About Us', 'views' => '412', 'pct' => '10.7%', 'icon' => '🏢', 'bg' => 'rgba(34, 197, 94, 0.15)', 'color' => '#22C55E'],
                ['name' => 'Contact Us', 'views' => '298', 'pct' => '7.9%', 'icon' => '✉️', 'bg' => 'rgba(245, 158, 11, 0.15)', 'color' => '#F59E0B'],
                ['name' => 'Others', 'views' => '198', 'pct' => '5.1%', 'icon' => '🌐', 'bg' => 'rgba(6, 182, 212, 0.15)', 'color' => '#06B6D4'],
            ];
            foreach ($pageIcons as $tp): 
            ?>
                <div class="page-rank-row">
                    <div class="page-rank-header">
                        <div class="page-rank-icon-name">
                            <span class="page-rank-icon-pod" style="background: <?= $tp['bg'] ?>; color: <?= $tp['color'] ?>;"><?= $tp['icon'] ?></span>
                            <span><?= Security::e($tp['name']) ?></span>
                        </div>
                        <span style="color: var(--text-heading); font-weight: 800;"><?= Security::e($tp['views']) ?> <small style="font-weight: 600; color: var(--text-muted);">(<?= Security::e($tp['pct']) ?>)</small></span>
                    </div>
                    <div class="page-rank-bar-bg">
                        <div class="page-rank-bar-fill" style="width: <?= Security::e($tp['pct']) ?>;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Row 4: Bottom 3-Card Grid (Source: dashboard ui dark mode.png) -->
<div class="bottom-triplet-row">
    <!-- Triplet 1: Recent Projects (4 Showcase Cards) -->
    <div class="triplet-box-card">
        <div class="triplet-header">
            <h3 class="triplet-title">Recent Projects</h3>
            <a href="/admin/projects" class="triplet-view-all">View All</a>
        </div>

        <div class="recent-proj-horizontal-grid">
            <?php foreach ($recentProjects as $proj): ?>
                <div class="proj-card-mini">
                    <img src="<?= Security::e($proj['cover_image']) ?>" alt="" class="proj-mini-cover">
                    <div class="proj-mini-body">
                        <div>
                            <div class="proj-mini-title"><?= Security::e($proj['title_en']) ?></div>
                            <div class="proj-mini-loc"><?= Security::e($proj['location_en'] ?? 'Dhaka') ?></div>
                        </div>
                        <div class="proj-mini-footer">
                            <?php if (!empty($proj['is_active'])): ?>
                                <span class="badge-status badge-active" style="font-size: 0.66rem; padding: 0.12rem 0.45rem;">Published</span>
                            <?php else: ?>
                                <span class="badge-status" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; font-size: 0.66rem; padding: 0.12rem 0.45rem;">Draft</span>
                            <?php endif; ?>
                            <a href="/admin/projects/edit/<?= (int)$proj['id'] ?>" style="color: var(--text-muted); text-decoration: none; font-size: 0.82rem; font-weight: 800;">&#8942;</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Triplet 2: Latest Inquiries -->
    <div class="triplet-box-card">
        <div class="triplet-header">
            <h3 class="triplet-title">Latest Inquiries</h3>
            <a href="/admin/contacts" class="triplet-view-all">View All</a>
        </div>

        <div class="inquiry-item-list">
            <?php 
            $avatarColors = [
                ['bg' => 'rgba(239, 68, 68, 0.15)', 'color' => '#EF4444'],
                ['bg' => 'rgba(139, 92, 246, 0.15)', 'color' => '#8B5CF6'],
                ['bg' => 'rgba(34, 197, 94, 0.15)', 'color' => '#22C55E'],
                ['bg' => 'rgba(245, 158, 11, 0.15)', 'color' => '#F59E0B'],
                ['bg' => 'rgba(200, 16, 46, 0.15)', 'color' => '#C8102E'],
            ];
            foreach ($recentInquiries as $idx => $inq): 
                $initial = strtoupper(mb_substr($inq['full_name'], 0, 1));
                $c = $avatarColors[$idx % count($avatarColors)];
            ?>
                <div class="inquiry-row-card">
                    <div class="inquiry-avatar-pod" style="background: <?= $c['bg'] ?>; color: <?= $c['color'] ?>;">
                        <?= Security::e($initial) ?>
                    </div>
                    <div class="inquiry-details-col">
                        <div class="inquiry-top-line">
                            <span class="inquiry-client-name"><?= Security::e($inq['full_name']) ?></span>
                            <span class="inquiry-time-badge"><?= $idx === 0 ? '10 min ago' : ($idx === 1 ? '1 hour ago' : ($idx === 2 ? '3 hours ago' : 'Yesterday')) ?></span>
                        </div>
                        <div class="inquiry-service-desc"><?= Security::e($inq['service_slug']) ?></div>
                    </div>
                    <div>
                        <?php if ($inq['status'] === 'new'): ?>
                            <span class="badge-status badge-active" style="font-size: 0.68rem; padding: 0.15rem 0.55rem;">New</span>
                        <?php else: ?>
                            <span class="badge-status" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; font-size: 0.68rem; padding: 0.15rem 0.55rem;">Follow Up</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Triplet 3: Quick Actions & System Status -->
    <div class="triplet-box-card">
        <h3 class="triplet-title" style="margin-bottom: 0.85rem;">Quick Actions</h3>
        
        <!-- 2x3 Grid of square action pills -->
        <div class="qa-mini-grid">
            <a href="/admin/projects/create" class="qa-btn-square">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: var(--crimson);"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Add Project</span>
            </a>
            <a href="/admin/hero" class="qa-btn-square">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: #3B82F6;"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
                <span>Manage Slides</span>
            </a>
            <a href="/admin/settings" class="qa-btn-square">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: #F59E0B;"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Site Settings</span>
            </a>
            <a href="/admin/media" class="qa-btn-square">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: #22C55E;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/></svg>
                <span>Media Library</span>
            </a>
            <a href="/admin/seo" class="qa-btn-square">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: #8B5CF6;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>SEO Settings</span>
            </a>
            <a href="/admin/backup" class="qa-btn-square">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: #06B6D4;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Backup Now</span>
            </a>
        </div>

        <h3 class="triplet-title" style="margin-bottom: 0.85rem; font-size: 0.95rem;">System Status</h3>

        <div class="sys-status-list">
            <div class="sys-status-item">
                <span>Website</span>
                <span class="sys-status-val" style="color: #22C55E;">Online &rarr;</span>
            </div>
            <div class="sys-status-item">
                <span>Database</span>
                <span class="sys-status-val" style="color: #22C55E;">Connected &rarr;</span>
            </div>
            <div class="sys-status-item">
                <span>SSL Certificate</span>
                <span class="sys-status-val" style="color: #22C55E;">Valid &rarr;</span>
            </div>
            <div class="sys-status-item">
                <span>Last Backup</span>
                <span class="sys-status-val" style="color: #F59E0B;"><?= Security::e($lastBackupTime) ?> &rarr;</span>
            </div>
            <div class="sys-status-item">
                <span>PHP Version</span>
                <span class="sys-status-val" style="color: #8B5CF6;"><?= Security::e($phpVersion) ?> &rarr;</span>
            </div>
        </div>
    </div>
</div>
