<x-layout :title="$project->title" :description="$project->summary">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:py-14">
        <a href="{{ route('home') }}#proyek" class="inline-flex items-center gap-2 text-sm font-medium text-ink-soft transition hover:text-brand">
            <x-icon name="arrow-left" class="size-4" />
            Kembali ke proyek
        </a>

        <header class="mt-8 max-w-3xl">
            <p class="text-sm font-medium text-muted">{{ $project->categories->map->label()->implode(' & ') }}, {{ $project->year }}</p>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-5xl">{{ $project->title }}</h1>
            <p class="mt-5 text-lg leading-relaxed text-ink-soft">{{ $project->summary }}</p>
        </header>

        <x-project-cover :project="$project" class="mt-10 rounded-3xl" />

        <div class="mt-12 grid gap-12 lg:grid-cols-[1fr_300px]">
            <div class="space-y-12">
                <section>
                    <h2 class="font-display text-xl font-semibold text-ink">Tentang proyek</h2>
                    <p class="mt-4 leading-relaxed text-ink-soft">{{ $project->description }}</p>
                </section>

                <section>
                    <h2 class="font-display text-xl font-semibold text-ink">Yang kami kerjakan</h2>
                    <ul class="mt-5 space-y-3">
                        @foreach ($project->highlights as $highlight)
                            <li class="flex gap-3 leading-relaxed text-ink-soft">
                                <x-icon name="chevron-right" class="mt-1 size-4 shrink-0 text-brand" />
                                {{ $highlight }}
                            </li>
                        @endforeach
                    </ul>
                </section>

                @if ($project->members->isNotEmpty())
                    <section>
                        <h2 class="font-display text-xl font-semibold text-ink">Tim proyek</h2>
                        <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                            @foreach ($project->members as $member)
                                <li>
                                    <a href="{{ route('members.show', $member) }}" class="group flex items-center gap-4 rounded-2xl border border-line bg-white p-4 transition hover:border-brand/40">
                                        <x-member-avatar :member="$member" class="size-12 shrink-0 rounded-xl text-base" />
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-ink group-hover:text-brand">{{ $member->name }}</p>
                                            <p class="truncate text-sm text-ink-soft">{{ $member->pivot->contribution ?? $member->role }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>

            <aside class="h-fit space-y-6 rounded-2xl border border-line bg-white p-6">
                @if ($project->client)
                    <div>
                        <h2 class="text-xs font-semibold tracking-wider text-muted uppercase">Klien</h2>
                        <p class="mt-1 font-medium text-ink">{{ $project->client }}</p>
                    </div>
                @endif
                <div>
                    <h2 class="text-xs font-semibold tracking-wider text-muted uppercase">Tahun</h2>
                    <p class="mt-1 font-medium text-ink">{{ $project->year }}</p>
                </div>
                <div>
                    <h2 class="text-xs font-semibold tracking-wider text-muted uppercase">Teknologi</h2>
                    <ul class="mt-2 flex flex-wrap gap-1.5">
                        @foreach ($project->tech_stack as $tech)
                            <li class="rounded-md bg-canvas px-2.5 py-1 text-sm text-ink">{{ $tech }}</li>
                        @endforeach
                    </ul>
                </div>
                @if ($project->url)
                    <a href="{{ $project->url }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-deep">
                        Kunjungi situs
                        <x-icon name="arrow-up-right" class="size-4" />
                    </a>
                @endif
            </aside>
        </div>

        @if ($nextProject)
            <a href="{{ route('projects.show', $nextProject) }}" class="group mt-16 flex items-center justify-between gap-6 rounded-2xl bg-night p-6 text-white sm:p-8">
                <div>
                    <p class="text-sm text-white/50">Proyek berikutnya</p>
                    <p class="mt-1 font-display text-xl font-semibold">{{ $nextProject->title }}</p>
                </div>
                <x-icon name="arrow-right" class="size-6 shrink-0 transition group-hover:translate-x-1" />
            </a>
        @endif
    </div>
</x-layout>
