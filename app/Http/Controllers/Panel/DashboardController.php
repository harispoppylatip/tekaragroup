<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the panel home with counts and shortcuts.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('panel.dashboard', [
            'member' => $user->member,
            'counts' => [
                'members' => Member::count(),
                'projects' => Project::count(),
                'posts' => Post::query()->published()->count(),
                'drafts' => Post::query()->whereNull('published_at')->count(),
            ],
            'myProjects' => $user->member?->projects()->latest('projects.updated_at')->take(5)->get() ?? collect(),
            'latestPosts' => Post::query()->with('author')->latest()->take(5)->get(),
        ]);
    }
}
