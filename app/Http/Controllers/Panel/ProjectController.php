<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Member;
use App\Models\Project;
use App\Services\PublicFileStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private PublicFileStore $files) {}

    /**
     * List projects; each row shows whether the user may edit it.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Project::class);

        return view('panel.projects.index', [
            'projects' => Project::query()->with('members')->orderBy('sort_order')->get(),
            'myMemberId' => $request->user()->member?->id,
        ]);
    }

    /**
     * Show the form for a new project.
     */
    public function create(): View
    {
        Gate::authorize('create', Project::class);

        return view('panel.projects.form', [
            'project' => new Project(['year' => now()->year, 'categories' => []]),
            'members' => Member::query()->orderBy('sort_order')->get(),
        ]);
    }

    /**
     * Store a new project; a member who adds it joins the team automatically.
     */
    public function store(ProjectRequest $request): RedirectResponse
    {
        Gate::authorize('create', Project::class);

        $project = DB::transaction(function () use ($request): Project {
            $project = Project::create([
                ...$request->project(),
                'slug' => $this->uniqueSlug($request->string('title')->toString()),
                'sort_order' => $request->filled('sort_order') ? $request->integer('sort_order') : (int) Project::max('sort_order') + 1,
                'cover_image' => $this->files->replace(null, $request->file('cover_image'), false, 'projects'),
            ]);

            $project->members()->sync($this->teamWithAuthor($request));

            return $project;
        });

        return redirect()->route('panel.projects.index')->with('status', "Proyek {$project->title} ditambahkan.");
    }

    /**
     * Show the form for editing a project.
     */
    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('panel.projects.form', [
            'project' => $project->load('members'),
            'members' => Member::query()->orderBy('sort_order')->get(),
        ]);
    }

    /**
     * Update a project and its team.
     */
    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        DB::transaction(function () use ($request, $project): void {
            $project->update([
                ...$request->project(),
                'cover_image' => $this->files->replace($project->cover_image, $request->file('cover_image'), $request->boolean('remove_cover_image'), 'projects'),
            ]);

            $project->members()->sync($this->teamWithAuthor($request));
        });

        return redirect()->route('panel.projects.index')->with('status', "Proyek {$project->title} disimpan.");
    }

    /**
     * Delete a project and its cover image.
     */
    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $project->delete();
        $this->files->delete($project->cover_image);

        return redirect()->route('panel.projects.index')->with('status', "Proyek {$project->title} dihapus.");
    }

    /**
     * Selected team, always including a non-admin editor so they keep access to the project.
     *
     * @return array<int, array{contribution: string|null}>
     */
    private function teamWithAuthor(ProjectRequest $request): array
    {
        $team = $request->team();
        $memberId = $request->user()->member?->id;

        if (! $request->user()->isAdmin() && $memberId !== null && ! array_key_exists($memberId, $team)) {
            $team[$memberId] = ['contribution' => null];
        }

        return $team;
    }

    /**
     * Slug from the title, with a numeric suffix when it is already taken.
     */
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'proyek';
        $slug = $base;
        $suffix = 2;

        while (Project::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
