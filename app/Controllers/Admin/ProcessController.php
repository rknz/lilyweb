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

final class ProcessController extends AdminController
{
    /**
     * List all process steps.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_process_steps` ORDER BY `sort_order` ASC, `step_number` ASC");
        $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.process.index', [
            'steps' => $steps,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Process Steps Management',
            'pageHeading' => 'Execution Process Steps Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for a step.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_process_steps` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $step = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$step) {
            Session::flash('error', 'Process step not found.');
            return Response::redirect('/admin/process');
        }

        $content = View::make('admin.process.form', [
            'step' => $step,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit Step #' . $step['step_number'],
            'pageHeading' => 'Edit Step #' . $step['step_number'] . ' — ' . $step['title_en'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new step.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $step = [
            'id' => 0,
            'step_number' => '05',
            'title_en' => '',
            'title_bn' => '',
            'description_en' => '',
            'description_bn' => '',
            'icon_svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>',
            'sort_order' => 5,
            'is_active' => 1,
        ];

        $content = View::make('admin.process.form', [
            'step' => $step,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New Process Step',
            'pageHeading' => 'Add New Process Step',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update process step.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $stepNumber = (string) Request::post('step_number', '01');
        $titleEn = (string) Request::post('title_en', '');
        $titleBn = (string) Request::post('title_bn', '');
        $descriptionEn = (string) Request::post('description_en', '');
        $descriptionBn = (string) Request::post('description_bn', '');
        $iconSvg = (string) Request::post('icon_svg', '');
        $sortOrder = (int) Request::post('sort_order', 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);

        if (trim($titleEn) === '' || trim($descriptionEn) === '') {
            Session::flash('error', 'English title and description are required.');
            return Response::redirect($id > 0 ? "/admin/process/edit/{$id}" : '/admin/process/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_process_steps` SET
                        `step_number` = :step,
                        `title_en` = :te,
                        `title_bn` = :tb,
                        `description_en` = :de,
                        `description_bn` = :db,
                        `icon_svg` = :icon,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':step' => $stepNumber,
                    ':te' => $titleEn,
                    ':tb' => $titleBn ?: null,
                    ':de' => $descriptionEn,
                    ':db' => $descriptionBn ?: null,
                    ':icon' => $iconSvg,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'process_step', (string) $id, Request::ip());
                Session::flash('success', "Process step #{$stepNumber} updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_process_steps`
                    (`step_number`, `title_en`, `title_bn`, `description_en`, `description_bn`, `icon_svg`, `sort_order`, `is_active`)
                    VALUES (:step, :te, :tb, :de, :db, :icon, :sort, :active)
                ");
                $stmt->execute([
                    ':step' => $stepNumber,
                    ':te' => $titleEn,
                    ':tb' => $titleBn ?: null,
                    ':de' => $descriptionEn,
                    ':db' => $descriptionBn ?: null,
                    ':icon' => $iconSvg,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'process_step', (string) $newId, Request::ip());
                Session::flash('success', "New process step #{$stepNumber} created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save process step: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/process/edit/{$id}" : '/admin/process/create');
        }

        return Response::redirect('/admin/process');
    }

    /**
     * Delete a process step.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_process_steps` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'process_step', (string) $id, Request::ip());
            Session::flash('success', 'Process step deleted successfully.');
        }

        return Response::redirect('/admin/process');
    }
}
