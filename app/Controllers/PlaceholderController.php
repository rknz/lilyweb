<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Response;
use Lilyweb\Core\View;

/**
 * Placeholder controller proving the routing + DB connection + base layout stack
 * works end to end. Removed or replaced by real pages in later phases.
 */
class PlaceholderController extends Controller
{
    public function index(): Response
    {
        $dbOk = false;
        $dbMessage = '';

        try {
            $pdo = \Lilyweb\Core\Database::connect();
            $version = $pdo->query('SELECT VERSION()')->fetchColumn();
            $dbOk = $version !== false;
            $dbMessage = (string) $version;
        } catch (\Throwable $e) {
            $dbMessage = $e->getMessage();
        }

        $data = [
            'title' => 'Foundation Check',
            'dbOk' => $dbOk,
            'dbMessage' => $dbMessage,
            'sessionName' => session_name(),
        ];

        $view = View::make('pages.foundation', $data);
        $html = View::renderPartial('layouts.app', ['content' => $view->render(), 'title' => $data['title']]);

        return new Response($html);
    }
}
