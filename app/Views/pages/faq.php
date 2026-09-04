<?php

/**
 * Frequently Asked Questions (FAQ) Page.
 * Fully localized for English & Bengali.
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\View;
use Lilyweb\Core\Database;

$isBn = Lang::isBn();

$faqs = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM `lilyweb_faqs` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    $dbFaqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dbFaqs as $row) {
        $q = $isBn ? ($row['question_bn'] ?: $row['question_en']) : $row['question_en'];
        $a = $isBn ? ($row['answer_bn'] ?: $row['answer_en']) : $row['answer_en'];

        $faqs[] = [
            'q' => $q,
            'a' => $a,
        ];
    }
} catch (\Throwable $e) {
    // Fail-safe
}

if (empty($faqs)) {
    $faqs = [
        [
            'q' => $isBn ? 'লিলি ইন্টেরিয়র্সের প্রজেক্ট বাস্তবায়নে কেমন সময় লাগে?' : 'How long does a typical interior design project take?',
            'a' => $isBn 
                ? 'প্রজেক্টের পরিধি এবং সাইজের উপর নির্ভর করে সময় নির্ধারিত হয়। সাধারণত একটি স্ট্যান্ডার্ড অ্যাপার্টমেন্ট সম্পন্ন করতে ৪৫ থেকে ৭৫ কার্যদিবস সময় লাগে।'
                : 'Project timelines depend on the square footage and custom scope. Typically, a standard residential apartment takes between 45 to 75 working days from design approval to final handover.',
        ],
    ];
}
?>
<section class="section faq-page-header">
    <div class="site-wrapper">
        <div class="section-header-center">
            <span class="section-kicker"><?= $isBn ? 'সাধারণ জিজ্ঞাসা' : 'FREQUENTLY ASKED QUESTIONS' ?></span>
            <h1 class="section-h2"><?= $isBn ? 'আপনার প্রশ্নের সহজ সমাধান' : 'Answers To Common Questions' ?></h1>
            <p class="hero-sub" style="margin: 0.8rem auto 0; max-width: 58ch;">
                <?= $isBn 
                    ? 'আমাদের কাজের প্রক্রিয়া, খরচ এবং সময়সীমা সম্পর্কিত যেকোনো তথ্যের জন্য নিচের প্রশ্নোত্তরগুলো দেখুন।' 
                    : 'Everything you need to know about our interior design workflow, cost estimations, timelines, and handover standards.' ?>
            </p>
        </div>

        <div class="faq-accordion-container" style="max-width: 800px; margin: 3rem auto 0;">
            <?php foreach ($faqs as $fIdx => $faq): ?>
                <details class="faq-item-card" <?= $fIdx === 0 ? 'open' : '' ?> style="background: var(--surface-cool); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.4rem 1.6rem; margin-bottom: 1rem; cursor: pointer; transition: all var(--speed) var(--ease);">
                    <summary style="font-weight: 700; font-size: 1.05rem; color: var(--charcoal-deep); list-style: none; display: flex; justify-content: space-between; align-items: center;">
                        <span><?= View::e($faq['q']) ?></span>
                        <span class="faq-plus" style="font-size: 1.3rem; color: var(--crimson); font-weight: 400;">&plus;</span>
                    </summary>
                    <p style="margin-top: 1rem; color: var(--charcoal-text); line-height: 1.7; font-size: 0.95rem; border-top: 1px solid var(--border-subtle); padding-top: 0.9rem;">
                        <?= View::e($faq['a']) ?>
                    </p>
                </details>
            <?php endforeach; ?>
        </div>

        </div>
    </div>
</section>

<!-- About Us Authority Snapshot -->
<?= View::renderPartial('partials.about') ?>

<!-- Trust & Achievement Statistics -->
<?= View::renderPartial('partials.stats') ?>

<!-- FAQPage JSON-LD Schema Markup for Search Rich Snippets -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php foreach ($faqs as $i => $faq): ?>
    {
      "@type": "Question",
      "name": <?= json_encode($faq['q']) ?>,
      "acceptedAnswer": {
        "@type": "Answer",
        "text": <?= json_encode($faq['a']) ?>
      }
    }<?= $i < count($faqs) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

