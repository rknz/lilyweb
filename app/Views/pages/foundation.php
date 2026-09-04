<?php

/**
 * Foundation status page (dev/test aid).
 *
 * @var bool   $dbOk
 * @var string $dbMessage
 * @var string $sessionName
 */

use Lilyweb\Core\View;
?>
<section>
    <h1>Foundation Check</h1>
    <ul>
        <li>Application boot: <strong>OK</strong></li>
        <li>Database connection: <strong><?= $dbOk ? 'OK (' . View::e($dbMessage) . ')' : 'FAILED — ' . View::e($dbMessage) ?></strong></li>
        <li>Session namespace: <strong><?= View::e($sessionName) ?></strong> (isolated from Profixapp's PHPSESSID)</li>
    </ul>
</section>
