<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\PublicFileStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private PublicFileStore $files) {}

    /**
     * List news posts, newest first.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Post::class);

        return view('panel.posts.index', [
            'posts' => Post::query()->with('author')->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for a new post.
     */
    public function create(): View
    {
        Gate::authorize('create', Post::class);

        return view('panel.posts.form', ['post' => new Post]);
    }

    /**
     * Store a new post written by the signed-in user.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $post = $request->user()->posts()->create([
            ...$request->safe()->only(['title', 'excerpt', 'body']),
            'slug' => $this->uniqueSlug($request->string('title')->toString()),
            'cover_image' => $this->files->replace(null, $request->file('cover_image'), false, 'posts'),
            'published_at' => $request->boolean('publish') ? now() : null,
        ]);

        return redirect()->route('panel.posts.index')->with('status', $post->isPublished()
            ? "Berita \"{$post->title}\" sudah terbit."
            : "Berita \"{$post->title}\" disimpan sebagai draf.");
    }

    /**
     * Show the form for editing a post.
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('panel.posts.form', ['post' => $post]);
    }

    /**
     * Update a post; the publish date is kept once set.
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $post->update([
            ...$request->safe()->only(['title', 'excerpt', 'body']),
            'cover_image' => $this->files->replace($post->cover_image, $request->file('cover_image'), $request->boolean('remove_cover_image'), 'posts'),
            'published_at' => $request->boolean('publish') ? ($post->published_at ?? now()) : null,
        ]);

        return redirect()->route('panel.posts.index')->with('status', "Berita \"{$post->title}\" disimpan.");
    }

    /**
     * Delete a post and its cover image.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();
        $this->files->delete($post->cover_image);

        return redirect()->route('panel.posts.index')->with('status', "Berita \"{$post->title}\" dihapus.");
    }

    /**
     * Slug from the title, with a numeric suffix when it is already taken.
     */
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'berita';
        $slug = $base;
        $suffix = 2;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
