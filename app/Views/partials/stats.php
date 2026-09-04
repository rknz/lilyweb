<?php

/**
 * Trust & Achievement Stats Strip matching FINAL_DESKTOP_UI.png (5 Framed Cards).
 */
use Lilyweb\Core\Lang;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;

$isBn = Lang::isBn();
$bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
$toBnDigits = function ($num) use ($bengaliDigits) {
    return preg_replace_callback('/\d/', fn($m) => $bengaliDigits[(int)$m[0]], (string)$num);
};

$stats = [];
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM `lilyweb_stats` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    $dbStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dbStats as $row) {
        $valNum = (int)$row['value_number'];
        $suffix = (string)$row['suffix'];
        $label = $isBn ? ($row['label_bn'] ?: $row['label_en']) : $row['label_en'];
        $displayVal = ($isBn ? $toBnDigits($valNum) : $valNum) . $suffix;

        $stats[] = [
            'icon' => $row['icon_svg'],
            'target' => $valNum,
            'suffix' => $suffix,
            'value' => $displayVal,
            'label' => $label,
        ];
    }
} catch (\Throwable $e) {
    // Fail-safe
}

if (empty($stats)) {
    $stats = [
        [
            'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            'target' => 250,
            'suffix' => '+',
            'value' => $isBn ? '২৫০+' : '250+',
            'label' => $isBn ? 'বাস্তবায়িত প্রজেক্ট' : 'Projects Completed',
        ],
    ];
}
?>
<section class="stats" aria-label="Achievement statistics">
    <div class="site-wrapper stats-grid">
        <?php foreach ($stats as $stat): ?>
            <div class="stat-card" data-reveal>
                <div class="stat-icon-wrap" aria-hidden="true">
                    <?= Security::sanitizeSvg($stat['icon']) ?>
                </div>
                <div class="stat-value" data-counter data-target="<?= Security::e($stat['target']) ?>" data-suffix="<?= Security::e($stat['suffix']) ?>">
                    <span class="counter-num"><?= Security::e($stat['value']) ?></span>
                </div>
                <div class="stat-label"><?= Security::e($stat['label']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>