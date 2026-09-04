<?php
use Lilyweb\Core\Security;
?>
<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem;">
    <a href="/admin/projects" class="btn-back-highlight">
        <span>&larr; Back to Projects Portfolio</span>
    </a>
</div>

<form action="/admin/projects/save" method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= (int) ($project['id'] ?? 0) ?>">

    <div class="form-grid">
        <!-- English Column (Master) -->
        <div class="admin-card card-master-en">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.4rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.5rem;">
                    <span>🇬🇧 English Content</span>
                </h3>
                <span class="badge-pill-blue">Required Master</span>
            </div>

            <div class="form-group">
                <label class="form-label">Project Title (EN) <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="title_en" class="form-input" required value="<?= Security::e($project['title_en'] ?? '') ?>" placeholder="e.g. Modern Luxury Apartment">
                <p class="form-help">Headline name of the project showcase.</p>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Location (EN) <span class="req" style="color: var(--crimson);">*</span></label>
                    <input type="text" name="location_en" class="form-input" required value="<?= Security::e($project['location_en'] ?? '') ?>" placeholder="e.g. Gulshan 2, Dhaka">
                </div>

                <div class="form-group">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-input" value="<?= Security::e($project['client_name'] ?? 'Private Client') ?>" placeholder="e.g. Mr. & Mrs. Chowdhury">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Executive Summary / Overview (EN) <span class="req" style="color: var(--crimson);">*</span></label>
                <textarea name="summary_en" class="form-textarea" style="min-height: 100px;" required placeholder="Brief narrative of this project"><?= Security::e($project['summary_en'] ?? '') ?></textarea>
                <p class="form-help">Lead paragraph displayed on project details page.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Detailed Solution / Narrative (EN)</label>
                <textarea name="description_en" class="form-textarea" style="min-height: 90px;" placeholder="Full architectural details"><?= Security::e($project['description_en'] ?? '') ?></textarea>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Room Layout Breakdown (EN)</label>
                    <input type="text" name="room_details_en" class="form-input" value="<?= Security::e($project['room_details_en'] ?? '4 Bed, 5 Bath, Living, Dining') ?>" placeholder="4 Bed, 5 Bath">
                </div>
                <div class="form-group">
                    <label class="form-label">Design Style (EN)</label>
                    <input type="text" name="design_style_en" class="form-input" value="<?= Security::e($project['design_style_en'] ?? 'Modern Luxury Minimalism') ?>" placeholder="Modern Luxury Minimalism">
                </div>
            </div>
        </div>

        <!-- Bengali Column (Optional Fallback) -->
        <div class="admin-card card-optional-bn">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.4rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.5rem;">
                    <span>🇧🇩 বাংলা অনুবাদ (Bengali)</span>
                </h3>
                <span class="badge-pill-crimson">ঐচ্ছিক অনুবাদ</span>
            </div>

            <div class="form-group">
                <label class="form-label">প্রজেক্ট টাইটেল (বাংলা) <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <input type="text" name="title_bn" class="form-input" value="<?= Security::e($project['title_bn'] ?? '') ?>" placeholder="যেমন: মডার্ন লাক্সারি অ্যাপার্টমেন্ট">
            </div>

            <div class="form-group">
                <label class="form-label">লোকেশন (বাংলা) <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <input type="text" name="location_bn" class="form-input" value="<?= Security::e($project['location_bn'] ?? '') ?>" placeholder="যেমন: গুলশান ২, ঢাকা">
            </div>

            <div class="form-group">
                <label class="form-label">প্রজেক্ট সারাংশ (বাংলা) <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <textarea name="summary_bn" class="form-textarea" style="min-height: 100px;" placeholder="বাংলা বিবরণ"><?= Security::e($project['summary_bn'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">বিস্তারিত সমাধান (বাংলা) <span class="opt" style="color: var(--text-muted);">(Optional)</span></label>
                <textarea name="description_bn" class="form-textarea" style="min-height: 90px;" placeholder="বাংলা সমাধান বিবরণ..."><?= Security::e($project['description_bn'] ?? '') ?></textarea>
            </div>

            <div class="form-grid" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">রুম বিবরণ (বাংলা)</label>
                    <input type="text" name="room_details_bn" class="form-input" value="<?= Security::e($project['room_details_bn'] ?? '') ?>" placeholder="যেমন: ৪ বেড, ৫ বাথ">
                </div>
                <div class="form-group">
                    <label class="form-label">ডিজাইন স্টাইল (বাংলা)</label>
                    <input type="text" name="design_style_bn" class="form-input" value="<?= Security::e($project['design_style_bn'] ?? '') ?>" placeholder="যেমন: মডার্ন লাক্সারি মিনিমালিজম">
                </div>
            </div>
        </div>
    </div>

    <!-- Specifications, Categories, URLs & Instant Photo Uploader -->
    <div class="admin-card card-specs">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">Specifications, Primary Cover Image &amp; Gallery</h3>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Project Category <span class="req" style="color: var(--crimson);">*</span></label>
                <select name="category_id" class="form-input">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int) $cat['id'] ?>" <?= ((int)($project['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
                            <?= Security::e($cat['name_en']) ?> (<?= Security::e($cat['slug']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Room Type Key <span class="req" style="color: var(--crimson);">*</span></label>
                <select name="room_type_key" class="form-input">
                    <option value="living-room" <?= ($project['room_type_key'] ?? '') === 'living-room' ? 'selected' : '' ?>>Living Room</option>
                    <option value="bedroom" <?= ($project['room_type_key'] ?? '') === 'bedroom' ? 'selected' : '' ?>>Bedroom</option>
                    <option value="kitchen" <?= ($project['room_type_key'] ?? '') === 'kitchen' ? 'selected' : '' ?>>Gourmet Kitchen</option>
                    <option value="office" <?= ($project['room_type_key'] ?? '') === 'office' ? 'selected' : '' ?>>Office / Commercial</option>
                    <option value="others" <?= ($project['room_type_key'] ?? '') === 'others' ? 'selected' : '' ?>>Others / Exterior</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">URL Slug <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="slug" class="form-input" required value="<?= Security::e($project['slug'] ?? '') ?>" placeholder="e.g. modern-luxury-apartment">
                <p class="form-help">SEO friendly URL slug (/projects/your-slug).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Floor Area Size</label>
                <input type="text" name="area_sqft" class="form-input" value="<?= Security::e($project['area_sqft'] ?? '2,800 sq.ft') ?>" placeholder="2,800 sq.ft">
            </div>

            <div class="form-group">
                <label class="form-label">Completion Year</label>
                <input type="text" name="completion_year" class="form-input" value="<?= Security::e($project['completion_year'] ?? '2024') ?>" placeholder="2024">
            </div>

            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($project['sort_order'] ?? 1) ?>" min="0" max="99">
            </div>
        </div>

        <!-- Primary Cover Image with Instant Upload & Media Picker -->
        <div class="form-group" style="margin-top: 1.5rem;">
            <label class="form-label">Primary Cover Image <span class="req" style="color: var(--crimson);">*</span></label>
            <div class="media-uploader-box" data-input-name="cover_image">
                <input type="hidden" name="cover_image" value="<?= Security::e($project['cover_image'] ?? '') ?>">
                <input type="file" class="hidden-file-input" accept="image/*" style="display: none;">
                
                <div class="media-uploader-actions">
                    <button type="button" class="btn-uploader-upload">
                        <span>📤 Upload Photo Directly</span>
                    </button>
                    <button type="button" class="btn-uploader-picker">
                        <span>📁 Choose from Media Library</span>
                    </button>
                    <span style="font-size: 0.78rem; color: var(--text-muted);">Instant direct upload or select from 248+ media assets.</span>
                </div>

                <div class="media-preview-container">
                    <?php if (!empty($project['cover_image'])): ?>
                        <img src="<?= Security::e($project['cover_image']) ?>" alt="" class="media-preview-thumb">
                        <div class="media-preview-meta">
                            <div class="media-preview-url"><?= Security::e($project['cover_image']) ?></div>
                        </div>
                        <button type="button" class="btn-media-remove">Remove</button>
                    <?php else: ?>
                        <p style="font-size: 0.78rem; color: var(--text-muted);">No image selected yet. Click Upload or Choose above.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-top: 1.25rem;">
            <label class="form-label">Gallery Images (One URL per line)</label>
            <textarea name="gallery_images" class="form-textarea" style="min-height: 100px; font-family: monospace; font-size: 0.85rem;" placeholder="/assets/img/project-1.webp&#10;/assets/img/project-2.webp"><?= Security::e(is_array($project['gallery_images'] ?? null) ? implode("\n", $project['gallery_images']) : ($project['gallery_images'] ?? '')) ?></textarea>
            <p class="form-help">Enter high-resolution gallery URLs line-by-line for the project lightbox slider.</p>
        </div>

        <div style="display: flex; gap: 2rem; margin-top: 1.25rem; flex-wrap: wrap;">
            <label style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" <?= (!empty($project['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <span>Published (Visible on live site)</span>
            </label>

            <label style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">
                <input type="checkbox" name="show_on_home" value="1" <?= (!empty($project['show_on_home'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <span>Show in Homepage 6-Card Grid</span>
            </label>

            <label style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; font-weight: 700; color: var(--text-heading); cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" <?= (!empty($project['is_featured'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson);">
                <span>Mark as Featured Project</span>
            </label>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.6rem; font-size: 0.92rem;">Save &amp; Update Live Site &rarr;</button>
            <a href="/admin/projects" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>
