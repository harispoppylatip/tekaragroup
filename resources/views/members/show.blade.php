<x-layout :title="'CV '.$member->name" :description="$member->name.', '.$member->role.' di Tekara. '.$member->headline">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:py-14">
        <div class="no-print flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('home') }}#tim" class="inline-flex items-center gap-2 text-sm font-medium text-ink-soft transition hover:text-brand">
                <x-icon name="arrow-left" class="size-4" />
                Kembali ke tim
            </a>

            <div class="flex flex-wrap gap-2">
                <button type="button" data-print class="inline-flex items-center gap-2 rounded-lg border border-line bg-white px-4 py-2 text-sm font-semibold text-ink transition hover:border-ink">
                    <x-icon name="printer" class="size-4" />
                    Cetak atau simpan PDF
                </button>
                @if ($member->cv_file)
                    <a href="{{ asset('storage/'.$member->cv_file) }}" download class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-deep">
                        <x-icon name="download" class="size-4" />
                        Unduh CV
                    </a>
                @endif
            </div>
        </div>

        <article class="print-flat mt-6 overflow-hidden rounded-3xl border border-line bg-white shadow-sm">
            {{-- Kepala CV --}}
            <header class="relative overflow-hidden bg-night px-6 py-10 text-white sm:px-10">
                <x-logo-mark tone="light" class="pointer-events-none absolute -right-8 -bottom-14 w-64 opacity-[0.07]" />
                <div class="relative flex flex-col gap-8 sm:flex-row sm:items-center">
                    <x-member-avatar :member="$member" class="size-28 shrink-0 rounded-2xl text-4xl ring-4 ring-white/10 sm:size-32" />
                    <div>
                        <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $member->name }}</h1>
                        <p class="mt-2 text-lg font-medium text-brand-sky">{{ $member->role }}</p>
                        <p class="mt-3 max-w-xl leading-relaxed text-white/70">{{ $member->headline }}</p>
                    </div>
                </div>

                <ul class="relative mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/80">
                    @if ($member->location)
                        <li class="flex items-center gap-2"><x-icon name="map-pin" class="size-4 text-white/50" />{{ $member->location }}</li>
                    @endif
                    @if ($member->email)
                        <li><a href="mailto:{{ $member->email }}" class="flex items-center gap-2 hover:text-white"><x-icon name="mail" class="size-4 text-white/50" />{{ $member->email }}</a></li>
                    @endif
                    @if ($member->phone)
                        <li class="flex items-center gap-2"><x-icon name="phone" class="size-4 text-white/50" />{{ $member->phone }}</li>
                    @endif
                    @foreach ($member->links ?? [] as $link)
                        <li><a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-white"><x-icon name="link" class="size-4 text-white/50" />{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </header>

            <div class="grid gap-12 px-6 py-10 sm:px-10 lg:grid-cols-[1fr_280px]">
                <div class="space-y-12">
                    <section>
                        <h2 class="font-display text-lg font-semibold text-ink">Tentang</h2>
                        <p class="mt-4 leading-relaxed text-ink-soft">{{ $member->summary }}</p>
                    </section>

                    <section>
                        <h2 class="flex items-center gap-2 font-display text-lg font-semibold text-ink">
                            <x-icon name="briefcase" class="size-5 text-brand" />
                            Pengalaman
                        </h2>
                        <ol class="mt-6 space-y-8">
                            @foreach ($member->experiences as $experience)
                                <li class="grid gap-1 sm:grid-cols-[130px_1fr] sm:gap-6">
                                    <p class="text-sm text-muted">{{ $experience['period'] }}</p>
                                    <div>
                                        <h3 class="font-semibold text-ink">{{ $experience['title'] }}</h3>
                                        <p class="text-sm font-medium text-brand">{{ $experience['place'] }}</p>
                                        <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ $experience['description'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </section>

                    @if ($member->projects->isNotEmpty())
                        <section>
                            <h2 class="font-display text-lg font-semibold text-ink">Proyek di Tekara</h2>
                            <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                                @foreach ($member->projects as $project)
                                    <li>
                                        <a href="{{ route('projects.show', $project) }}" class="group block h-full rounded-xl border border-line p-4 transition hover:border-brand/40 hover:bg-brand-tint/40">
                                            <p class="flex items-start justify-between gap-3 font-semibold text-ink">
                                                {{ $project->title }}
                                                <x-icon name="arrow-up-right" class="no-print size-4 shrink-0 text-muted transition group-hover:text-brand" />
                                            </p>
                                            <p class="mt-1 text-sm text-ink-soft">{{ $project->pivot->contribution }}</p>
                                            <p class="mt-2 text-xs text-muted">{{ $project->categories->map->label()->implode(' & ') }}, {{ $project->year }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </div>

                <aside class="space-y-10">
                    <section>
                        <h2 class="font-display text-lg font-semibold text-ink">Keahlian</h2>
                        <div class="mt-5 space-y-5">
                            @foreach ($member->skills as $skillGroup)
                                <div>
                                    <h3 class="text-xs font-semibold tracking-wider text-muted uppercase">{{ $skillGroup['group'] }}</h3>
                                    <ul class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach ($skillGroup['items'] as $skill)
                                            <li class="rounded-md bg-canvas px-2.5 py-1 text-sm text-ink">{{ $skill }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section>
                        <h2 class="flex items-center gap-2 font-display text-lg font-semibold text-ink">
                            <x-icon name="academic" class="size-5 text-brand" />
                            Pendidikan
                        </h2>
                        <ul class="mt-5 space-y-4">
                            @foreach ($member->educations as $education)
                                <li>
                                    <p class="font-semibold text-ink">{{ $education['school'] }}</p>
                                    <p class="text-sm text-ink-soft">{{ $education['major'] }}</p>
                                    <p class="text-xs text-muted">{{ $education['period'] }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    @if (! empty($member->certifications))
                        <section>
                            <h2 class="font-display text-lg font-semibold text-ink">Sertifikasi</h2>
                            <ul class="mt-5 space-y-4">
                                @foreach ($member->certifications as $certification)
                                    <li>
                                        <p class="font-semibold text-ink">{{ $certification['name'] }}</p>
                                        <p class="text-sm text-ink-soft">{{ $certification['issuer'] }}, {{ $certification['year'] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </aside>
            </div>
        </article>

        @if ($otherMembers->isNotEmpty())
            <section class="no-print mt-16">
                <h2 class="font-display text-lg font-semibold text-ink">Anggota lainnya</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    @foreach ($otherMembers as $otherMember)
                        <a href="{{ route('members.show', $otherMember) }}" class="group flex items-center gap-4 rounded-2xl border border-line bg-white p-4 transition hover:border-brand/40">
                            <x-member-avatar :member="$otherMember" class="size-14 shrink-0 rounded-xl text-lg" />
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-ink group-hover:text-brand">{{ $otherMember->name }}</p>
                                <p class="truncate text-sm text-ink-soft">{{ $otherMember->role }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layout>
