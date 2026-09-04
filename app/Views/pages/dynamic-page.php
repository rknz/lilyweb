<?php
/**
 * Dynamic Content Page - Bilingual (English / Bengali).
 * Renders the localized title and body from the lilyweb_pages table.
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\View;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();
$pageTitle = $pageTitle ?? 'Untitled Page';
$pageContent = $pageContent ?? '';
$lastUpdated = !empty($page['updated_at']) ? date('F j, Y', strtotime($page['updated_at'])) : '';

$blocks = [];
$buffer = [];
$flush = function () use (&$buffer, &$blocks) {
    if (count($buffer) === 0) {
        return;
    }
    $text = trim(implode(PHP_EOL, $buffer));
    $buffer = [];
    if ($text === '') {
        return;
    }
    $escaped = Security::e($text);
    $escaped = str_replace(["\r\n", "\r"], "\n", $escaped);
    $escaped = str_replace("\n", '<br>', $escaped);
    $blocks[] = '<p>' . $escaped . '</p>';
};

foreach (preg_split('/\R/u', $pageContent) ?: [] as $line) {
    $trimmed = trim($line);
    if (preg_match('/^(#{1,3})\s+(.+)$/u', $trimmed, $m)) {
        $flush();
        $level = strlen($m[1]);
        $tag = 'h' . $level;
        $blocks[] = '<' . $tag . ' class="page-h' . $level . '">' . Security::e($m[2]) . '</' . $tag . '>';
    } elseif ($trimmed === '') {
        $flush();
    } else {
        $buffer[] = $line;
    }
}
$flush();
?>
<style>
    .page-hero {
        padding: 5rem 0 3.5rem;
        background: radial-gradient(circle at 50% 0%, rgba(200, 16, 46, 0.08) 0%, transparent 70%), var(--surface-cool, #F7F8F8);
        border-bottom: 1px solid var(--border-subtle, #E8E9EA);
        position: relative;
        text-align: center;
    }

    .page-badge-pill {
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

    .page-title {
        font-size: clamp(2.2rem, 4.5vw, 3.2rem);
        font-weight: 800;
        color: var(--charcoal-deep, #111111);
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin-bottom: 0.85rem;
        font-family: var(--font-heading, 'Playfair Display', serif);
    }

    .page-meta-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        font-size: 0.88rem;
        color: var(--charcoal-muted, #62666B);
        flex-wrap: wrap;
    }

    .page-content-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 4rem 0 6rem;
    }

    .page-article {
        background: var(--surface-white, #FFFFFF);
        border: 1.5px solid var(--border-subtle, #E8E9EA);
        border-radius: 20px;
        padding: 2.8rem clamp(1.4rem, 4vw, 3.2rem);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
        color: var(--charcoal-text, #26282C);
        font-size: 1.02rem;
        line-height: 1.85;
    }

    .page-article p {
        margin: 0 0 1.15rem;
    }

    .page-h1 {
        font-size: 1.85rem;
        color: var(--charcoal-deep, #111111);
        margin: 1.75rem 0 1rem;
        line-height: 1.3;
        letter-spacing: -0.015em;
    }

    .page-h2 {
        font-size: 1.45rem;
        color: var(--charcoal-deep, #111111);
        margin: 2rem 0 0.9rem;
        line-height: 1.3;
        letter-spacing: -0.01em;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-subtle, #E8E9EA);
    }

    .page-h3 {
        font-size: 1.2rem;
        color: var(--charcoal-deep, #111111);
        margin: 1.6rem 0 0.75rem;
        line-height: 1.35;
    }

    .page-article a {
        color: #C8102E;
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    html[data-theme="dark"] .page-title,
    html[data-theme="dark"] .page-h1,
    html[data-theme="dark"] .page-h2,
    html[data-theme="dark"] .page-h3 {
        color: #F8FAFC;
    }

    html[data-theme="dark"] .page-article {
        background: #10151F;
        border-color: rgba(255, 255, 255, 0.1);
        color: #D7DBE3;
    }

    html[data-theme="dark"] .page-article a {
        color: #FF4D6A;
    }

    @media (max-width: 768px) {
        .page-article {
            padding: 1.8rem 1.3rem;
            border-radius: 14px;
        }

        .page-h1 { font-size: 1.5rem; }
        .page-h2 { font-size: 1.25rem; }
    }
</style>

<header class="page-hero">
    <div class="site-wrapper">
        <div class="page-badge-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span><?= $isBn ? 'অফিসিয়াল পেজ' : 'Official Page' ?></span>
        </div>
        <h1 class="page-title"><?= View::e($pageTitle) ?></h1>
        <div class="page-meta-bar">
            <span><?= $isBn ? 'সর্বশেষ আপডেট' : 'Last Updated' ?>: <?= View::e($lastUpdated ?: 'Today') ?></span>
        </div>
    </div>
</header>

<div class="site-wrapper">
    <div class="page-content-wrap">
        <article class="page-article">
            <?php if (count($blocks) === 0): ?>
                <p><?= View::e($pageContent) ?></p>
            <?php else: ?>
                <?= implode("\n", $blocks) ?>
            <?php endif; ?>
        </article>
    </div>
</div>