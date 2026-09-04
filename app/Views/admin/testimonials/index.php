<?php
use Lilyweb\Core\Security;
?>
<div class="card-header-flex">
    <div>
        <h2 class="card-title">Client Testimonials (<?= count($testimonials) ?> Reviews)</h2>
        <p class="card-subtitle">Edits take effect immediately on the homepage luxury architectural carousel.</p>
    </div>
    <a href="/admin/testimonials/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New Review</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Avatar</th>
                    <th style="min-width: 170px;">Client / Author</th>
                    <th style="min-width: 180px;">Project & Location</th>
                    <th style="width: 85px;">Rating</th>
                    <th style="min-width: 300px;">Review Narrative</th>
                    <th style="width: 70px; text-align: center;">Order</th>
                    <th style="width: 90px; text-align: center;">Status</th>
                    <th style="min-width: 130px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($testimonials as $tm): ?>
                    <tr>
                        <td>
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--crimson); color: #FFFFFF; display: grid; place-items: center; font-weight: 800; font-size: 0.85rem; box-shadow: 0 2px 8px rgba(200, 16, 46, 0.35);">
                                <?= Security::e($tm['author_initials']) ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem; line-height: 1.3;"><?= Security::e($tm['author_name']) ?></div>
                            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); margin-top: 0.2rem;"><?= Security::e($tm['author_role_en']) ?></div>
                        </td>
                        <td>
                            <span class="badge-pill-crimson"><?= Security::e($tm['project_tag_en']) ?></span>
                            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); margin-top: 0.3rem;">📍 <?= Security::e($tm['author_location_en']) ?></div>
                        </td>
                        <td>
                            <span style="color: #D97706; font-weight: 800; font-size: 0.88rem; background: #FEF3C7; padding: 0.25rem 0.6rem; border-radius: 9999px; border: 1px solid #FDE68A; white-space: nowrap;">
                                ★ <?= number_format((float)$tm['rating_score'], 1) ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.88rem; color: var(--text-main); line-height: 1.55; font-weight: 500;">
                                “<?= Security::e($tm['content_en']) ?>”
                            </div>
                            <?php if (!empty($tm['content_bn'])): ?>
                                <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); margin-top: 0.35rem; line-height: 1.5;">
                                    🇧🇩 <?= Security::e($tm['content_bn']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <strong style="color: var(--text-heading); font-weight: 800; font-size: 0.95rem;"><?= (int) $tm['sort_order'] ?></strong>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-status <?= $tm['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                <?= $tm['is_active'] ? 'Active' : 'Draft' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.45rem;">
                                <a href="/admin/testimonials/edit/<?= (int) $tm['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                
                                <form action="/admin/testimonials/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?');" style="display: inline;">
                                    <?= Security::csrfField() ?>
                                    <input type="hidden" name="id" value="<?= (int) $tm['id'] ?>">
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
