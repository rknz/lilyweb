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

final class ContactController extends AdminController
{
    /**
     * List all contact and consultation submissions.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_contact_submissions` ORDER BY `id` DESC");
        $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.contacts.index', [
            'leads' => $leads,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Consultation Inquiries & Leads',
            'pageHeading' => 'Client Inquiries & Consultation Submissions (' . count($leads) . ' Leads)',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * View detailed lead information and add private notes.
     */
    public function show(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_contact_submissions` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $lead = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$lead) {
            Session::flash('error', 'Inquiry lead not found.');
            return Response::redirect('/admin/contacts');
        }

        // If new, mark as contacted automatically upon opening if requested
        $content = View::make('admin.contacts.show', [
            'lead' => $lead,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Lead #' . $lead['id'] . ' — ' . $lead['full_name'],
            'pageHeading' => 'Consultation Lead: ' . $lead['full_name'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Update status and notes of a lead.
     */
    public function updateStatus(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $status = (string) Request::post('status', 'contacted');
        $notes = (string) Request::post('admin_notes', '');

        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                UPDATE `lilyweb_contact_submissions` 
                SET `status` = :st, `admin_notes` = :notes
                WHERE `id` = :id
            ");
            $stmt->execute([
                ':st' => $status,
                ':notes' => $notes ?: null,
                ':id' => $id,
            ]);

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update_status', 'contact_lead', (string) $id, Request::ip());
            Session::flash('success', "Lead #{$id} status updated to '{$status}'.");
        }

        return Response::redirect('/admin/contacts');
    }

    /**
     * Export all inquiries to CSV.
     */
    public function export(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_contact_submissions` ORDER BY `id` DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="lily_interiors_leads_' . date('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        // BOM for Excel UTF-8
        fputs($out, "\xEF\xBB\xBF");
        fputcsv($out, ['ID', 'Full Name', 'Phone', 'Email', 'Service', 'Location', 'Message', 'Status', 'Notes', 'Date']);

        foreach ($rows as $r) {
            fputcsv($out, [
                $r['id'],
                $r['full_name'],
                $r['phone_number'],
                $r['email_address'],
                $r['service_slug'],
                $r['project_location'],
                $r['message'],
                $r['status'],
                $r['admin_notes'],
                $r['created_at'],
            ]);
        }
        fclose($out);
        exit;
    }

    /**
     * Delete an inquiry.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_contact_submissions` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'contact_lead', (string) $id, Request::ip());
            Session::flash('success', 'Lead record deleted.');
        }

        return Response::redirect('/admin/contacts');
    }
}
