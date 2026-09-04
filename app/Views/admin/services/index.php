<?php
use Lilyweb\Core\Security;
?>
<div class="card-header-flex">
    <div>
        <h2 class="card-title">Core Services (<?= count($services) ?> Services)</h2>
        <p style="color: var(--text-muted); font-size: 0.86rem; font-weight: 600; margin-top: 0.25rem;">
            Edits take effect immediately on the homepage 2x3 services grid and services directory.
        </p>
    </div>
    <a href="/admin/services/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New Service</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Icon</th>
                    <th>Service Name (EN & BN)</th>
                    <th>Badge Pill</th>
                    <th>Slug</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($services)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--text-muted); font-size: 0.95rem; font-weight: 600;">
                            No services found. Click "Add New Service" above.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($services as $srv): ?>
                        <tr>
                            <td>
                                <div style="width: 42px; height: 42px; border-radius: var(--radius-md); background: rgba(200, 16, 46, 0.12); display: grid; place-items: center; border: 1.5px solid rgba(200, 16, 46, 0.3);">
                                    <?= Security::sanitizeSvg($srv['icon_svg']) ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem;">
                                    <?= Security::e($srv['title_en']) ?>
                                </div>
                                <?php if (!empty($srv['title_bn'])): ?>
                                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem;">
                                        🇧🇩 <?= Security::e($srv['title_bn']) ?>
                                    </div>
                                <?php endif; ?>
                                <div style="font-size: 0.8rem; color: var(--text-main); margin-top: 0.25rem; max-width: 340px; line-height: 1.4;">
                                    <?= Security::e($srv['summary_en']) ?>
                                </div>
                            </td>
                            <td>
                                <span style="display: inline-block; font-size: 0.76rem; font-weight: 800; background: #FEE2E2; color: #991B1B; border: 1.5px solid #FCA5A5; padding: 0.22rem 0.6rem; border-radius: 9999px;">
                                    <?= Security::e($srv['tag_badge_en']) ?>
                                </span>
                                <?php if (!empty($srv['tag_badge_bn'])): ?>
                                    <br><span style="font-size: 0.74rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem; display: inline-block;">🇧🇩 <?= Security::e($srv['tag_badge_bn']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code style="font-size: 0.8rem; font-weight: 700; color: var(--crimson); background: rgba(200, 16, 46, 0.08); padding: 0.15rem 0.4rem; border-radius: 4px;">
                                    <?= Security::e($srv['slug']) ?>
                                </code>
                            </td>
                            <td>
                                <strong style="color: var(--text-heading); font-weight: 700;"><?= (int) $srv['sort_order'] ?></strong>
                            </td>
                            <td>
                                <span class="badge-status <?= $srv['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= $srv['is_active'] ? 'Active' : 'Draft' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.45rem;">
                                    <a href="/admin/services/edit/<?= (int) $srv['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                    
                                    <form action="/admin/services/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" style="display: inline;">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $srv['id'] ?>">
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
