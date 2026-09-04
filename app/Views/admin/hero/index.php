<?php
use Lilyweb\Core\Security;
?>
<div class="card-header-flex">
    <div>
        <h2 class="card-title">Homepage Hero Slides (<?= count($slides) ?> Active Slides)</h2>
        <p style="color: var(--text-muted); font-size: 0.86rem; font-weight: 600; margin-top: 0.25rem;">
            Edits take effect immediately on the homepage without a separate publish step.
        </p>
    </div>
    <a href="/admin/hero/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New Slide</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Step</th>
                    <th style="width: 100px;">Image</th>
                    <th>Heading (EN & BN)</th>
                    <th>Location / Room Tag</th>
                    <th>CTA Button</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($slides)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2.5rem; color: var(--text-muted); font-size: 0.95rem; font-weight: 600;">
                            No hero slides found. Click "Add New Slide" above.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($slides as $s): ?>
                        <tr>
                            <td><strong style="color: var(--crimson); font-size: 1.05rem; font-weight: 800;"><?= Security::e($s['step_number']) ?></strong></td>
                            <td>
                                <img src="<?= Security::e($s['image_url']) ?>" alt="Slide preview" style="width: 84px; height: 54px; object-fit: cover; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                            </td>
                            <td>
                                <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem;">
                                    <?= Security::e($s['title_prefix_en'] . ' ' . $s['title_highlight_en'] . ' ' . $s['title_suffix_en']) ?>
                                </div>
                                <div style="font-size: 0.8rem; font-weight: 600; color: var(--crimson); margin-top: 0.15rem;"><?= Security::e($s['kicker_en']) ?></div>
                                <?php if (!empty($s['title_highlight_bn'])): ?>
                                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem;">
                                        🇧🇩 <?= Security::e($s['title_prefix_bn'] . ' ' . $s['title_highlight_bn'] . ' ' . $s['title_suffix_bn']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="font-size: 0.86rem; color: var(--text-heading); font-weight: 700;"><?= Security::e($s['badge_room_en']) ?></strong><br>
                                <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;"><?= Security::e($s['badge_location_en']) ?></span>
                            </td>
                            <td>
                                <span style="font-size: 0.84rem; color: var(--crimson); font-weight: 800;"><?= Security::e($s['cta_text_en']) ?></span><br>
                                <span style="font-size: 0.76rem; color: var(--text-muted); font-weight: 600;"><?= Security::e($s['cta_url']) ?></span>
                            </td>
                            <td>
                                <strong style="color: var(--text-heading); font-weight: 700;"><?= (int) $s['sort_order'] ?></strong>
                            </td>
                            <td>
                                <span class="badge-status <?= $s['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= $s['is_active'] ? 'Active' : 'Draft' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.45rem;">
                                    <a href="/admin/hero/edit/<?= (int) $s['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                    
                                    <form action="/admin/hero/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this hero slide?');" style="display: inline;">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
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
