<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Response;
use Lilyweb\Core\View;
use Lilyweb\Core\SiteContent;

/**
 * Homepage controller (foundation). Full homepage build is Phase 4.
 * Shared content blocks (stats, services, projects) come from SiteContent so
 * the homepage and the partials stay in exact sync.
 */
class HomeController extends Controller
{
    public function index(): Response
    {
        $data = [
            'stats' => SiteContent::stats(),
            'services' => SiteContent::services(),
            'projects' => SiteContent::projects(),
            'title' => 'Lily Interiors — Home',
            'active' => 'home',
        ];

        $view = View::make('pages.home', $data);
        $html = View::renderPartial('layouts.app', $data + ['content' => $view->render()]);

        return new Response($html);
    }
}
