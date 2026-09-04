<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Response;
use Lilyweb\Core\View;
use Lilyweb\Core\Exceptions\HttpException;
use Lilyweb\App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display the full portfolio projects directory.
     * Filtered by Project/Property Types (Residential, Commercial, Turnkey, Renovation).
     */
    public function index(): Response
    {
        $projects = Project::all();
        $projectTypes = Project::projectTypes();

        $content = View::make('pages.projects', [
            'projects' => $projects,
            'categories' => $projectTypes,
        ])->render();

        $html = View::renderPartial('layouts.app', [
            'title' => 'Our Portfolio & Interior Projects — Lily Interiors',
            'active' => 'projects',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Display a specific project details page matching Screen 8 of mobile UI and Desktop reference.
     */
    public function show(array $params): Response
    {
        $slug = $params['slug'] ?? '';
        $project = Project::findBySlug($slug);

        if (!$project) {
            throw new HttpException(404, 'Project not found');
        }

        $all = Project::all();
        $related = array_values(array_filter($all, fn($p) => $p['slug'] !== $slug));
        $related = array_slice($related, 0, 3);

        $content = View::make('pages.project-detail', [
            'project' => $project,
            'related' => $related,
        ])->render();

        $html = View::renderPartial('layouts.app', [
            'title' => $project['title'] . ' (' . $project['location'] . ') — Lily Interiors',
            'active' => 'projects',
            'content' => $content,
        ]);

        return new Response($html);
    }
}
