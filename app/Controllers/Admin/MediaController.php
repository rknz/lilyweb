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

final class MediaController extends AdminController
{
    /**
     * List all media assets (auto-indexes default assets if database table is empty).
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_media_assets` ORDER BY `id` DESC");
        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Auto-seed existing assets if empty
        if (empty($media)) {
            $imgDir = dirname(__DIR__, 3) . '/public/assets/img';
            if (is_dir($imgDir)) {
                $files = glob($imgDir . '/*.{jpg,jpeg,png,webp,svg}', GLOB_BRACE) ?: [];
                $insStmt = $pdo->prepare("
                    INSERT IGNORE INTO `lilyweb_media_assets`
                    (`original_name`, `filename`, `storage_path`, `mime_type`, `size_bytes`, `width`, `height`, `alt_en`, `alt_bn`)
                    VALUES (:orig, :fn, :path, :mime, :size, :w, :h, :alte, :altb)
                ");
                foreach ($files as $f) {
                    $fn = basename($f);
                    $mime = mime_content_type($f) ?: 'image/jpeg';
                    $size = filesize($f) ?: 0;
                    $dims = @getimagesize($f);
                    $w = $dims ? $dims[0] : null;
                    $h = $dims ? $dims[1] : null;
                    $altEn = ucwords(str_replace(['-', '_', '.'], ' ', pathinfo($fn, PATHINFO_FILENAME)));

                    $insStmt->execute([
                        ':orig' => $fn,
                        ':fn' => $fn,
                        ':path' => '/assets/img/' . $fn,
                        ':mime' => $mime,
                        ':size' => $size,
                        ':w' => $w,
                        ':h' => $h,
                        ':alte' => $altEn . ' by Lily Interiors',
                        ':altb' => null,
                    ]);
                }
                $stmt = $pdo->query("SELECT * FROM `lilyweb_media_assets` ORDER BY `id` DESC");
                $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        $content = View::make('admin.media.index', [
            'media' => $media,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Media & Image Asset Library',
            'pageHeading' => 'Media Asset Library & Image SEO Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Secure file upload handler.
     */
    public function upload(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        if (empty($_FILES['media_file']) || $_FILES['media_file']['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'No file was uploaded or an upload error occurred.');
            return Response::redirect('/admin/media');
        }

        $file = $_FILES['media_file'];
        $maxBytes = 10 * 1024 * 1024; // 10 MB
        if ($file['size'] > $maxBytes) {
            Session::flash('error', 'File size exceeds maximum allowed limit of 10MB.');
            return Response::redirect('/admin/media');
        }

        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf'
        ];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes, true)) {
            Session::flash('error', 'Invalid file format. Allowed: JPG, PNG, WEBP, GIF, PDF.');
            return Response::redirect('/admin/media');
        }

        $origName = $file['name'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $cleanBase = preg_replace('/[^a-zA-Z0-9_\-]+/', '-', pathinfo($origName, PATHINFO_FILENAME));
        $uniqueFilename = $cleanBase . '_' . time() . '.' . $ext;

        $targetDir = dirname(__DIR__, 3) . '/public/uploads';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $uniqueFilename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            Session::flash('error', 'Failed to store uploaded file on server.');
            return Response::redirect('/admin/media');
        }

        $storagePath = '/uploads/' . $uniqueFilename;
        $sizeBytes = filesize($targetPath) ?: $file['size'];
        $dims = @getimagesize($targetPath);
        $w = $dims ? $dims[0] : null;
        $h = $dims ? $dims[1] : null;
        $altEn = (string) Request::post('alt_en', '');
        $altBn = (string) Request::post('alt_bn', '');
        if (trim($altEn) === '') {
            $altEn = ucwords(str_replace(['-', '_'], ' ', $cleanBase)) . ' — Lily Interiors';
        }

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO `lilyweb_media_assets`
                (`original_name`, `filename`, `storage_path`, `mime_type`, `size_bytes`, `width`, `height`, `alt_en`, `alt_bn`, `hash_sha256`)
                VALUES (:orig, :fn, :path, :mime, :size, :w, :h, :alte, :altb, :hash)
            ");
            $stmt->execute([
                ':orig' => $origName,
                ':fn' => $uniqueFilename,
                ':path' => $storagePath,
                ':mime' => $mime,
                ':size' => $sizeBytes,
                ':w' => $w,
                ':h' => $h,
                ':alte' => $altEn,
                ':altb' => $altBn ?: null,
                ':hash' => hash_file('sha256', $targetPath),
            ]);

            $newId = (int) $pdo->lastInsertId();
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'upload', 'media_asset', (string) $newId, Request::ip());
            Session::flash('success', "Media file '{$origName}' uploaded successfully. Path: {$storagePath}");

        } catch (Exception $e) {
            Session::flash('error', 'Failed to save media metadata: ' . $e->getMessage());
        }

        return Response::redirect('/admin/media');
    }

    /**
     * Update Alt tags for SEO.
     */
    public function update(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $altEn = (string) Request::post('alt_en', '');
        $altBn = (string) Request::post('alt_bn', '');

        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("UPDATE `lilyweb_media_assets` SET `alt_en` = :ae, `alt_bn` = :ab WHERE `id` = :id");
            $stmt->execute([
                ':ae' => $altEn,
                ':ab' => $altBn ?: null,
                ':id' => $id,
            ]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'media_asset', (string) $id, Request::ip());
            Session::flash('success', 'Media SEO Alt tags updated successfully.');
        }

        return Response::redirect('/admin/media');
    }

    /**
     * Delete media asset.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT `storage_path` FROM `lilyweb_media_assets` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $path = $stmt->fetchColumn();

            if ($path && str_starts_with($path, '/uploads/')) {
                $fullPath = dirname(__DIR__, 3) . '/public' . $path;
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            $delStmt = $pdo->prepare("DELETE FROM `lilyweb_media_assets` WHERE `id` = :id");
            $delStmt->execute([':id' => $id]);

            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'media_asset', (string) $id, Request::ip());
            Session::flash('success', 'Media asset removed.');
        }

        return Response::redirect('/admin/media');
    }

    /**
     * Instant AJAX File Upload for in-form image & document uploaders (single & multi-file batch).
     */
    public function quickUpload(array $params = []): Response
    {
        if (!Auth::check()) {
            return Response::json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'application/pdf'
        ];
        $targetDir = dirname(__DIR__, 3) . '/public/uploads';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $pdo = Database::connect();

        // Check for multiple files in 'files' or 'file'
        $filesToProcess = [];
        if (!empty($_FILES['files']) && is_array($_FILES['files']['name'])) {
            foreach ($_FILES['files']['name'] as $idx => $name) {
                if ($_FILES['files']['error'][$idx] === UPLOAD_ERR_OK) {
                    $filesToProcess[] = [
                        'name' => $_FILES['files']['name'][$idx],
                        'type' => $_FILES['files']['type'][$idx],
                        'tmp_name' => $_FILES['files']['tmp_name'][$idx],
                        'size' => $_FILES['files']['size'][$idx],
                    ];
                }
            }
        } elseif (!empty($_FILES['file']) && is_array($_FILES['file']['name'])) {
            foreach ($_FILES['file']['name'] as $idx => $name) {
                if ($_FILES['file']['error'][$idx] === UPLOAD_ERR_OK) {
                    $filesToProcess[] = [
                        'name' => $_FILES['file']['name'][$idx],
                        'type' => $_FILES['file']['type'][$idx],
                        'tmp_name' => $_FILES['file']['tmp_name'][$idx],
                        'size' => $_FILES['file']['size'][$idx],
                    ];
                }
            }
        } elseif (!empty($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $filesToProcess[] = $_FILES['file'];
        } elseif (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $filesToProcess[] = $_FILES['image_file'];
        }

        if (empty($filesToProcess)) {
            return Response::json(['success' => false, 'message' => 'No files were uploaded or an upload error occurred'], 400);
        }

        $uploadedUrls = [];
        $lastUrl = '';
        $lastFilename = '';

        foreach ($filesToProcess as $file) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowedMimes, true)) {
                continue;
            }

            $origName = $file['name'];
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $cleanBase = preg_replace('/[^a-zA-Z0-9_\-]+/', '-', pathinfo($origName, PATHINFO_FILENAME));
            $uniqueFilename = $cleanBase . '_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;

            $targetPath = $targetDir . '/' . $uniqueFilename;
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                continue;
            }

            $storagePath = '/uploads/' . $uniqueFilename;
            $sizeBytes = filesize($targetPath) ?: $file['size'];
            $dims = ($mime !== 'application/pdf') ? @getimagesize($targetPath) : null;
            $w = $dims ? $dims[0] : null;
            $h = $dims ? $dims[1] : null;
            $altEn = ucwords(str_replace(['-', '_'], ' ', $cleanBase)) . ' — Lily Interiors';

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_media_assets`
                    (`original_name`, `filename`, `storage_path`, `mime_type`, `size_bytes`, `width`, `height`, `alt_en`, `hash_sha256`)
                    VALUES (:orig, :fn, :path, :mime, :size, :w, :h, :alte, :hash)
                ");
                $stmt->execute([
                    ':orig' => $origName,
                    ':fn' => $uniqueFilename,
                    ':path' => $storagePath,
                    ':mime' => $mime,
                    ':size' => $sizeBytes,
                    ':w' => $w,
                    ':h' => $h,
                    ':alte' => $altEn,
                    ':hash' => hash_file('sha256', $targetPath),
                ]);
            } catch (Exception $e) {
                // fail-safe
            }

            $uploadedUrls[] = $storagePath;
            $lastUrl = $storagePath;
            $lastFilename = $uniqueFilename;
        }

        if (empty($uploadedUrls)) {
            return Response::json(['success' => false, 'message' => 'No valid images could be saved.'], 400);
        }

        return Response::json([
            'success' => true,
            'url' => $lastUrl,
            'urls' => $uploadedUrls,
            'count' => count($uploadedUrls),
            'filename' => $lastFilename,
            'message' => count($uploadedUrls) . ' file(s) uploaded successfully'
        ]);
    }

    /**
     * Quick list of media items for interactive modal picker.
     */
    public function pickerList(array $params = []): Response
    {
        if (!Auth::check()) {
            return Response::json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT `id`, `filename`, `storage_path`, `alt_en` FROM `lilyweb_media_assets` ORDER BY `id` DESC LIMIT 60");
        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Response::json([
            'success' => true,
            'data' => $media,
        ]);
    }
}
