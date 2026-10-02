<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * List published news.
     */
    public function index(): View
    {
        return view('posts.index', [
            'posts' => Post::query()->published()->with('author')->latest('published_at')->paginate(9),
        ]);
    }

    /**
     * Show one published post.
     */
    public function show(Post $post): View
    {
        abort_unless($post->isPublished(), 404);

        return view('posts.show', [
            'post' => $post->load('author.member'),
            'morePosts' => Post::query()->published()->whereKeyNot($post->getKey())->latest('published_at')->take(3)->get(),
        ]);
    }
}
