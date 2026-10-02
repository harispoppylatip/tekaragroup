<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the landing page with projects, team, and latest news.
     */
    public function __invoke(): View
    {
        return view('home', [
            'projects' => Project::query()->orderBy('sort_order')->get(),
            'members' => Member::query()->orderBy('sort_order')->get(),
            'latestPosts' => Post::query()->published()->with('author')->latest('published_at')->take(3)->get(),
        ]);
    }
}
