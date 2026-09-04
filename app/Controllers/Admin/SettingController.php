<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Request;
use Lilyweb\Core\Response;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;
use Lilyweb\Core\Session;
use Lilyweb\Core\View;
use PDO;
use Exception;

final class SettingController extends AdminController
{
    /**
     * Show general settings, socials & owner security.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings`");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $owner = Auth::user();

        $content = View::make('admin.settings.index', [
            'settings' => $settings,
            'owner' => $owner,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Site & Brand Settings',
            'pageHeading' => 'General Settings, NAP Details & Security',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save site settings (NAP, Socials, Brand).
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $keys = [
            'site_name', 'site_tagline', 'phone_primary', 'phone_secondary',
            'email_primary', 'email_support', 'whatsapp_number', 'whatsapp_message',
            'address_en', 'address_bn', 'google_maps_embed',
            'social_facebook', 'social_instagram', 'social_linkedin', 'social_youtube', 'social_pinterest',
            'floating_whatsapp_enabled', 'business_hours_en', 'business_hours_bn',
        ];

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO `lilyweb_site_settings` (`setting_key`, `setting_value`, `setting_group`, `is_public`)
                VALUES (:k, :v, 'general', 1)
                ON DUPLICATE KEY UPDATE `setting_value` = :v2
            ");

            foreach ($keys as $k) {
                $val = (string) Request::post($k, '');
                $stmt->execute([
                    ':k' => $k,
                    ':v' => $val,
                    ':v2' => $val,
                ]);
            }

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'settings', 'general_settings', Request::ip());
            Session::flash('success', 'Site settings updated successfully and live across the entire website.');

        } catch (Exception $e) {
            Session::flash('error', 'Failed to update settings: ' . $e->getMessage());
        }

        return Response::redirect('/admin/settings');
    }

    /**
     * Update owner password / credentials.
     */
    public function updateOwner(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $displayName = trim((string) Request::post('display_name', ''));
        $email = trim((string) Request::post('email', ''));
        $currentPass = (string) Request::post('current_password', '');
        $newPass = (string) Request::post('new_password', '');
        $confirmPass = (string) Request::post('confirm_password', '');

        $ownerId = Auth::id();
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("SELECT `password_hash` FROM `lilyweb_users` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $ownerId]);
            $hash = $stmt->fetchColumn();

            // Update basic info
            if ($displayName !== '' || $email !== '') {
                $upd = $pdo->prepare("UPDATE `lilyweb_users` SET `display_name` = :dn, `email` = :em WHERE `id` = :id");
                $upd->execute([':dn' => $displayName ?: 'Lily Interiors Owner', ':em' => $email ?: 'admin@lilyinteriorsbd.com', ':id' => $ownerId]);
            }

            // Update password if requested
            if ($newPass !== '') {
                if (!password_verify($currentPass, (string) $hash)) {
                    Session::flash('error', 'Current password entered is incorrect.');
                    return Response::redirect('/admin/settings');
                }

                if (strlen($newPass) < 8) {
                    Session::flash('error', 'New password must be at least 8 characters long.');
                    return Response::redirect('/admin/settings');
                }

                if ($newPass !== $confirmPass) {
                    Session::flash('error', 'New password and confirmation do not match.');
                    return Response::redirect('/admin/settings');
                }

                $newHash = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
                $updPass = $pdo->prepare("UPDATE `lilyweb_users` SET `password_hash` = :hash WHERE `id` = :id");
                $updPass->execute([':hash' => $newHash, ':id' => $ownerId]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update_password', 'user', (string) $ownerId, Request::ip());
                Session::flash('success', 'Owner account password updated successfully.');
            } else {
                Session::flash('success', 'Owner profile information updated.');
            }

        } catch (Exception $e) {
            Session::flash('error', 'Failed to update owner credentials: ' . $e->getMessage());
        }

        return Response::redirect('/admin/settings');
    }
}
