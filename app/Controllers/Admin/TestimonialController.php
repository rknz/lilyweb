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

final class TestimonialController extends AdminController
{
    /**
     * List all client testimonials.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_testimonials` ORDER BY `sort_order` ASC, `id` ASC");
        $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.testimonials.index', [
            'testimonials' => $testimonials,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Testimonials Management',
            'pageHeading' => 'Client Testimonials Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for a testimonial.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_testimonials` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$testimonial) {
            Session::flash('error', 'Testimonial not found.');
            return Response::redirect('/admin/testimonials');
        }

        $content = View::make('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit Review — ' . $testimonial['author_name'],
            'pageHeading' => 'Edit Client Review: ' . $testimonial['author_name'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new testimonial.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $testimonial = [
            'id' => 0,
            'author_name' => '',
            'author_role_en' => 'Homeowner',
            'author_role_bn' => '',
            'author_location_en' => 'Gulshan 2, Dhaka',
            'author_location_bn' => '',
            'project_tag_en' => 'Gulshan 2 Duplex • 3,200 sqft',
            'project_tag_bn' => '',
            'author_initials' => 'LI',
            'content_en' => '',
            'content_bn' => '',
            'rating_score' => 5.0,
            'is_verified' => 1,
            'sort_order' => 7,
            'is_active' => 1,
        ];

        $content = View::make('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New Client Review',
            'pageHeading' => 'Add New Client Review',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update testimonial.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $authorName = (string) Request::post('author_name', '');
        $authorRoleEn = (string) Request::post('author_role_en', '');
        $authorRoleBn = (string) Request::post('author_role_bn', '');
        $authorLocationEn = (string) Request::post('author_location_en', '');
        $authorLocationBn = (string) Request::post('author_location_bn', '');
        $projectTagEn = (string) Request::post('project_tag_en', '');
        $projectTagBn = (string) Request::post('project_tag_bn', '');
        $authorInitials = (string) Request::post('author_initials', '');
        if (trim($authorInitials) === '' && trim($authorName) !== '') {
            $words = explode(' ', trim($authorName));
            $authorInitials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
        }

        $contentEn = (string) Request::post('content_en', '');
        $contentBn = (string) Request::post('content_bn', '');
        $ratingScore = (float) Request::post('rating_score', 5.0);
        $isVerified = (int) (Request::post('is_verified') ? 1 : 0);
        $sortOrder = (int) Request::post('sort_order', 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);

        if (trim($authorName) === '' || trim($contentEn) === '') {
            Session::flash('error', 'Author name and English review content are required.');
            return Response::redirect($id > 0 ? "/admin/testimonials/edit/{$id}" : '/admin/testimonials/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_testimonials` SET
                        `author_name` = :name,
                        `author_role_en` = :re,
                        `author_role_bn` = :rb,
                        `author_location_en` = :le,
                        `author_location_bn` = :lb,
                        `project_tag_en` = :pte,
                        `project_tag_bn` = :ptb,
                        `author_initials` = :init,
                        `content_en` = :ce,
                        `content_bn` = :cb,
                        `rating_score` = :score,
                        `is_verified` = :ver,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':name' => $authorName,
                    ':re' => $authorRoleEn,
                    ':rb' => $authorRoleBn ?: null,
                    ':le' => $authorLocationEn,
                    ':lb' => $authorLocationBn ?: null,
                    ':pte' => $projectTagEn,
                    ':ptb' => $projectTagBn ?: null,
                    ':init' => $authorInitials,
                    ':ce' => $contentEn,
                    ':cb' => $contentBn ?: null,
                    ':score' => $ratingScore,
                    ':ver' => $isVerified,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'testimonial', (string) $id, Request::ip());
                Session::flash('success', "Review by '{$authorName}' updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_testimonials`
                    (`author_name`, `author_role_en`, `author_role_bn`, `author_location_en`, `author_location_bn`, `project_tag_en`, `project_tag_bn`, `author_initials`, `content_en`, `content_bn`, `rating_score`, `is_verified`, `sort_order`, `is_active`)
                    VALUES (:name, :re, :rb, :le, :lb, :pte, :ptb, :init, :ce, :cb, :score, :ver, :sort, :active)
                ");
                $stmt->execute([
                    ':name' => $authorName,
                    ':re' => $authorRoleEn,
                    ':rb' => $authorRoleBn ?: null,
                    ':le' => $authorLocationEn,
                    ':lb' => $authorLocationBn ?: null,
                    ':pte' => $projectTagEn,
                    ':ptb' => $projectTagBn ?: null,
                    ':init' => $authorInitials,
                    ':ce' => $contentEn,
                    ':cb' => $contentBn ?: null,
                    ':score' => $ratingScore,
                    ':ver' => $isVerified,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'testimonial', (string) $newId, Request::ip());
                Session::flash('success', "New review by '{$authorName}' created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save review: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/testimonials/edit/{$id}" : '/admin/testimonials/create');
        }

        return Response::redirect('/admin/testimonials');
    }

    /**
     * Delete a testimonial.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_testimonials` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'testimonial', (string) $id, Request::ip());
            Session::flash('success', 'Testimonial deleted successfully.');
        }

        return Response::redirect('/admin/testimonials');
    }
}
