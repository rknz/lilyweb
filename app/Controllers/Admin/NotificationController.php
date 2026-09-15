<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Request;
use Lilyweb\Core\Response;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;
use PDO;
use Exception;
use DateTime;

final class NotificationController extends AdminController
{
    /**
     * Fetch real-time notifications (unread inquiries, leads, and recent audit activity).
     */
    public function api(array $params = []): Response
    {
        $this->requireAuth();

        try {
            $pdo = Database::connect();

            // 1. Unread Inquiries Count
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `lilyweb_contact_submissions` WHERE `status` = 'new'");
            $unreadCount = (int) $countStmt->fetchColumn();

            // 2. Recent Client Inquiries / Consultation Leads
            $inqStmt = $pdo->query("
                SELECT `id`, `full_name`, `phone_number`, `email_address`, `service_slug`, `project_location`, `message`, `status`, `created_at`
                FROM `lilyweb_contact_submissions`
                ORDER BY `id` DESC
                LIMIT 8
            ");
            $rawInquiries = $inqStmt->fetchAll(PDO::FETCH_ASSOC);

            $inquiries = [];
            foreach ($rawInquiries as $item) {
                $serviceName = !empty($item['service_slug']) ? ucwords(str_replace(['-', '_'], ' ', $item['service_slug'])) : 'General Consultation';
                $inquiries[] = [
                    'id' => (int) $item['id'],
                    'full_name' => $item['full_name'] ?: 'Prospective Client',
                    'phone' => $item['phone_number'] ?: '',
                    'email' => $item['email_address'] ?: '',
                    'service' => $serviceName,
                    'location' => $item['project_location'] ?: '',
                    'message_snippet' => mb_substr(strip_tags((string) $item['message']), 0, 75) . (mb_strlen((string) $item['message']) > 75 ? '...' : ''),
                    'status' => $item['status'],
                    'is_new' => $item['status'] === 'new',
                    'created_at' => $item['created_at'],
                    'time_ago' => self::formatTimeAgo($item['created_at']),
                    'url' => '/admin/contacts/' . $item['id'],
                ];
            }

            // 3. Recent System Audit Activities
            $auditStmt = $pdo->query("
                SELECT `id`, `username`, `action`, `entity_type`, `entity_id`, `details_json`, `created_at`
                FROM `lilyweb_audit_logs`
                ORDER BY `id` DESC
                LIMIT 6
            ");
            $rawLogs = $auditStmt->fetchAll(PDO::FETCH_ASSOC);

            $activities = [];
            foreach ($rawLogs as $log) {
                $activities[] = [
                    'id' => (int) $log['id'],
                    'username' => $log['username'] ?: 'Admin',
                    'action' => $log['action'],
                    'entity_type' => $log['entity_type'],
                    'entity_id' => $log['entity_id'],
                    'description' => self::formatAuditDescription($log),
                    'time_ago' => self::formatTimeAgo($log['created_at']),
                    'created_at' => $log['created_at'],
                ];
            }

            return Response::json([
                'success' => true,
                'unread_count' => $unreadCount,
                'inquiries' => $inquiries,
                'activities' => $activities,
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            return Response::json([
                'success' => false,
                'error' => $e->getMessage(),
                'unread_count' => 0,
                'inquiries' => [],
                'activities' => [],
            ], 500);
        }
    }

    /**
     * Mark all pending new inquiries as contacted/read.
     */
    public function markAllRead(array $params = []): Response
    {
        $this->requireAuth();

        try {
            $pdo = Database::connect();
            $stmt = $pdo->query("UPDATE `lilyweb_contact_submissions` SET `status` = 'contacted' WHERE `status` = 'new'");
            $affected = $stmt->rowCount();

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'mark_all_read', 'contact_submissions', (string) $affected, Request::ip());

            return Response::json([
                'success' => true,
                'affected' => $affected,
                'message' => "Marked {$affected} new inquiries as reviewed.",
            ]);
        } catch (Exception $e) {
            return Response::json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark a single inquiry as contacted/read.
     */
    public function markRead(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) Request::post('id', 0);
        if ($id <= 0) {
            return Response::json(['success' => false, 'error' => 'Invalid lead ID'], 400);
        }

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("UPDATE `lilyweb_contact_submissions` SET `status` = 'contacted' WHERE `id` = :id");
            $stmt->execute([':id' => $id]);

            return Response::json(['success' => true, 'id' => $id]);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper to format relative human-readable time (e.g., '2m ago', '3 hours ago', 'Yesterday').
     */
    private static function formatTimeAgo(string $datetimeStr): string
    {
        try {
            $time = new DateTime($datetimeStr);
            $now = new DateTime();
            $diff = $now->getTimestamp() - $time->getTimestamp();

            if ($diff < 45) {
                return 'Just now';
            }
            if ($diff < 3600) {
                $mins = max(1, (int) round($diff / 60));
                return "{$mins}m ago";
            }
            if ($diff < 86400) {
                $hours = (int) round($diff / 3600);
                return "{$hours}h ago";
            }
            if ($diff < 172800) {
                return 'Yesterday';
            }
            if ($diff < 604800) {
                $days = (int) round($diff / 86400);
                return "{$days}d ago";
            }
            return $time->format('M j, Y');
        } catch (Exception $e) {
            return $datetimeStr;
        }
    }

    /**
     * Helper to format readable audit log action descriptions.
     */
    private static function formatAuditDescription(array $log): string
    {
        $user = htmlspecialchars($log['username'] ?: 'Admin');
        $action = str_replace('_', ' ', $log['action'] ?: 'action');
        $entity = str_replace('_', ' ', $log['entity_type'] ?: 'item');
        $id = $log['entity_id'] ? " #{$log['entity_id']}" : '';

        return ucfirst("{$action} on {$entity}{$id} by {$user}");
    }
}
