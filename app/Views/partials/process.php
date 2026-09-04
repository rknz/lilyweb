<?php

/**
 * Approved 4-Step Process matching FINAL_DESKTOP_UI.png (Horizontal Connected Stepper with dashed line).
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();
$bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
$toBnDigits = function ($num) use ($bengaliDigits) {
    return preg_replace_callback('/\d/', fn($m) => $bengaliDigits[(int)$m[0]], (string)$num);
};

$steps = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM `lilyweb_process_steps` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `step_number` ASC");
    $dbSteps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dbSteps as $row) {
        $stepStr = (string)$row['step_number'];
        $stepDisplay = $isBn ? $toBnDigits($stepStr) : $stepStr;

        $title = $isBn ? ($row['title_bn'] ?: $row['title_en']) : $row['title_en'];
        $desc = $isBn ? ($row['description_bn'] ?: $row['description_en']) : $row['description_en'];

        $steps[] = [
            'num' => $stepDisplay,
            'title' => $title,
            'desc' => $desc,
            'icon' => $row['icon_svg'],
        ];
    }
} catch (\Throwable $e) {
    // Fail-safe
}

if (empty($steps)) {
    $steps = [
        [
            'num' => $isBn ? '০১' : '01',
            'title' => $isBn ? 'কনসালটেশন' : 'Consultation',
            'desc' => $isBn ? 'আপনার চাহিদা, বাজেট ও স্থান নিরীক্ষণ করে প্রাথমিক পরিকল্পনা নির্ধারণ।' : 'Understanding your vision, space requirements, lifestyle and budget expectations.',
            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
        ],
    ];
}
?>
<section class="section process" id="process" aria-labelledby="process-title">
    <div class="site-wrapper">
        <div class="section-header-center" data-reveal>
            <span class="section-kicker"><?= Lang::get('process_kicker') ?></span>
            <h2 id="process-title" class="section-h2"><?= Lang::get('process_h2') ?></h2>
        </div>

        <div class="process-timeline" data-reveal>
            <div class="timeline-track" aria-hidden="true"></div>
            
            <div class="process-nodes">
                <?php foreach ($steps as $step): ?>
                    <div class="process-node">
                        <div class="node-icon-circle" aria-hidden="true">
                            <?= Security::sanitizeSvg($step['icon']) ?>
                        </div>
                        <div class="node-content">
                            <div class="node-header">
                                <span class="node-num"><?= Security::e($step['num']) ?>.</span>
                                <span class="node-title"><?= Security::e($step['title']) ?></span>
                            </div>
                            <p class="node-desc"><?= Security::e($step['desc']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
