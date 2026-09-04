<?php
use Lilyweb\Core\Security;
?>

<!-- Upload Card -->
<div class="admin-card card-specs sticky-upload">
    <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <span>Upload New Image / Media Asset</span>
    </h3>

    <form action="/admin/media/upload" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1.25rem; align-items: flex-end;">
        <?= Security::csrfField() ?>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Select File (Max 10MB • JPG, PNG, WEBP, SVG, PDF) <span class="req">*</span></label>
            <input type="file" name="media_file" class="form-input" required accept="image/*,.pdf" style="padding: 0.55rem;">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">SEO Alt Text (EN - For Google Image Search)</label>
            <input type="text" name="alt_en" class="form-input" placeholder="e.g. Modern Living Room Interior in Gulshan">
        </div>

        <button type="submit" class="btn-primary" style="height: 44px; padding: 0 1.5rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <span>Upload Asset</span>
        </button>
    </form>
</div>

<!-- Media Library Grid -->
<div class="admin-card">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        <?php foreach ($media as $item): ?>
            <div style="background: var(--bg-card); border: 1.5px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--card-shadow);">
                <div style="height: 160px; background: #0F172A; position: relative; overflow: hidden; display: grid; place-items: center;">
                    <?php if (str_starts_with($item['mime_type'], 'image/')): ?>
                        <img src="<?= Security::e($item['storage_path']) ?>" alt="<?= Security::e($item['alt_en'] ?? '') ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="color: #94A3B8; font-size: 0.85rem; text-align: center;">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <div style="font-weight: 700; margin-top: 0.35rem;">PDF Document</div>
                        </div>
                    <?php endif; ?>

                    <span style="position: absolute; top: 8px; right: 8px; font-size: 0.72rem; font-weight: 700; background: rgba(15, 23, 42, 0.85); color: #FFFFFF; padding: 0.2rem 0.5rem; border-radius: 4px;">
                        <?= round($item['size_bytes'] / 1024, 1) ?> KB
                        <?php if ($item['width']): ?>
                            • <?= (int) $item['width'] ?>x<?= (int) $item['height'] ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= Security::e($item['original_name']) ?>">
                            <?= Security::e($item['original_name']) ?>
                        </div>

                        <div style="margin-top: 0.65rem; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="text" readonly value="<?= Security::e($item['storage_path']) ?>" id="path_<?= (int) $item['id'] ?>" class="form-input" style="font-family: monospace; font-size: 0.75rem; padding: 0.4rem 0.6rem;">
                            <button type="button" onclick="navigator.clipboard.writeText('<?= Security::e($item['storage_path']) ?>'); alert('Copied path: <?= Security::e($item['storage_path']) ?>');" class="btn-secondary" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; white-space: nowrap;">Copy</button>
                        </div>

                        <!-- SEO Alt Edit Form -->
                        <form action="/admin/media/update" method="POST" style="margin-top: 0.85rem;">
                            <?= Security::csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                            <label style="font-size: 0.76rem; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">SEO Alt Tag:</label>
                            <div style="display: flex; gap: 0.35rem;">
                                <input type="text" name="alt_en" value="<?= Security::e($item['alt_en'] ?? '') ?>" placeholder="Alt description" class="form-input" style="font-size: 0.8rem; padding: 0.35rem 0.6rem;">
                                <button type="submit" class="btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.75rem;">Save</button>
                            </div>
                        </form>
                    </div>

                    <div style="margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <a href="<?= Security::e($item['storage_path']) ?>" target="_blank" style="font-size: 0.8rem; font-weight: 700; color: var(--crimson); text-decoration: none;">View Full ↗</a>
                        
                        <form action="/admin/media/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this media asset?');" style="display: inline;">
                            <?= Security::csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                            <button type="submit" style="background: none; border: none; color: #DC2626; font-size: 0.8rem; font-weight: 700; cursor: pointer; padding: 0;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
