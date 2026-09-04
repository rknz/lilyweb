<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * Base controller. Provides common rendering helpers for all controllers.
 */
abstract class Controller
{
    protected function view(string $view, array $data = []): View
    {
        return View::make($view, $data);
    }

    protected function render(string $view, array $data = []): Response
    {
        return new Response(View::make($view, $data)->render());
    }

    protected function respond(): Response
    {
        return new Response();
    }
}
