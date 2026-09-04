<?php
use Lilyweb\Core\Security;
?>
<div class="page-actions">
    <a href="/admin/contacts/export" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.45rem;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        <span>Export CSV</span>
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Client Name</th>
                    <th>Phone / WhatsApp</th>
                    <th>Service & Location</th>
                    <th>Status</th>
                    <th>Date Received</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); font-size: 0.95rem; font-weight: 600; padding: 2.5rem;">
                            No consultation submissions yet. Client submissions from the website will appear here in real-time.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $l): ?>
                        <tr>
                            <td><strong style="color: var(--text-heading);">#<?= (int) $l['id'] ?></strong></td>
                            <td>
                                <div style="font-weight: 800; color: var(--text-heading); font-size: 0.95rem;"><?= Security::e($l['full_name']) ?></div>
                                <?php if (!empty($l['email_address'])): ?>
                                    <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-top: 0.15rem;">
                                        <a href="mailto:<?= Security::e($l['email_address']) ?>" style="color: #2563EB; text-decoration: none;"><?= Security::e($l['email_address']) ?></a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="tel:<?= Security::e($l['phone_number']) ?>" style="color: var(--crimson); font-weight: 800; font-size: 0.92rem; text-decoration: none;">
                                    <?= Security::e($l['phone_number']) ?>
                                </a>
                                <div style="font-size: 0.78rem; font-weight: 700; margin-top: 0.2rem;">
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $l['phone_number']) ?>" target="_blank" style="color: #16A34A; text-decoration: none;">💬 WhatsApp Chat ↗</a>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill-crimson"><?= Security::e($l['service_slug'] ?: 'Interior Design') ?></span>
                                <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-top: 0.25rem;">📍 <?= Security::e($l['project_location'] ?: 'Dhaka') ?></div>
                            </td>
                            <td>
                                <?php
                                $statusClass = match($l['status']) {
                                    'new' => 'badge-inactive',
                                    'contacted' => 'badge-active',
                                    'in_progress' => 'badge-active',
                                    'closed' => 'badge-active',
                                    default => 'badge-inactive',
                                };
                                ?>
                                <span class="badge-status <?= $statusClass ?>">
                                    <?= ucfirst(str_replace('_', ' ', $l['status'])) ?>
                                </span>
                            </td>
                            <td style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); white-space: nowrap;">
                                <?= date('M d, Y h:i A', strtotime($l['created_at'])) ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.45rem;">
                                    <a href="/admin/contacts/<?= (int) $l['id'] ?>" class="btn-secondary" style="padding: 0.42rem 0.85rem; font-size: 0.82rem;">View & Note</a>
                                    
                                    <form action="/admin/contacts/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');" style="display: inline;">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
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
