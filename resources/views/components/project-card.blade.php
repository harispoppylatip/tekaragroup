@props(['project'])

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-line bg-white transition duration-300 hover:-translate-y-1 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5" data-project data-categories="{{ $project->categoryKeys() }}">
    <x-project-cover :project="$project" />

    <div class="flex flex-1 flex-col p-6">
        <div class="flex items-center justify-between text-xs font-medium text-muted">
            <span>{{ $project->categories->map->label()->implode(' & ') }}</span>
            <span>{{ $project->year }}</span>
        </div>

        <h3 class="mt-3 font-display text-lg font-semibold text-ink">
            <a href="{{ route('projects.show', $project) }}" class="after:absolute after:inset-0">{{ $project->title }}</a>
        </h3>
        <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ $project->summary }}</p>

        <ul class="mt-5 flex flex-wrap gap-1.5" aria-label="Teknologi">
            @foreach (array_slice($project->tech_stack, 0, 4) as $tech)
                <li class="rounded-md bg-canvas px-2 py-1 text-xs text-ink-soft">{{ $tech }}</li>
            @endforeach
        </ul>

        <span class="mt-auto flex items-center gap-1.5 pt-6 text-sm font-semibold text-brand">
            Lihat detail
            <x-icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" />
        </span>
    </div>
</article>
