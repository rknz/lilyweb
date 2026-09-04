<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Response;

/**
 * Base controller for all admin CMS controllers.
 *
 * Provides centralized authentication guard — any controller extending this
 * class automatically requires an authenticated admin session on every action.
 */
abstract class AdminController extends Controller
{
    /**
     * Verify admin authentication. Redirects to login and exits if not authenticated.
     * Called automatically via __construct for all admin routes.
     */
    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Response::redirect('/admin/login')->send();
            exit;
        }
    }
}
