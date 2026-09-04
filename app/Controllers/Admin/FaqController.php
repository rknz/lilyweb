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

final class FaqController extends AdminController
{
    /**
     * List all FAQs.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_faqs` ORDER BY `sort_order` ASC, `id` ASC");
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.faq.index', [
            'faqs' => $faqs,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'FAQ Management & Schema',
            'pageHeading' => 'FAQ & Search Rich Snippets Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for an FAQ.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_faqs` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $faq = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$faq) {
            Session::flash('error', 'FAQ item not found.');
            return Response::redirect('/admin/faq');
        }

        $content = View::make('admin.faq.form', [
            'faq' => $faq,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit FAQ',
            'pageHeading' => 'Edit FAQ: ' . substr($faq['question_en'], 0, 45) . '...',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new FAQ.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $faq = [
            'id' => 0,
            'category' => 'general',
            'question_en' => '',
            'question_bn' => '',
            'answer_en' => '',
            'answer_bn' => '',
            'sort_order' => 6,
            'is_active' => 1,
        ];

        $content = View::make('admin.faq.form', [
            'faq' => $faq,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New FAQ',
            'pageHeading' => 'Add New FAQ Item',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update FAQ item.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $category = (string) Request::post('category', 'general');
        $questionEn = (string) Request::post('question_en', '');
        $questionBn = (string) Request::post('question_bn', '');
        $answerEn = (string) Request::post('answer_en', '');
        $answerBn = (string) Request::post('answer_bn', '');
        $sortOrder = (int) Request::post('sort_order', 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);

        if (trim($questionEn) === '' || trim($answerEn) === '') {
            Session::flash('error', 'English question and answer are required.');
            return Response::redirect($id > 0 ? "/admin/faq/edit/{$id}" : '/admin/faq/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_faqs` SET
                        `category` = :cat,
                        `question_en` = :qe,
                        `question_bn` = :qb,
                        `answer_en` = :ae,
                        `answer_bn` = :ab,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':cat' => $category,
                    ':qe' => $questionEn,
                    ':qb' => $questionBn ?: null,
                    ':ae' => $answerEn,
                    ':ab' => $answerBn ?: null,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'faq', (string) $id, Request::ip());
                Session::flash('success', "FAQ updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_faqs`
                    (`category`, `question_en`, `question_bn`, `answer_en`, `answer_bn`, `sort_order`, `is_active`)
                    VALUES (:cat, :qe, :qb, :ae, :ab, :sort, :active)
                ");
                $stmt->execute([
                    ':cat' => $category,
                    ':qe' => $questionEn,
                    ':qb' => $questionBn ?: null,
                    ':ae' => $answerEn,
                    ':ab' => $answerBn ?: null,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'faq', (string) $newId, Request::ip());
                Session::flash('success', "New FAQ created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save FAQ: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/faq/edit/{$id}" : '/admin/faq/create');
        }

        return Response::redirect('/admin/faq');
    }

    /**
     * Delete an FAQ.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_faqs` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'faq', (string) $id, Request::ip());
            Session::flash('success', 'FAQ deleted successfully.');
        }

        return Response::redirect('/admin/faq');
    }
}
