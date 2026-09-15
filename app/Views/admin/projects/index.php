<?php
use Lilyweb\Core\Security;
?>
<div class="page-actions">
    <a href="/admin/projects/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New Project</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 85px;">Cover</th>
                    <th>Project Title (EN & BN)</th>
                    <th>Category</th>
                    <th>Location & Area</th>
                    <th>Year</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projects)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2.5rem; color: var(--text-muted); font-size: 0.95rem; font-weight: 600;">
                            No projects found. Click "Add New Project" above to create one.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projects as $p): ?>
                        <tr>
                            <td>
                                <img src="<?= Security::e($p['cover_image']) ?>" alt="Project cover" style="width: 72px; height: 50px; object-fit: cover; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                            </td>
                            <td>
                                <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem; line-height: 1.25;">
                                    <?= Security::e($p['title_en']) ?>
                                </div>
                                <?php if (!empty($p['title_bn'])): ?>
                                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem;">
                                        🇧🇩 <?= Security::e($p['title_bn']) ?>
                                    </div>
                                <?php endif; ?>
                                <div style="font-size: 0.76rem; color: var(--text-dim); margin-top: 0.25rem; font-weight: 600;">
                                    Slug: <code style="color: var(--crimson); font-weight: 700; background: rgba(200, 16, 46, 0.08); padding: 0.15rem 0.4rem; border-radius: 4px;"><?= Security::e($p['slug']) ?></code>
                                </div>
                            </td>
                            <td>
                                <span style="display: inline-block; font-size: 0.78rem; font-weight: 800; background: #FEE2E2; color: #991B1B; border: 1.5px solid #FCA5A5; padding: 0.25rem 0.65rem; border-radius: 9999px;">
                                    <?= Security::e($p['category_name'] ?? $p['room_type_key']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="font-size: 0.86rem; color: var(--text-heading); font-weight: 700;"><?= Security::e($p['location_en']) ?></strong><br>
                                <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;"><?= Security::e($p['area_sqft']) ?></span>
                            </td>
                            <td>
                                <strong style="font-size: 0.88rem; color: var(--text-heading); font-weight: 700;"><?= Security::e($p['completion_year']) ?></strong>
                            </td>
                            <td>
                                <?php if ($p['is_featured']): ?>
                                    <span style="color: #D97706; font-size: 0.86rem; font-weight: 800;">★ Featured</span>
                                <?php else: ?>
                                    <span style="color: var(--text-dim); font-size: 0.82rem; font-weight: 600;">Standard</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-status <?= $p['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= $p['is_active'] ? 'Published' : 'Draft' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.45rem; align-items: center;">
                                    <a href="/projects/<?= Security::e($p['slug']) ?>" target="_blank" class="btn-secondary" style="padding: 0.42rem 0.7rem; font-size: 0.82rem;" title="View public project page">↗</a>
                                    <a href="/admin/projects/edit/<?= (int) $p['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                    
                                    <form action="/admin/projects/duplicate" method="POST" style="display: inline;">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                        <button type="submit" class="btn-secondary" style="padding: 0.42rem 0.75rem; font-size: 0.82rem;" title="Duplicate this project with all photos and specs">📋 Duplicate</button>
                                    </form>

                                    <form action="/admin/projects/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');" style="display: inline;">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
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
