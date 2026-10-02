<x-panel-layout title="Ringkasan" description="Keadaan website dan pekerjaan Anda hari ini.">
    <x-slot:actions>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-secondary">Lihat website</a>
        <a href="{{ route('panel.posts.create') }}" class="btn-primary">Tulis berita</a>
    </x-slot:actions>

    @php
        $user = auth()->user();
    @endphp

    <section class="panel-card p-6">
        <h2 class="font-display text-xl font-semibold text-ink">Halo, {{ $user->name }}.</h2>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink-soft">
            @if ($user->isAdmin())
                Anda masuk sebagai admin. Semua bagian website bisa Anda kelola dari sini, mulai dari anggota, proyek,
                berita, sampai teks halaman depan.
            @else
                Anda masuk sebagai anggota. Dari sini Anda bisa memperbarui CV sendiri, menambah proyek, dan menulis
                berita.
            @endif
        </p>

        <dl class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([['label' => 'Anggota', 'value' => $counts['members']], ['label' => 'Proyek', 'value' => $counts['projects']], ['label' => 'Berita terbit', 'value' => $counts['posts']], ['label' => 'Draf berita', 'value' => $counts['drafts']]] as $stat)
                <div class="rounded-xl border border-line bg-canvas px-4 py-3">
                    <dt class="text-xs text-muted">{{ $stat['label'] }}</dt>
                    <dd class="mt-1 font-display text-2xl font-semibold text-ink">{{ $stat['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <section class="panel-card flex flex-col p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="font-display text-lg font-semibold text-ink">Proyek saya</h2>
                    <p class="mt-1 text-sm text-ink-soft">Lima proyek yang terakhir Anda perbarui.</p>
                </div>
                <a href="{{ route('panel.projects.index') }}"
                    class="text-sm font-semibold text-brand hover:text-brand-deep">Semua proyek</a>
            </div>

            @if ($member === null)
                <p class="mt-5 rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink-soft">
                    Akun ini belum terhubung dengan profil anggota, jadi belum ada CV atau daftar proyek pribadi.
                </p>
            @elseif ($myProjects->isEmpty())
                <p class="mt-5 rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink-soft">
                    Belum ada proyek yang tercatat. Tambahkan proyek pertama Anda.
                </p>
            @else
                <ul class="mt-5 divide-y divide-line">
                    @foreach ($myProjects as $project)
                        <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-ink">{{ $project->title }}</p>
                                <p class="mt-0.5 text-xs text-muted">{{ $project->year }} &middot;
                                    {{ $project->categories->map(fn($category) => $category->label())->join(', ') }}
                                </p>
                            </div>
                            <a href="{{ route('panel.projects.edit', $project) }}"
                                class="shrink-0 text-sm font-semibold text-brand hover:text-brand-deep">Ubah</a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-5">
                <a href="{{ route('panel.projects.create') }}" class="btn-secondary">Tambah proyek</a>
            </div>
        </section>

        <section class="panel-card flex flex-col p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="font-display text-lg font-semibold text-ink">Berita terbaru</h2>
                    <p class="mt-1 text-sm text-ink-soft">Termasuk yang masih berupa draf.</p>
                </div>
                <a href="{{ route('panel.posts.index') }}"
                    class="text-sm font-semibold text-brand hover:text-brand-deep">Semua berita</a>
            </div>

            @if ($latestPosts->isEmpty())
                <p class="mt-5 rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink-soft">
                    Belum ada berita. Tulis kabar pertama dari tim.
                </p>
            @else
                <ul class="mt-5 divide-y divide-line">
                    @foreach ($latestPosts as $post)
                        <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-ink">{{ $post->title }}</p>
                                <p class="mt-0.5 text-xs text-muted">
                                    {{ $post->author?->name ?? 'Tanpa penulis' }} &middot;
                                    {{ $post->isPublished() ? 'Terbit ' . $post->published_at->translatedFormat('j F Y') : 'Draf' }}
                                </p>
                            </div>
                            @if ($user->isAdmin() || $post->user_id === $user->id)
                                <a href="{{ route('panel.posts.edit', $post) }}"
                                    class="shrink-0 text-sm font-semibold text-brand hover:text-brand-deep">Ubah</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-5">
                <a href="{{ route('panel.posts.create') }}" class="btn-secondary">Tulis berita</a>
            </div>
        </section>
    </div>

    <section class="panel-card mt-6 p-6">
        <h2 class="font-display text-lg font-semibold text-ink">Pintasan</h2>
        <p class="mt-1 text-sm text-ink-soft">Bagian yang paling sering diubah.</p>

        <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (array_filter([['label' => 'Perbarui profil saya', 'route' => 'panel.profile.edit'], $member ? ['label' => 'Perbarui CV saya', 'route' => 'panel.cv.edit'] : null, ['label' => 'Ganti sandi', 'route' => 'panel.password.edit'], $user->isAdmin() ? ['label' => 'Kelola anggota', 'route' => 'panel.members.index'] : null, $user->isAdmin() ? ['label' => 'Tambah anggota', 'route' => 'panel.members.create'] : null, $user->isAdmin() ? ['label' => 'Pengaturan website', 'route' => 'panel.settings.edit'] : null]) as $shortcut)
                <a href="{{ route($shortcut['route']) }}"
                    class="rounded-xl border border-line bg-canvas px-4 py-3 text-sm font-medium text-ink transition hover:border-ink">{{ $shortcut['label'] }}</a>
            @endforeach
        </div>
    </section>
</x-panel-layout>
