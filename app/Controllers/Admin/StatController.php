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

final class StatController extends AdminController
{
    /**
     * List all achievement stats.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_stats` ORDER BY `sort_order` ASC, `id` ASC");
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.stats.index', [
            'stats' => $stats,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Achievement Stats Management',
            'pageHeading' => 'Achievement Stats Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for a stat.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_stats` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $stat = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stat) {
            Session::flash('error', 'Stat card not found.');
            return Response::redirect('/admin/stats');
        }

        $content = View::make('admin.stats.form', [
            'stat' => $stat,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit Stat — ' . $stat['label_en'],
            'pageHeading' => 'Edit Stat Card: ' . $stat['label_en'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new stat.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $stat = [
            'id' => 0,
            'stat_key' => 'stat_custom',
            'value_number' => 100,
            'suffix' => '+',
            'label_en' => '',
            'label_bn' => '',
            'icon_svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            'sort_order' => 6,
            'is_active' => 1,
        ];

        $content = View::make('admin.stats.form', [
            'stat' => $stat,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New Stat Card',
            'pageHeading' => 'Add New Stat Card',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update stat card.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $statKey = (string) Request::post('stat_key', 'stat_' . time());
        $valueNumber = (int) Request::post('value_number', 0);
        $suffix = (string) Request::post('suffix', '+');
        $labelEn = (string) Request::post('label_en', '');
        $labelBn = (string) Request::post('label_bn', '');
        $iconSvg = (string) Request::post('icon_svg', '');
        $sortOrder = (int) Request::post('sort_order', 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);

        if (trim($labelEn) === '') {
            Session::flash('error', 'English label is required.');
            return Response::redirect($id > 0 ? "/admin/stats/edit/{$id}" : '/admin/stats/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_stats` SET
                        `stat_key` = :sk,
                        `value_number` = :val,
                        `suffix` = :suf,
                        `label_en` = :le,
                        `label_bn` = :lb,
                        `icon_svg` = :icon,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':sk' => $statKey,
                    ':val' => $valueNumber,
                    ':suf' => $suffix,
                    ':le' => $labelEn,
                    ':lb' => $labelBn ?: null,
                    ':icon' => $iconSvg,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'stat_card', (string) $id, Request::ip());
                Session::flash('success', "Stat '{$labelEn}' updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_stats`
                    (`stat_key`, `value_number`, `suffix`, `label_en`, `label_bn`, `icon_svg`, `sort_order`, `is_active`)
                    VALUES (:sk, :val, :suf, :le, :lb, :icon, :sort, :active)
                ");
                $stmt->execute([
                    ':sk' => $statKey,
                    ':val' => $valueNumber,
                    ':suf' => $suffix,
                    ':le' => $labelEn,
                    ':lb' => $labelBn ?: null,
                    ':icon' => $iconSvg,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'stat_card', (string) $newId, Request::ip());
                Session::flash('success', "New stat card '{$labelEn}' created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save stat card: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/stats/edit/{$id}" : '/admin/stats/create');
        }

        return Response::redirect('/admin/stats');
    }

    /**
     * Delete a stat card.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_stats` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'stat_card', (string) $id, Request::ip());
            Session::flash('success', 'Stat card deleted successfully.');
        }

        return Response::redirect('/admin/stats');
    }
}
