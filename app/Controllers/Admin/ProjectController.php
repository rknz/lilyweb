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

final class ProjectController extends AdminController
{
    /**
     * List all projects.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("
            SELECT p.*, c.name_en AS category_name
            FROM `lilyweb_projects` p
            LEFT JOIN `lilyweb_project_categories` c ON c.slug = p.room_type_key
            ORDER BY p.sort_order ASC, p.id ASC
        ");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.projects.index', [
            'projects' => $projects,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Portfolio Projects Management',
            'pageHeading' => 'Portfolio Projects Management (' . count($projects) . ' Projects)',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for a project.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_projects` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            Session::flash('error', 'Project not found.');
            return Response::redirect('/admin/projects');
        }

        // Fetch categories for dropdown
        $catStmt = $pdo->query("SELECT * FROM `lilyweb_project_categories` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.projects.form', [
            'project' => $project,
            'categories' => $categories,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit Project — ' . $project['title_en'],
            'pageHeading' => 'Edit Project: ' . $project['title_en'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new project.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $catStmt = $pdo->query("SELECT * FROM `lilyweb_project_categories` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        $project = [
            'id' => 0,
            'slug' => '',
            'title_en' => '',
            'title_bn' => '',
            'room_type_key' => 'living_room',
            'property_type_key' => 'residential',
            'location_en' => 'Gulshan 2, Dhaka',
            'location_bn' => '',
            'client_name' => 'Private Client',
            'completion_year' => '2026',
            'area_sqft' => '3,200 sqft',
            'room_details_en' => '4 Bed, 5 Bath, Living, Dining',
            'room_details_bn' => '',
            'design_style_en' => 'Modern Luxury Minimalism',
            'design_style_bn' => '',
            'project_status' => 'Completed',
            'summary_en' => '',
            'summary_bn' => '',
            'description_en' => '',
            'description_bn' => '',
            'cover_image' => '/assets/img/project-1.jpg',
            'gallery_json' => '["/assets/img/project-1.jpg", "/assets/img/project-2.jpg", "/assets/img/project-3.jpg"]',
            'show_on_home' => 1,
            'is_featured' => 1,
            'sort_order' => 10,
            'is_active' => 1,
        ];

        $content = View::make('admin.projects.form', [
            'project' => $project,
            'categories' => $categories,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New Portfolio Project',
            'pageHeading' => 'Add New Project',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update project.
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

        $roomTypeKey = (string) Request::post('room_type_key', 'living_room');
        $propertyTypeKey = (string) Request::post('property_type_key', 'residential');
        $locationEn = (string) Request::post('location_en', '');
        $locationBn = (string) Request::post('location_bn', '');
        $clientName = (string) Request::post('client_name', 'Private Client');
        $completionYear = (string) Request::post('completion_year', '2026');
        $areaSqft = (string) Request::post('area_sqft', '2,800 sqft');
        $roomDetailsEn = (string) Request::post('room_details_en', '4 Bed, 5 Bath');
        $roomDetailsBn = (string) Request::post('room_details_bn', '');
        $designStyleEn = (string) Request::post('design_style_en', 'Modern Luxury Minimalism');
        $designStyleBn = (string) Request::post('design_style_bn', '');
        $projectStatus = (string) Request::post('project_status', 'Completed');
        $summaryEn = (string) Request::post('summary_en', '');
        $summaryBn = (string) Request::post('summary_bn', '');
        $descriptionEn = (string) Request::post('description_en', '');
        $descriptionBn = (string) Request::post('description_bn', '');
        $coverImage = (string) Request::post('cover_image', '/assets/img/project-1.jpg');
        $galleryJson = (string) Request::post('gallery_json', '[]');
        $showOnHome = (int) (Request::post('show_on_home') ? 1 : 0);
        $isFeatured = (int) (Request::post('is_featured') ? 1 : 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);
        $sortOrder = (int) Request::post('sort_order', 0);

        if (trim($titleEn) === '' || trim($summaryEn) === '') {
            Session::flash('error', 'English title and summary are required.');
            return Response::redirect($id > 0 ? "/admin/projects/edit/{$id}" : '/admin/projects/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_projects` SET
                        `slug` = :slug,
                        `title_en` = :te,
                        `title_bn` = :tb,
                        `summary_en` = :se,
                        `summary_bn` = :sb,
                        `description_en` = :de,
                        `description_bn` = :db,
                        `location_en` = :le,
                        `location_bn` = :lb,
                        `client_name` = :cname,
                        `completion_year` = :year,
                        `area_sqft` = :area,
                        `room_details_en` = :rde,
                        `room_details_bn` = :rdb,
                        `design_style_en` = :dse,
                        `design_style_bn` = :dsb,
                        `project_status` = :pstatus,
                        `cover_image` = :cover,
                        `gallery_json` = :gallery,
                        `room_type_key` = :rtk,
                        `property_type_key` = :ptk,
                        `show_on_home` = :soh,
                        `is_featured` = :feat,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':slug' => $slug,
                    ':te' => $titleEn,
                    ':tb' => $titleBn ?: null,
                    ':se' => $summaryEn,
                    ':sb' => $summaryBn ?: null,
                    ':de' => $descriptionEn ?: null,
                    ':db' => $descriptionBn ?: null,
                    ':le' => $locationEn,
                    ':lb' => $locationBn ?: null,
                    ':cname' => $clientName,
                    ':year' => $completionYear,
                    ':area' => $areaSqft,
                    ':rde' => $roomDetailsEn,
                    ':rdb' => $roomDetailsBn ?: null,
                    ':dse' => $designStyleEn,
                    ':dsb' => $designStyleBn ?: null,
                    ':pstatus' => $projectStatus,
                    ':cover' => $coverImage,
                    ':gallery' => $galleryJson,
                    ':rtk' => $roomTypeKey,
                    ':ptk' => $propertyTypeKey,
                    ':soh' => $showOnHome,
                    ':feat' => $isFeatured,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'project', (string) $id, Request::ip());
                Session::flash('success', "Project '{$titleEn}' updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_projects`
                    (`slug`, `title_en`, `title_bn`, `summary_en`, `summary_bn`, `description_en`, `description_bn`, `location_en`, `location_bn`, `client_name`, `completion_year`, `area_sqft`, `room_details_en`, `room_details_bn`, `design_style_en`, `design_style_bn`, `project_status`, `cover_image`, `gallery_json`, `room_type_key`, `property_type_key`, `show_on_home`, `is_featured`, `sort_order`, `is_active`)
                    VALUES (:slug, :te, :tb, :se, :sb, :de, :db, :le, :lb, :cname, :year, :area, :rde, :rdb, :dse, :dsb, :pstatus, :cover, :gallery, :rtk, :ptk, :soh, :feat, :sort, :active)
                ");
                $stmt->execute([
                    ':slug' => $slug,
                    ':te' => $titleEn,
                    ':tb' => $titleBn ?: null,
                    ':se' => $summaryEn,
                    ':sb' => $summaryBn ?: null,
                    ':de' => $descriptionEn ?: null,
                    ':db' => $descriptionBn ?: null,
                    ':le' => $locationEn,
                    ':lb' => $locationBn ?: null,
                    ':cname' => $clientName,
                    ':year' => $completionYear,
                    ':area' => $areaSqft,
                    ':rde' => $roomDetailsEn,
                    ':rdb' => $roomDetailsBn ?: null,
                    ':dse' => $designStyleEn,
                    ':dsb' => $designStyleBn ?: null,
                    ':pstatus' => $projectStatus,
                    ':cover' => $coverImage,
                    ':gallery' => $galleryJson,
                    ':rtk' => $roomTypeKey,
                    ':ptk' => $propertyTypeKey,
                    ':soh' => $showOnHome,
                    ':feat' => $isFeatured,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'project', (string) $newId, Request::ip());
                Session::flash('success', "New project '{$titleEn}' created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save project: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/projects/edit/{$id}" : '/admin/projects/create');
        }

        return Response::redirect('/admin/projects');
    }

    /**
     * Delete a project.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_projects` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'project', (string) $id, Request::ip());
            Session::flash('success', 'Project deleted successfully.');
        }

        return Response::redirect('/admin/projects');
    }
}
