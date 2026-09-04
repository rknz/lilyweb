<?php
use Lilyweb\Core\Security;
?>
<div class="page-actions">
    <a href="/admin/contacts" class="btn-secondary">← Back to Leads</a>
</div>

<div class="form-grid">
    <!-- Client Details Card -->
    <div class="admin-card card-master-en">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Client Inquiry Details</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Full Name</label>
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-heading);"><?= Security::e($lead['full_name']) ?></div>
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Phone Number</label>
                <div>
                    <a href="tel:<?= Security::e($lead['phone_number']) ?>" style="color: var(--crimson); font-weight: 800; font-size: 1.05rem; text-decoration: none;">
                        <?= Security::e($lead['phone_number']) ?>
                    </a>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['phone_number']) ?>" target="_blank" style="margin-left: 0.75rem; color: #16A34A; font-weight: 700; font-size: 0.85rem; text-decoration: none;">💬 WhatsApp ↗</a>
                </div>
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Email Address</label>
                <div style="color: #2563EB; font-weight: 600; font-size: 0.95rem;">
                    <?= !empty($lead['email_address']) ? Security::e($lead['email_address']) : '<span style="color: var(--text-muted);">Not provided</span>' ?>
                </div>
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Service & Location</label>
                <div style="color: var(--text-main); font-weight: 700; font-size: 0.95rem;">
                    <?= Security::e($lead['service_slug'] ?: 'Interior Design') ?> • <?= Security::e($lead['project_location'] ?: 'Dhaka') ?>
                </div>
            </div>
        </div>

        <div style="margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--border-color);">
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.45rem;">Message / Requirements Narrative</label>
            <div style="background: var(--bg-card-alt); border: 1.5px solid var(--border-color); border-radius: var(--radius-md); padding: 1.1rem; color: var(--text-heading); font-size: 0.92rem; line-height: 1.6; font-weight: 500; white-space: pre-wrap;"><?= Security::e($lead['message']) ?></div>
        </div>
    </div>

    <!-- Status & Admin Notes -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Follow-up Status & Private Notes</h3>

        <form action="/admin/contacts/status" method="POST">
            <?= Security::csrfField() ?>
            <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">

            <div class="form-group">
                <label class="form-label">Inquiry Status</label>
                <select name="status" class="form-input">
                    <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>New Inquiry (Uncontacted)</option>
                    <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>Contacted (Initial Call Done)</option>
                    <option value="in_progress" <?= $lead['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress (Site Visit / Proposal Sent)</option>
                    <option value="closed" <?= $lead['status'] === 'closed' ? 'selected' : '' ?>>Closed (Project Awarded / Concluded)</option>
                    <option value="archived" <?= $lead['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Private Owner Notes</label>
                <textarea name="admin_notes" class="form-textarea" style="min-height: 140px;" placeholder="Add internal notes about client meeting, budget discussion, site survey date..."><?= Security::e($lead['admin_notes'] ?? '') ?></textarea>
                <p class="form-help">Only visible to the owner in this admin panel.</p>
            </div>

            <div style="margin-top: 1.75rem; display: flex; gap: 1rem; align-items: center;">
                <button type="submit" class="btn-primary">Update Status & Save Notes</button>
                <a href="/admin/contacts" class="btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
