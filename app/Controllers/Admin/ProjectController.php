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
            'area_sqft' => '2,800 sqft',
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
            'gallery_json' => '["/assets/img/project-1.jpg"]',
            'features_json' => '[]',
            'highlights_json' => '[]',
            'materials_json' => '[]',
            'proposal_pdf' => '',
            'show_on_home' => 1,
            'is_featured' => 0,
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
     * Save/update project with full gallery and spec parsing.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $titleEn = trim((string) Request::post('title_en', ''));
        $titleBn = trim((string) Request::post('title_bn', ''));
        $slug = trim((string) Request::post('slug', ''));
        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $titleEn));
            $slug = trim($slug, '-');
        }

        $roomTypeKey = (string) Request::post('room_type_key', 'living_room');
        $propertyTypeKey = (string) Request::post('property_type_key', 'residential');
        $locationEn = trim((string) Request::post('location_en', ''));
        $locationBn = trim((string) Request::post('location_bn', ''));
        $clientName = trim((string) Request::post('client_name', 'Private Client'));
        $completionYear = trim((string) Request::post('completion_year', '2026'));
        $areaSqft = trim((string) Request::post('area_sqft', '2,800 sqft'));
        $roomDetailsEn = trim((string) Request::post('room_details_en', '4 Bed, 5 Bath'));
        $roomDetailsBn = trim((string) Request::post('room_details_bn', ''));
        $designStyleEn = trim((string) Request::post('design_style_en', 'Modern Luxury Minimalism'));
        $designStyleBn = trim((string) Request::post('design_style_bn', ''));
        $projectStatus = (string) Request::post('project_status', 'Completed');
        $summaryEn = trim((string) Request::post('summary_en', ''));
        $summaryBn = trim((string) Request::post('summary_bn', ''));
        $descriptionEn = trim((string) Request::post('description_en', ''));
        $descriptionBn = trim((string) Request::post('description_bn', ''));
        $coverImage = trim((string) Request::post('cover_image', '/assets/img/project-1.jpg'));
        $proposalPdf = trim((string) Request::post('proposal_pdf', ''));
        $showOnHome = (int) (Request::post('show_on_home') ? 1 : 0);
        $isFeatured = (int) (Request::post('is_featured') ? 1 : 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);
        $sortOrder = (int) Request::post('sort_order', 0);

        // Process Gallery JSON
        $rawGallery = Request::post('gallery_json', '[]');
        $galleryList = [];
        if (is_string($rawGallery)) {
            $decoded = json_decode($rawGallery, true);
            if (is_array($decoded)) {
                $galleryList = array_values(array_filter($decoded, fn($u) => is_string($u) && trim($u) !== ''));
            }
        }
        if (empty($galleryList) && !empty($coverImage)) {
            $galleryList = [$coverImage];
        }
        $galleryJson = json_encode($galleryList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Process Features (Key Features Checklist)
        $featuresTextEn = (string) Request::post('features_en', '');
        $featuresList = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $featuresTextEn))), 'strlen'));
        $featuresJson = !empty($featuresList) ? json_encode($featuresList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

        // Process Materials Specifications
        $materialsTextEn = (string) Request::post('materials_en', '');
        $materialsList = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $materialsTextEn))), 'strlen'));
        $materialsJson = !empty($materialsList) ? json_encode($materialsList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

        // Process Highlights
        $highlightsTextEn = (string) Request::post('highlights_en', '');
        $highlightsLines = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $highlightsTextEn))), 'strlen'));
        $highlightsList = [];
        $icons = ['sparkle', 'gem', 'layout', 'award'];
        foreach ($highlightsLines as $hIdx => $hLine) {
            $highlightsList[] = [
                'title' => $hLine,
                'icon' => $icons[$hIdx % count($icons)],
            ];
        }
        $highlightsJson = !empty($highlightsList) ? json_encode($highlightsList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

        if ($titleEn === '' || $summaryEn === '') {
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
                        `features_json` = :features,
                        `highlights_json` = :highlights,
                        `materials_json` = :materials,
                        `proposal_pdf` = :pdf,
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
                    ':features' => $featuresJson,
                    ':highlights' => $highlightsJson,
                    ':materials' => $materialsJson,
                    ':pdf' => $proposalPdf ?: null,
                    ':rtk' => $roomTypeKey,
                    ':ptk' => $propertyTypeKey,
                    ':soh' => $showOnHome,
                    ':feat' => $isFeatured,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'project', (string) $id, Request::ip());
                Session::flash('success', "Project '{$titleEn}' updated successfully with " . count($galleryList) . " gallery photo(s).");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_projects`
                    (`slug`, `title_en`, `title_bn`, `summary_en`, `summary_bn`, `description_en`, `description_bn`, `location_en`, `location_bn`, `client_name`, `completion_year`, `area_sqft`, `room_details_en`, `room_details_bn`, `design_style_en`, `design_style_bn`, `project_status`, `cover_image`, `gallery_json`, `features_json`, `highlights_json`, `materials_json`, `proposal_pdf`, `room_type_key`, `property_type_key`, `show_on_home`, `is_featured`, `sort_order`, `is_active`)
                    VALUES (:slug, :te, :tb, :se, :sb, :de, :db, :le, :lb, :cname, :year, :area, :rde, :rdb, :dse, :dsb, :pstatus, :cover, :gallery, :features, :highlights, :materials, :pdf, :rtk, :ptk, :soh, :feat, :sort, :active)
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
                    ':features' => $featuresJson,
                    ':highlights' => $highlightsJson,
                    ':materials' => $materialsJson,
                    ':pdf' => $proposalPdf ?: null,
                    ':rtk' => $roomTypeKey,
                    ':ptk' => $propertyTypeKey,
                    ':soh' => $showOnHome,
                    ':feat' => $isFeatured,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'project', (string) $newId, Request::ip());
                Session::flash('success', "New project '{$titleEn}' created successfully with " . count($galleryList) . " gallery photo(s).");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save project: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/projects/edit/{$id}" : '/admin/projects/create');
        }

        return Response::redirect('/admin/projects');
    }

    /**
     * 1-Click Duplicate a project with all photos, specs, and metadata.
     */
    public function duplicate(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) (Request::post('id') ?: ($params['id'] ?? 0));
        if ($id <= 0) {
            Session::flash('error', 'Invalid project ID for duplication.');
            return Response::redirect('/admin/projects');
        }

        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_projects` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $orig = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$orig) {
            Session::flash('error', 'Project not found.');
            return Response::redirect('/admin/projects');
        }

        try {
            $copyTitleEn = $orig['title_en'] . ' (Copy)';
            $copyTitleBn = !empty($orig['title_bn']) ? $orig['title_bn'] . ' (কপি)' : null;
            
            // Unique slug generation
            $baseSlug = preg_replace('/-copy(-\d+)?$/', '', $orig['slug']);
            $copySlug = $baseSlug . '-copy-' . time();

            $insStmt = $pdo->prepare("
                INSERT INTO `lilyweb_projects`
                (`slug`, `title_en`, `title_bn`, `summary_en`, `summary_bn`, `description_en`, `description_bn`, `location_en`, `location_bn`, `client_name`, `completion_year`, `area_sqft`, `room_details_en`, `room_details_bn`, `design_style_en`, `design_style_bn`, `project_status`, `cover_image`, `gallery_json`, `features_json`, `highlights_json`, `materials_json`, `proposal_pdf`, `room_type_key`, `property_type_key`, `show_on_home`, `is_featured`, `sort_order`, `is_active`)
                VALUES (:slug, :te, :tb, :se, :sb, :de, :db, :le, :lb, :cname, :year, :area, :rde, :rdb, :dse, :dsb, :pstatus, :cover, :gallery, :features, :highlights, :materials, :pdf, :rtk, :ptk, :soh, :feat, :sort, :active)
            ");
            $insStmt->execute([
                ':slug' => $copySlug,
                ':te' => $copyTitleEn,
                ':tb' => $copyTitleBn,
                ':se' => $orig['summary_en'],
                ':sb' => $orig['summary_bn'],
                ':de' => $orig['description_en'],
                ':db' => $orig['description_bn'],
                ':le' => $orig['location_en'],
                ':lb' => $orig['location_bn'],
                ':cname' => $orig['client_name'],
                ':year' => $orig['completion_year'],
                ':area' => $orig['area_sqft'],
                ':rde' => $orig['room_details_en'],
                ':rdb' => $orig['room_details_bn'],
                ':dse' => $orig['design_style_en'],
                ':dsb' => $orig['design_style_bn'],
                ':pstatus' => $orig['project_status'],
                ':cover' => $orig['cover_image'],
                ':gallery' => $orig['gallery_json'],
                ':features' => $orig['features_json'],
                ':highlights' => $orig['highlights_json'],
                ':materials' => $orig['materials_json'],
                ':pdf' => $orig['proposal_pdf'],
                ':rtk' => $orig['room_type_key'],
                ':ptk' => $orig['property_type_key'],
                ':soh' => $orig['show_on_home'],
                ':feat' => 0,
                ':sort' => (int)$orig['sort_order'] + 1,
                ':active' => 1,
            ]);

            $newId = (int) $pdo->lastInsertId();
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'duplicate', 'project', (string) $newId, Request::ip());
            Session::flash('success', "Project duplicated successfully! You can now edit its title, images, and details.");

            return Response::redirect("/admin/projects/edit/{$newId}");
        } catch (Exception $e) {
            Session::flash('error', 'Failed to duplicate project: ' . $e->getMessage());
            return Response::redirect('/admin/projects');
        }
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
