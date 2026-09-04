<?php
use Lilyweb\Core\Security;
?>
<div class="page-actions">
    <a href="/admin/categories/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New Category</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Category Name (EN & BN)</th>
                    <th>Slug</th>
                    <th>Projects Assigned</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--text-muted); font-size: 0.95rem; font-weight: 600;">
                            No categories found. Click "Add New Category" above.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem;">
                                    <?= Security::e($c['name_en']) ?>
                                </div>
                                <?php if (!empty($c['name_bn'])): ?>
                                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem;">
                                        🇧🇩 <?= Security::e($c['name_bn']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code style="font-size: 0.8rem; font-weight: 700; color: var(--crimson); background: rgba(200, 16, 46, 0.08); padding: 0.15rem 0.4rem; border-radius: 4px;">
                                    <?= Security::e($c['slug']) ?>
                                </code>
                            </td>
                            <td>
                                <span style="display: inline-block; font-size: 0.78rem; font-weight: 800; background: #DBEAFE; color: #1D4ED8; border: 1.5px solid #93C5FD; padding: 0.22rem 0.65rem; border-radius: 9999px;">
                                    <?= (int) $c['project_count'] ?> projects
                                </span>
                            </td>
                            <td>
                                <strong style="color: var(--text-heading); font-weight: 700;"><?= (int) $c['sort_order'] ?></strong>
                            </td>
                            <td>
                                <span class="badge-status <?= $c['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= $c['is_active'] ? 'Active' : 'Draft' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.45rem;">
                                    <a href="/admin/categories/edit/<?= (int) $c['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                    
                                    <form action="/admin/categories/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" style="display: inline;">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
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
