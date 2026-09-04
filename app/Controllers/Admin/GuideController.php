<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Response;
use Lilyweb\Core\View;
use Lilyweb\Core\Auth;

class GuideController extends Controller
{
    public function index(): Response
    {
        if (!Auth::check()) {
            return Response::redirect('/admin/login');
        }

        $content = View::make('admin.guide.index')->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'ব্যবহার নির্দেশিকা (User Guide)',
            'pageHeading' => 'ড্যাশবোর্ড ব্যবহার নির্দেশিকা ও গাইডলাইন',
            'content' => $content,
        ]);

        return new Response($html);
    }
}
