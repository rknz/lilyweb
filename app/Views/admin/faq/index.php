<?php
use Lilyweb\Core\Security;
?>
<div class="card-header-flex">
    <div>
        <h2 class="card-title">FAQ Items (<?= count($faqs) ?> Questions)</h2>
        <p class="card-subtitle">Edits take effect immediately on the homepage FAQ accordion and Google FAQPage Schema.org JSON-LD.</p>
    </div>
    <a href="/admin/faq/create" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Add New FAQ</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 120px;">Category</th>
                    <th style="min-width: 250px;">Question (EN & BN)</th>
                    <th style="min-width: 340px;">Detailed Answer</th>
                    <th style="width: 70px; text-align: center;">Order</th>
                    <th style="width: 90px; text-align: center;">Status</th>
                    <th style="min-width: 130px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $f): ?>
                    <tr>
                        <td>
                            <span class="badge-pill-blue">
                                <?= Security::e($f['category']) ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem; line-height: 1.35;">
                                <?= Security::e($f['question_en']) ?>
                            </div>
                            <?php if (!empty($f['question_bn'])): ?>
                                <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); margin-top: 0.25rem; line-height: 1.4;">
                                    🇧🇩 <?= Security::e($f['question_bn']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-size: 0.88rem; color: var(--text-main); line-height: 1.55; font-weight: 500;">
                                <?= Security::e($f['answer_en']) ?>
                            </div>
                            <?php if (!empty($f['answer_bn'])): ?>
                                <div style="font-size: 0.82rem; font-weight: 500; color: var(--text-muted); margin-top: 0.35rem; line-height: 1.5;">
                                    🇧🇩 <?= Security::e($f['answer_bn']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <strong style="color: var(--text-heading); font-weight: 800; font-size: 0.95rem;"><?= (int) $f['sort_order'] ?></strong>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-status <?= $f['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                <?= $f['is_active'] ? 'Active' : 'Draft' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.45rem;">
                                <a href="/admin/faq/edit/<?= (int) $f['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">Edit</a>
                                
                                <form action="/admin/faq/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ item?');" style="display: inline;">
                                    <?= Security::csrfField() ?>
                                    <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
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
