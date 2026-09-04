<?php

/**
 * Flash notification toast (success / error).
 *
 * Reads session flash messages and renders a dismissible toast.
 * Works without JS (falls back to a static banner) and auto-hides after 6s with JS.
 */
use Lilyweb\Core\Session;
use Lilyweb\Core\Lang;
use Lilyweb\Core\View as V;

$success = Session::getFlash('success');
$error   = Session::getFlash('error');
$toastMsg = $success ?: $error;
$toastType = $success ? 'success' : ($error ? 'error' : '');
if ($success || $error) {
    Session::flush();
}
$toastMsg = is_string($toastMsg) ? V::e($toastMsg) : '';
$toastIcon = $toastType === 'success'
    ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
    : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
?>
<?php if (!empty($toastMsg)): ?>
<div id="notification-toast" class="notification-toast notification-toast--<?= $toastType ?>" role="status" aria-live="polite">
    <span class="notification-toast-icon"><?= $toastIcon ?></span>
    <span class="notification-toast-msg"><?= $toastMsg ?></span>
    <button type="button" class="notification-toast-close" id="toast-close-btn" aria-label="Dismiss">&times;</button>
</div>
<?php endif; ?>
