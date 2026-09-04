<?php

/**
 * Homepage — desktop sections in approved order:
 * Hero → Stats → About → Services → Process → Portfolio → Testimonials → Contact → Footer.
 *
 * Data ($stats, $services, $projects) come from the controller; each section
 * partial supplies safe defaults when data is absent.
 */

use Lilyweb\Core\View;
use Lilyweb\Core\SiteContent;

// Each section partial draws from the shared SiteContent when no specific data
// is supplied, so the sections can never render empty unexpectedly.
echo View::renderPartial('partials.hero');
echo View::renderPartial('partials.stats', ['stats' => $stats ?? SiteContent::stats()]);
echo View::renderPartial('partials.about');
echo View::renderPartial('partials.services', ['services' => $services ?? SiteContent::services()]);
echo View::renderPartial('partials.process');
echo View::renderPartial('partials.portfolio', ['projects' => $projects ?? SiteContent::projects()]);
echo View::renderPartial('partials.testimonials');
echo View::renderPartial('partials.contact');
