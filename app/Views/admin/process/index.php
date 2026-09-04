<?php
use Lilyweb\Core\Security;
?>
<div class="card-header-flex">
    <div>
        <h2 class="card-title">Execution Process Steps (<?= count($steps) ?> Steps)</h2>
        <p class="card-subtitle">Edits take effect immediately on the homepage animated process timeline.</p>
    </div>
    <a href="/admin/process/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New Step</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Step</th>
                    <th style="width: 60px;">Icon</th>
                    <th>Step Title (EN & BN)</th>
                    <th>Description</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($steps as $st): ?>
                    <tr>
                        <td>
                            <strong style="color: var(--crimson); font-size: 1.15rem; font-weight: 800;"><?= Security::e($st['step_number']) ?></strong>
                        </td>
                        <td>
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(200, 16, 46, 0.12); display: grid; place-items: center; border: 1.5px solid rgba(200, 16, 46, 0.3);">
                                <?= Security::sanitizeSvg($st['icon_svg']) ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem;">
                                <?= Security::e($st['title_en']) ?>
                            </div>
                            <?php if (!empty($st['title_bn'])): ?>
                                <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem;">
                                    🇧🇩 <?= Security::e($st['title_bn']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; color: var(--text-main); max-width: 380px; line-height: 1.45; font-weight: 500;">
                                <?= Security::e($st['description_en']) ?>
                            </div>
                            <?php if (!empty($st['description_bn'])): ?>
                                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; max-width: 380px; font-weight: 500;">
                                    🇧🇩 <?= Security::e($st['description_bn']) ?>
                                </div>
                            <?php endif; ?>
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
                                <a href="/admin/process/edit/<?= (int) $st['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                
                                <form action="/admin/process/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this process step?');" style="display: inline;">
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
