<?php
use Lilyweb\Core\Security;

// Pre-process JSON data for form inputs
$featuresArr = json_decode($project['features_json'] ?? '[]', true);
if (!is_array($featuresArr)) $featuresArr = [];
$featuresText = implode("\n", array_filter($featuresArr, 'is_string'));

$materialsArr = json_decode($project['materials_json'] ?? '[]', true);
if (!is_array($materialsArr)) $materialsArr = [];
$materialsText = implode("\n", array_filter($materialsArr, 'is_string'));

$highlightsArr = json_decode($project['highlights_json'] ?? '[]', true);
if (!is_array($highlightsArr)) $highlightsArr = [];
$highlightsTitles = array_map(function($h) {
    return is_array($h) ? ($h['title'] ?? '') : (string)$h;
}, $highlightsArr);
$highlightsText = implode("\n", array_filter($highlightsTitles));

// Ensure valid gallery JSON
$galleryJson = $project['gallery_json'] ?? '[]';
if (empty($galleryJson) || $galleryJson === 'null') {
    $galleryJson = !empty($project['cover_image']) ? json_encode([$project['cover_image']]) : '[]';
}
?>
<!-- Highlighted Back Button with Generous Spacing -->
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <a href="/admin/projects" class="btn-back-highlight">
        <span>&larr; Back to Projects Portfolio</span>
    </a>
    <?php if (!empty($project['id'])): ?>
        <a href="/projects/<?= Security::e($project['slug'] ?? '') ?>" target="_blank" class="btn-secondary" style="font-size: 0.82rem; padding: 0.45rem 1rem;">
            <span>↗ View Live Page</span>
        </a>
    <?php endif; ?>
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
                <textarea name="summary_en" class="form-textarea" style="min-height: 90px;" required placeholder="Brief narrative of this project"><?= Security::e($project['summary_en'] ?? '') ?></textarea>
                <p class="form-help">Lead paragraph displayed on project details page.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Detailed Solution / Narrative (EN)</label>
                <textarea name="description_en" class="form-textarea" style="min-height: 90px;" placeholder="Full architectural and design details"><?= Security::e($project['description_en'] ?? '') ?></textarea>
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

            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Key Features Checklist (One feature per line)</label>
                <textarea name="features_en" class="form-textarea" style="min-height: 95px; font-size: 0.85rem;" placeholder="Open-plan layout with natural illumination&#10;Custom acoustic wall cladding&#10;Smart circadian ambient lighting"><?= Security::e($featuresText) ?></textarea>
                <p class="form-help">Displayed with red checkmark icons in the Details tab.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Materials &amp; Finishes Specifications (One per line)</label>
                <textarea name="materials_en" class="form-textarea" style="min-height: 85px; font-size: 0.85rem;" placeholder="Italian Statuario marble floor tiles&#10;European White Oak veneer paneling&#10;German Blum soft-close hardware"><?= Security::e($materialsText) ?></textarea>
                <p class="form-help">Displayed in the Finishes &amp; Materials tab.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Project Highlights (One per line)</label>
                <textarea name="highlights_en" class="form-textarea" style="min-height: 75px; font-size: 0.85rem;" placeholder="Custom Joinery & Woodwork&#10;Concealed Ambient Lighting&#10;100% Quality Execution"><?= Security::e($highlightsText) ?></textarea>
                <p class="form-help">Displayed in the Highlights card.</p>
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
                <textarea name="summary_bn" class="form-textarea" style="min-height: 90px;" placeholder="বাংলা বিবরণ"><?= Security::e($project['summary_bn'] ?? '') ?></textarea>
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

            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">প্রধান ফিচারসমূহ (বাংলা - প্রতি লাইনে একটি)</label>
                <textarea name="features_bn" class="form-textarea" style="min-height: 95px; font-size: 0.85rem;" placeholder="উন্মুক্ত লিভিং ও ডাইনিং স্পেস লেআউট&#10;কাস্টম মেড ফার্নিচার ও গোপন স্টোরেজ"><?= Security::e($project['features_bn'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">ব্যবহৃত ম্যাটেরিয়ালস (বাংলা - প্রতি লাইনে একটি)</label>
                <textarea name="materials_bn" class="form-textarea" style="min-height: 85px; font-size: 0.85rem;" placeholder="ইতালীয় মার্বেল ও কোয়ার্টজ ফিনিশিং&#10;আমদানিকৃত ইউরোপিয়ান ওক কাঠ"><?= Security::e($project['materials_bn'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Specifications, Categories, URLs & Instant Photo Uploader -->
    <div class="admin-card card-specs" style="margin-top: 1.5rem;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.4rem;">⚙️ Specifications &amp; Taxonomies</h3>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Room Type Category <span class="req" style="color: var(--crimson);">*</span></label>
                <select name="room_type_key" class="form-input">
                    <option value="living_room" <?= in_array(($project['room_type_key'] ?? ''), ['living_room', 'living-room']) ? 'selected' : '' ?>>Living Room</option>
                    <option value="bedroom" <?= ($project['room_type_key'] ?? '') === 'bedroom' ? 'selected' : '' ?>>Bedroom</option>
                    <option value="kitchen" <?= ($project['room_type_key'] ?? '') === 'kitchen' ? 'selected' : '' ?>>Gourmet Kitchen</option>
                    <option value="office" <?= ($project['room_type_key'] ?? '') === 'office' ? 'selected' : '' ?>>Office / Commercial</option>
                    <option value="others" <?= ($project['room_type_key'] ?? '') === 'others' ? 'selected' : '' ?>>Others / Exterior</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Property / Project Type <span class="req" style="color: var(--crimson);">*</span></label>
                <select name="property_type_key" class="form-input">
                    <option value="residential" <?= ($project['property_type_key'] ?? '') === 'residential' ? 'selected' : '' ?>>Residential</option>
                    <option value="commercial" <?= ($project['property_type_key'] ?? '') === 'commercial' ? 'selected' : '' ?>>Commercial</option>
                    <option value="turnkey-duplex" <?= in_array(($project['property_type_key'] ?? ''), ['turnkey', 'turnkey-duplex', 'turnkey_duplex']) ? 'selected' : '' ?>>Turnkey Duplex</option>
                    <option value="renovation" <?= ($project['property_type_key'] ?? '') === 'renovation' ? 'selected' : '' ?>>Renovation</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">URL Slug <span class="req" style="color: var(--crimson);">*</span></label>
                <input type="text" name="slug" class="form-input" required value="<?= Security::e($project['slug'] ?? '') ?>" placeholder="e.g. modern-luxury-apartment">
                <p class="form-help">SEO friendly URL slug (/projects/your-slug).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Floor Area Size</label>
                <input type="text" name="area_sqft" class="form-input" value="<?= Security::e($project['area_sqft'] ?? '2,800 sqft') ?>" placeholder="2,800 sqft">
            </div>

            <div class="form-group">
                <label class="form-label">Completion Year</label>
                <input type="text" name="completion_year" class="form-input" value="<?= Security::e($project['completion_year'] ?? '2026') ?>" placeholder="2026">
            </div>

            <div class="form-group">
                <label class="form-label">Project Status</label>
                <select name="project_status" class="form-input">
                    <option value="Completed" <?= ($project['project_status'] ?? '') === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="In Progress" <?= ($project['project_status'] ?? '') === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Concept" <?= ($project['project_status'] ?? '') === 'Concept' ? 'selected' : '' ?>>Concept</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Display Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= (int) ($project['sort_order'] ?? 1) ?>" min="0" max="99">
            </div>
        </div>
    </div>

    <!-- Primary Cover Image -->
    <div class="admin-card" style="margin-top: 1.5rem;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 0.5rem;">🖼️ Primary Cover Image</h3>
        <p style="color: var(--text-muted); font-size: 0.84rem; margin-bottom: 1.25rem;">
            This is the main showcase image displayed on homepage portfolio cards, project directory cards, and as the initial slider photo.
        </p>

        <div class="media-uploader-box" data-input-name="cover_image">
            <input type="hidden" name="cover_image" value="<?= Security::e($project['cover_image'] ?? '') ?>">
            <input type="file" class="hidden-file-input" accept="image/*" style="display: none;">
            
            <div class="media-uploader-actions">
                <button type="button" class="btn-uploader-upload">
                    <span>📤 Upload Cover Directly</span>
                </button>
                <button type="button" class="btn-uploader-picker">
                    <span>📁 Choose from Media Library</span>
                </button>
            </div>

            <div class="media-preview-container">
                <?php if (!empty($project['cover_image'])): ?>
                    <img src="<?= asset($project['cover_image']) ?>" alt="" class="media-preview-thumb">
                    <div class="media-preview-meta">
                        <div class="media-preview-url"><?= Security::e($project['cover_image']) ?></div>
                    </div>
                    <button type="button" class="btn-media-remove">Remove</button>
                <?php else: ?>
                    <p style="font-size: 0.78rem; color: var(--text-muted);">No cover image selected yet. Click Upload or Choose above.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Interactive Project Gallery & Slider Photos Manager -->
    <div class="admin-card" style="margin-top: 1.5rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.6rem;">
                <span>📸 Project Gallery &amp; Detail Page Slider Photos</span>
                <span class="gallery-counter-badge">
                    <span class="gallery-count-num">0</span> Photos Added
                </span>
            </h3>
        </div>
        <p style="color: var(--text-muted); font-size: 0.84rem; margin-bottom: 1.25rem;">
            These high-resolution photos populate the <strong>Interactive Main Slider</strong>, <strong>8-Thumbnail Navigation Row</strong>, and the <strong>Photo Gallery Tab</strong> on the live project details page.
        </p>

        <div class="gallery-manager-box" id="project_gallery_manager">
            <input type="hidden" name="gallery_json" class="gallery-json-data" value="<?= Security::e($galleryJson) ?>">
            <input type="file" class="gallery-file-input" multiple accept="image/*" style="display: none;">

            <div class="media-uploader-actions">
                <button type="button" class="btn-uploader-upload btn-gallery-upload">
                    <span>📤 Upload Multiple Photos Directly</span>
                </button>
                <button type="button" class="btn-uploader-picker btn-gallery-picker">
                    <span>📁 Choose Multiple from Media Library</span>
                </button>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Select multiple images at once (JPG, PNG, WEBP).</span>
            </div>

            <!-- Live Thumbnail Preview Grid -->
            <div class="gallery-grid-preview">
                <!-- Populated automatically by JS -->
            </div>
        </div>
    </div>

    <!-- Proposal PDF Document -->
    <div class="admin-card" style="margin-top: 1.5rem;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 0.5rem;">📄 Project Proposal Document (PDF)</h3>
        <p style="color: var(--text-muted); font-size: 0.84rem; margin-bottom: 1.25rem;">
            Optional PDF proposal brochure downloaded by clients clicking "Download Proposal" on the project details page.
        </p>

        <div class="media-uploader-box" data-input-name="proposal_pdf">
            <input type="hidden" name="proposal_pdf" value="<?= Security::e($project['proposal_pdf'] ?? '') ?>">
            <input type="file" class="hidden-file-input" accept="application/pdf,image/*" style="display: none;">
            
            <div class="media-uploader-actions">
                <button type="button" class="btn-uploader-upload">
                    <span>📤 Upload PDF Document</span>
                </button>
                <button type="button" class="btn-uploader-picker">
                    <span>📁 Select from Media Assets</span>
                </button>
            </div>

            <div class="media-preview-container">
                <?php if (!empty($project['proposal_pdf'])): ?>
                    <div style="font-size: 2rem;">📄</div>
                    <div class="media-preview-meta">
                        <div class="media-preview-url"><?= Security::e($project['proposal_pdf']) ?></div>
                    </div>
                    <button type="button" class="btn-media-remove">Remove</button>
                <?php else: ?>
                    <p style="font-size: 0.78rem; color: var(--text-muted);">No proposal document attached yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Publishing & Visibility Options -->
    <div class="admin-card card-specs" style="margin-top: 1.5rem;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 1.25rem;">🌐 Publishing &amp; Display Settings</h3>

        <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-bottom: 1.75rem;">
            <label style="display: inline-flex; align-items: center; gap: 0.65rem; font-size: 0.92rem; font-weight: 700; color: var(--text-heading); cursor: pointer; user-select: none;">
                <input type="checkbox" name="is_active" value="1" <?= (!empty($project['is_active']) || !isset($project['is_active'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson); cursor: pointer;">
                <span>Published (Visible on live website)</span>
            </label>

            <label style="display: inline-flex; align-items: center; gap: 0.65rem; font-size: 0.92rem; font-weight: 700; color: var(--text-heading); cursor: pointer; user-select: none;">
                <input type="checkbox" name="show_on_home" value="1" <?= (!empty($project['show_on_home']) || !isset($project['show_on_home'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson); cursor: pointer;">
                <span>Show in Homepage Portfolio Grid</span>
            </label>

            <label style="display: inline-flex; align-items: center; gap: 0.65rem; font-size: 0.92rem; font-weight: 700; color: var(--text-heading); cursor: pointer; user-select: none;">
                <input type="checkbox" name="is_featured" value="1" <?= (!empty($project['is_featured'])) ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: var(--crimson); cursor: pointer;">
                <span>Mark as Featured Showcase</span>
            </label>
        </div>

        <div style="padding-top: 1.25rem; border-top: 1px solid var(--border-subtle); display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <button type="submit" class="btn-primary" style="padding: 0.85rem 2.2rem; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.6rem;">
                <span>💾 Save &amp; Update Project</span>
                <span>&rarr;</span>
            </button>
            <a href="/admin/projects" class="btn-secondary" style="padding: 0.85rem 1.6rem;">Cancel</a>
        </div>
    </div>
</form>
