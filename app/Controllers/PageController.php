<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Response;
use Lilyweb\Core\View;

class PageController extends Controller
{
    public function projects(): Response
    {
        return $this->renderPage('pages.projects', [
            'title' => 'Projects — Lily Interiors',
            'active' => 'projects',
        ]);
    }

    public function about(): Response
    {
        return $this->renderPage('pages.about', [
            'title' => 'About Us — Lily Interior',
            'active' => 'about',
        ]);
    }

    public function contact(): Response
    {
        return Response::redirect('/#contact');
    }

    public function services(): Response
    {
        return $this->renderPage('pages.services', [
            'title' => 'Services — Lily Interiors',
            'active' => 'services',
        ]);
    }

    public function faq(): Response
    {
        return $this->renderPage('pages.faq', [
            'title' => 'Frequently Asked Questions (FAQ) — Lily Interiors',
            'active' => 'faq',
        ]);
    }

    public function privacyPolicy(): Response
    {
        return $this->renderPage('pages.privacy-policy', [
            'title' => 'Privacy Policy — Lily Interiors',
            'active' => '',
        ]);
    }

    public function terms(): Response
    {
        return $this->renderPage('pages.terms', [
            'title' => 'Terms — Lily Interiors',
            'active' => '',
        ]);
    }

    public function show(array $params = []): Response
    {
        $slug = $params['slug'] ?? '';
        if (empty($slug)) {
            return Response::redirect('/');
        }
        
        try {
            $pdo = \Lilyweb\Core\Database::connect();
            $stmt = $pdo->prepare("SELECT * FROM lilyweb_pages WHERE slug = ? AND is_active = 1 LIMIT 1");
            $stmt->execute([$slug]);
            $page = $stmt->fetch();
            if ($page) {
                $isBn = \Lilyweb\Core\Lang::isBn();
                $title = ($isBn && !empty($page['title_bn'])) ? $page['title_bn'] : ($page['title_en'] ?? $page['title']);
                $content = ($isBn && !empty($page['content_bn'])) ? $page['content_bn'] : ($page['content_en'] ?? $page['content']);
                $metaDesc = ($isBn && !empty($page['meta_desc_bn'])) ? $page['meta_desc_bn'] : ($page['meta_desc_en'] ?? '');
                $html = View::renderPartial('layouts.app', [
                    'title' => $title . ' — Lily Interiors',
                    'meta_desc' => $metaDesc,
                    'active' => '',
                    'content' => '<section class="section" style="padding: 4rem 1.5rem;"><div class="container" style="max-width: 900px; margin: 0 auto;"><h1 style="margin-bottom: 2rem;">' . View::e($title) . '</h1><div class="prose">' . $content . '</div></div></section>'
                ]);
                return new Response($html);
            }
        } catch (\Throwable $e) {}

        throw new \Lilyweb\Core\Exceptions\HttpException(404, 'Page not found');
    }

    private function renderPage(string $view, array $data): Response
    {
        $content = View::make($view, $data)->render();
        $html = View::renderPartial('layouts.app', $data + ['content' => $content]);
        return new Response($html);
    }
}
