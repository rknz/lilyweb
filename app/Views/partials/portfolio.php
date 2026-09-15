<?php

/**
 * Homepage Portfolio section matching FINAL_DESKTOP_UI.png
 * Filtered by Room/Space Types (Living Room, Bedroom, Kitchen, Office, Others).
 * Fully localized for English & Bengali.
 */
use Lilyweb\Core\View;
use Lilyweb\Core\Lang;
use Lilyweb\App\Models\Project;

$roomCategories = Project::roomTypes();
$portfolioProjects = Project::all();
?>
<section class="section portfolio" id="portfolio" aria-labelledby="portfolio-title">
    <div class="site-wrapper">
        <div class="section-header-split" data-reveal>
            <div class="header-titles">
                <span class="section-kicker"><?= Lang::get('portfolio_kicker') ?></span>
                <h2 id="portfolio-title" class="section-h2"><?= Lang::get('portfolio_h2') ?></h2>
            </div>
            <a class="section-top-link" href="/projects"><?= Lang::get('view_all_projects') ?> &rarr;</a>
        </div>

        <!-- Room / Space Category Filter Pills -->
        <div class="portfolio-filter-bar" data-reveal>
            <?php $first = true; foreach ($roomCategories as $key => $label): ?>
                <button type="button"
                        class="filter-pill <?= $first ? 'is-active' : '' ?>"
                        data-filter="<?= View::e($key) ?>">
                    <?= View::e($label) ?>
                </button>
            <?php $first = false; endforeach; ?>
        </div>

        <!-- 2 Rows x 3 Columns Grid -->
        <div class="portfolio-grid" id="portfolio-grid">
            <?php foreach ($portfolioProjects as $index => $project): ?>
                <article class="project-card <?= $index >= 6 ? 'card-extra' : '' ?>"
                         data-category="<?= View::e($project['room_type_key']) ?>"
                         data-reveal>
                    <a href="/projects/<?= View::e($project['slug']) ?>" class="project-card-link" aria-label="View <?= View::e($project['title']) ?>">
                        <div class="project-thumb">
                            <?php 
                            $coverImg = $project['image'] ?: '/assets/img/project-1.jpg';
                            // Only use .webp source replacement for predefined /assets/img/ files that have physical webp counterparts
                            $isAssetImg = str_starts_with($coverImg, '/assets/img/');
                            $webpSrc = $isAssetImg ? preg_replace('/\.(jpg|png|jpeg)$/i', '.webp', $coverImg) : $coverImg;
                            ?>
                            <?php if ($isAssetImg && $webpSrc !== $coverImg): ?>
                                <picture>
                                    <source srcset="<?= View::e($webpSrc) ?>" type="image/webp">
                                    <img src="<?= View::e($coverImg) ?>"
                                         alt="<?= View::e($project['title']) ?> - <?= View::e($project['location']) ?>"
                                         width="400" height="280"
                                         loading="lazy" decoding="async">
                                </picture>
                            <?php else: ?>
                                <img src="<?= View::e($coverImg) ?>"
                                     alt="<?= View::e($project['title']) ?> - <?= View::e($project['location']) ?>"
                                     width="400" height="280"
                                     loading="lazy" decoding="async">
                            <?php endif; ?>
                            <span class="project-cat-badge"><?= View::e($project['room_type']) ?></span>
                        </div>
                        <div class="project-body">
                            <h3 class="project-title"><?= View::e($project['title']) ?></h3>
                            <p class="project-location">&#128205; <?= View::e($project['location']) ?></p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- See More Projects Action -->
        <div class="see-more-wrap" data-reveal>
            <button class="btn btn-see-more" type="button" id="see-more-btn" data-see-more>
                <?= Lang::get('see_more_projects') ?> <span class="caret-down">&#8964;</span>
            </button>
        </div>
    </div>
</section>