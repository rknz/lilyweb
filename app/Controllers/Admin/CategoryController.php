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

final class CategoryController extends AdminController
{
    /**
     * List all project categories.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("
            SELECT c.*, (SELECT COUNT(*) FROM `lilyweb_projects` p WHERE p.room_type_key = c.slug OR p.property_type_key = c.slug) AS project_count
            FROM `lilyweb_project_categories` c
            ORDER BY c.sort_order ASC, c.id ASC
        ");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.categories.index', [
            'categories' => $categories,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Project Categories Management',
            'pageHeading' => 'Project Categories & Portfolio Filter Taxonomy',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for a category.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_project_categories` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            Session::flash('error', 'Category not found.');
            return Response::redirect('/admin/categories');
        }

        $content = View::make('admin.categories.form', [
            'category' => $category,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit Category — ' . $category['name_en'],
            'pageHeading' => 'Edit Category: ' . $category['name_en'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new category.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $category = [
            'id' => 0,
            'name_en' => '',
            'name_bn' => '',
            'slug' => '',
            'sort_order' => 10,
            'is_active' => 1,
        ];

        $content = View::make('admin.categories.form', [
            'category' => $category,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New Project Category',
            'pageHeading' => 'Add New Category',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update category.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $nameEn = (string) Request::post('name_en', '');
        $nameBn = (string) Request::post('name_bn', '');
        $slug = (string) Request::post('slug', '');
        if (trim($slug) === '') {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($nameEn)));
        }

        $sortOrder = (int) Request::post('sort_order', 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);

        if (trim($nameEn) === '') {
            Session::flash('error', 'English category name is required.');
            return Response::redirect($id > 0 ? "/admin/categories/edit/{$id}" : '/admin/categories/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_project_categories` SET
                        `name_en` = :ne,
                        `name_bn` = :nb,
                        `slug` = :slug,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':ne' => $nameEn,
                    ':nb' => $nameBn ?: null,
                    ':slug' => $slug,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'category', (string) $id, Request::ip());
                Session::flash('success', "Category '{$nameEn}' updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_project_categories`
                    (`name_en`, `name_bn`, `slug`, `sort_order`, `is_active`)
                    VALUES (:ne, :nb, :slug, :sort, :active)
                ");
                $stmt->execute([
                    ':ne' => $nameEn,
                    ':nb' => $nameBn ?: null,
                    ':slug' => $slug,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'category', (string) $newId, Request::ip());
                Session::flash('success', "New category '{$nameEn}' created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save category: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/categories/edit/{$id}" : '/admin/categories/create');
        }

        return Response::redirect('/admin/categories');
    }

    /**
     * Delete a category.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();

            // A project references a category by slug (room_type_key /
            // property_type_key). Resolve the category slug so the in-use
            // check matches the real schema instead of a non-existent
            // `category_id` column.
            $catStmt = $pdo->prepare("SELECT `slug`, `category_type` FROM `lilyweb_project_categories` WHERE `id` = :id LIMIT 1");
            $catStmt->execute([':id' => $id]);
            $cat = $catStmt->fetch(PDO::FETCH_ASSOC);

            if ($cat) {
                $slug = $cat['slug'];
                $checkStmt = $pdo->prepare(
                    "SELECT COUNT(*) FROM `lilyweb_projects` WHERE `room_type_key` = :slug OR `property_type_key` = :slug"
                );
                $checkStmt->execute([':slug' => $slug]);
                if ((int)$checkStmt->fetchColumn() > 0) {
                    Session::flash('error', 'Cannot delete this category because projects are currently assigned to it.');
                    return Response::redirect('/admin/categories');
                }
            }

            $stmt = $pdo->prepare("DELETE FROM `lilyweb_project_categories` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'category', (string) $id, Request::ip());
            Session::flash('success', 'Category deleted successfully.');
        }

        return Response::redirect('/admin/categories');
    }
}
