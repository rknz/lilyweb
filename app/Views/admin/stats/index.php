<?php
use Lilyweb\Core\Security;
?>
<div class="card-header-flex">
    <div>
        <h2 class="card-title">Achievement Stats Strip (<?= count($stats) ?> Stats Cards)</h2>
        <p class="card-subtitle">Edits take effect immediately across homepage and content page stats strips with counting animations.</p>
    </div>
    <a href="/admin/stats/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New Stat</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Icon</th>
                    <th>Numeric KPI Value</th>
                    <th>Label (EN & BN)</th>
                    <th>Database Key</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $st): ?>
                    <tr>
                        <td>
                            <div style="width: 40px; height: 40px; border-radius: var(--radius-md); background: rgba(200, 16, 46, 0.12); display: grid; place-items: center; border: 1.5px solid rgba(200, 16, 46, 0.3);">
                                <?= Security::sanitizeSvg($st['icon_svg']) ?>
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--crimson); font-size: 1.35rem; font-weight: 800; letter-spacing: -0.01em;">
                                <?= (int) $st['value_number'] ?><?= Security::e($st['suffix']) ?>
                            </strong>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem;">
                                <?= Security::e($st['label_en']) ?>
                            </div>
                            <?php if (!empty($st['label_bn'])): ?>
                                <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem;">
                                    🇧🇩 <?= Security::e($st['label_bn']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <code style="font-size: 0.8rem; font-weight: 700; color: var(--crimson); background: rgba(200, 16, 46, 0.08); padding: 0.15rem 0.4rem; border-radius: 4px;">
                                <?= Security::e($st['stat_key']) ?>
                            </code>
                        </td>
                        <td>
                            <strong style="color: var(--text-heading); font-weight: 700;"><?= (int) $st['sort_order'] ?></strong>
                        </td>
                        <td>
                            <span class="badge-status <?= $st['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                <?= $st['is_active'] ? 'Active' : 'Draft' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.45rem;">
                                <a href="/admin/stats/edit/<?= (int) $st['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                
                                <form action="/admin/stats/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this stat card?');" style="display: inline;">
                                    <?= Security::csrfField() ?>
                                    <input type="hidden" name="id" value="<?= (int) $st['id'] ?>">
                                    <button type="submit" class="btn-danger" style="padding: 0.42rem 0.85rem;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
