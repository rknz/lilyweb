<?php
use Lilyweb\Core\Security;
?>

<div class="form-grid">
    <!-- 1-Click Backup Card -->
    <div class="admin-card card-master-en">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <span>Create 1-Click Database Backup</span>
        </h3>
        <p style="font-size: 0.88rem; color: var(--text-main); font-weight: 500; line-height: 1.6; margin-bottom: 1.4rem;">
            Dumps all 16 CMS tables, site settings, hero slides, portfolio projects, client consultation inquiries, and SEO metadata into a standalone, compressed <code>.sql</code> archive.
        </p>

        <form action="/admin/backup/create" method="POST">
            <?= Security::csrfField() ?>
            <button type="submit" class="btn-primary" style="background: #16A34A; border-color: #16A34A; box-shadow: 0 4px 14px rgba(22, 163, 74, 0.35);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Generate New Database Backup Now</span>
            </button>
        </form>
    </div>

    <!-- Restore Database Card -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            <span>Restore Database from SQL File</span>
        </h3>
        <p style="font-size: 0.88rem; color: var(--text-main); font-weight: 500; line-height: 1.6; margin-bottom: 1.25rem;">
            Upload an existing <code>.sql</code> backup archive to overwrite and restore database tables.
        </p>

        <form action="/admin/backup/restore" method="POST" enctype="multipart/form-data" onsubmit="return confirm('WARNING: Restoring will overwrite existing database records with the uploaded file. Proceed?');">
            <?= Security::csrfField() ?>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <input type="file" name="sql_file" required accept=".sql" class="form-input" style="padding: 0.55rem;">
                <button type="submit" class="btn-danger" style="white-space: nowrap; padding: 0.65rem 1.35rem;">Restore DB</button>
            </div>
        </form>
    </div>
</div>

<!-- Backups Archive Table -->
<div class="admin-card" style="margin-top: 1.85rem;">
    <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Saved Backup Archives</h3>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>File Size</th>
                    <th>Created By</th>
                    <th>Timestamp</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($backups)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); font-size: 0.95rem; font-weight: 600; padding: 2.5rem;">No backup archives generated yet. Click 'Generate New Database Backup Now' above.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($backups as $b): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-heading); font-family: monospace; font-size: 0.9rem;"><?= Security::e($b['filename']) ?></strong>
                                <?php if (!empty($b['checksum_sha256'])): ?>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-top: 0.2rem;">SHA256: <code><?= substr($b['checksum_sha256'], 0, 16) ?>...</code></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-pill-blue"><?= round($b['size_bytes'] / 1024, 1) ?> KB</span>
                            </td>
                            <td><strong style="color: var(--text-heading);"><?= Security::e($b['created_by_user']) ?></strong></td>
                            <td style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted);"><?= date('M d, Y h:i:s A', strtotime($b['created_at'])) ?></td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.45rem;">
                                    <a href="/admin/backup/download/<?= (int) $b['id'] ?>" class="btn-primary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        <span>Download</span>
                                    </a>
                                    
                                    <form action="/admin/backup/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this backup file?');" style="display: inline;">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                                        <button type="submit" class="btn-danger" style="padding: 0.42rem 0.85rem;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
