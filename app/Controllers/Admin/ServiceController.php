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

final class ServiceController extends AdminController
{
    /**
     * List all core services.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_services` ORDER BY `sort_order` ASC, `id` ASC");
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.services.index', [
            'services' => $services,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Core Services Management',
            'pageHeading' => 'Core Services Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for a service.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_services` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$service) {
            Session::flash('error', 'Service not found.');
            return Response::redirect('/admin/services');
        }

        $content = View::make('admin.services.form', [
            'service' => $service,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit Service — ' . $service['title_en'],
            'pageHeading' => 'Edit Service — ' . $service['title_en'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new service.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $service = [
            'id' => 0,
            'slug' => '',
            'title_en' => '',
            'title_bn' => '',
            'tag_badge_en' => 'Bespoke',
            'tag_badge_bn' => '',
            'summary_en' => '',
            'summary_bn' => '',
            'description_en' => '',
            'description_bn' => '',
            'icon_svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>',
            'sort_order' => 10,
            'is_active' => 1,
        ];

        $content = View::make('admin.services.form', [
            'service' => $service,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New Service',
            'pageHeading' => 'Add New Service',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update service.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $titleEn = (string) Request::post('title_en', '');
        $titleBn = (string) Request::post('title_bn', '');
        $slug = (string) Request::post('slug', '');
        if (trim($slug) === '') {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($titleEn)));
        }

        $tagBadgeEn = (string) Request::post('tag_badge_en', '');
        $tagBadgeBn = (string) Request::post('tag_badge_bn', '');
        $summaryEn = (string) Request::post('summary_en', '');
        $summaryBn = (string) Request::post('summary_bn', '');
        $descriptionEn = (string) Request::post('description_en', '');
        $descriptionBn = (string) Request::post('description_bn', '');
        $iconSvg = (string) Request::post('icon_svg', '');
        $sortOrder = (int) Request::post('sort_order', 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);

        if (trim($titleEn) === '' || trim($summaryEn) === '') {
            Session::flash('error', 'English title and summary are required.');
            return Response::redirect($id > 0 ? "/admin/services/edit/{$id}" : '/admin/services/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_services` SET
                        `slug` = :slug,
                        `title_en` = :te,
                        `title_bn` = :tb,
                        `tag_badge_en` = :be,
                        `tag_badge_bn` = :bb,
                        `summary_en` = :se,
                        `summary_bn` = :sb,
                        `description_en` = :de,
                        `description_bn` = :db,
                        `icon_svg` = :icon,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':slug' => $slug,
                    ':te' => $titleEn,
                    ':tb' => $titleBn ?: null,
                    ':be' => $tagBadgeEn,
                    ':bb' => $tagBadgeBn ?: null,
                    ':se' => $summaryEn,
                    ':sb' => $summaryBn ?: null,
                    ':de' => $descriptionEn ?: null,
                    ':db' => $descriptionBn ?: null,
                    ':icon' => $iconSvg,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'service', (string) $id, Request::ip());
                Session::flash('success', "Service '{$titleEn}' updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_services`
                    (`slug`, `title_en`, `title_bn`, `tag_badge_en`, `tag_badge_bn`, `summary_en`, `summary_bn`, `description_en`, `description_bn`, `icon_svg`, `sort_order`, `is_active`)
                    VALUES (:slug, :te, :tb, :be, :bb, :se, :sb, :de, :db, :icon, :sort, :active)
                ");
                $stmt->execute([
                    ':slug' => $slug,
                    ':te' => $titleEn,
                    ':tb' => $titleBn ?: null,
                    ':be' => $tagBadgeEn,
                    ':bb' => $tagBadgeBn ?: null,
                    ':se' => $summaryEn,
                    ':sb' => $summaryBn ?: null,
                    ':de' => $descriptionEn ?: null,
                    ':db' => $descriptionBn ?: null,
                    ':icon' => $iconSvg,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'service', (string) $newId, Request::ip());
                Session::flash('success', "New service '{$titleEn}' created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save service: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/services/edit/{$id}" : '/admin/services/create');
        }

        return Response::redirect('/admin/services');
    }

    /**
     * Delete a service.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_services` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'service', (string) $id, Request::ip());
            Session::flash('success', 'Service deleted successfully.');
        }

        return Response::redirect('/admin/services');
    }
}
