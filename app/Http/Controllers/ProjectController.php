<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Show a single project.
     */
    public function show(Project $project): View
    {
        $project->load('members');

        return view('projects.show', [
            'project' => $project,
            'nextProject' => Project::query()
                ->where('sort_order', '>', $project->sort_order)
                ->orderBy('sort_order')
                ->first() ?? Project::query()->orderBy('sort_order')->whereKeyNot($project->getKey())->first(),
        ]);
    }
}
