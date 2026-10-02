<x-layout>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-white">
        <div
            class="mx-auto grid max-w-6xl items-center gap-14 px-4 pt-16 pb-20 sm:px-6 lg:grid-cols-[1.1fr_1fr] lg:pt-24 lg:pb-28">
            <div>
                <p class="text-xs font-semibold tracking-[0.3em] text-muted uppercase">{{ $site['site_tagline'] }}</p>
                <h1
                    class="mt-6 font-display text-4xl leading-[1.1] font-bold tracking-tight text-ink sm:text-5xl lg:text-6xl">
                    {{ $site['hero_title'] }}
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-ink-soft">
                    {{ $site['hero_text'] }}
                </p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="#proyek"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand/25 transition hover:bg-brand-deep">
                        Lihat hasil proyek
                        <x-icon name="arrow-right" class="size-4" />
                    </a>
                    <a href="#tim"
                        class="inline-flex items-center gap-2 rounded-lg border border-line px-5 py-3 text-sm font-semibold text-ink transition hover:border-ink">
                        Kenali tim kami
                    </a>
                </div>

                <dl class="mt-14 grid max-w-md grid-cols-3 gap-6">
                    <div>
                        <dt class="text-xs text-muted">Proyek selesai</dt>
                        <dd class="mt-1 font-display text-xl font-semibold whitespace-nowrap text-ink sm:text-2xl">
                            {{ $projects->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-muted">Anggota tim</dt>
                        <dd class="mt-1 font-display text-xl font-semibold whitespace-nowrap text-ink sm:text-2xl">
                            {{ $members->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-muted">Spesialisasi</dt>
                        <dd class="mt-1 font-display text-xl font-semibold whitespace-nowrap text-ink sm:text-2xl">Web &
                            IoT</dd>
                    </div>
                </dl>
            </div>

            <div class="relative mx-auto w-full max-w-md lg:max-w-none" aria-hidden="true">
                <div class="relative aspect-square rounded-[2rem] bg-canvas">
                    <x-logo-mark class="absolute top-[54%] left-1/2 w-1/2 -translate-x-1/2 -translate-y-1/2" />

                    <div
                        class="absolute top-[5%] -left-8 hidden w-52 rounded-xl border border-line bg-white p-4 shadow-xl shadow-ink/5 sm:block">
                        <div class="flex items-center gap-3">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-ink text-white"><x-icon
                                    name="globe" class="size-5" /></span>
                            <div>
                                <p class="text-sm font-semibold text-ink">Website</p>
                                <p class="text-xs text-muted">Dashboard dan laporan</p>
                            </div>
                        </div>
                        <div class="mt-4 flex h-12 items-end gap-1.5">
                            @foreach ([40, 65, 50, 80, 60, 92, 74] as $height)
                                <div class="flex-1 rounded-t-sm bg-brand/80" style="height: {{ $height }}%"></div>
                            @endforeach
                        </div>
                    </div>

                    <div
                        class="absolute bottom-[8%] -right-8 hidden w-56 rounded-xl border border-line bg-white p-4 shadow-xl shadow-ink/5 sm:block">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex size-9 items-center justify-center rounded-lg bg-linear-to-br from-brand-deep to-brand-sky text-white"><x-icon
                                    name="chip" class="size-5" /></span>
                            <div>
                                <p class="text-sm font-semibold text-ink">Perangkat IoT</p>
                                <p class="text-xs text-muted">ESP32 di gerbang sekolah</p>
                            </div>
                        </div>
                        <div class="mt-4 rounded-lg bg-night px-3 py-2 font-mono text-xs leading-relaxed text-white/90">
                            <p>Tempelkan jari</p>
                            <p class="text-brand-sky">Hadir 07:02</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan --}}
    <section id="layanan" class="border-t border-line bg-canvas py-24">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-sm font-medium text-muted">Layanan</p>
                <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Spesialis website
                    dan IoT, siap untuk kebutuhan lainnya.</h2>
                <p class="mt-4 text-lg leading-relaxed text-ink-soft">Dua keahlian utama kami saling melengkapi. Alat di
                    lapangan mengirim data, website mengolahnya menjadi informasi yang bisa langsung dipakai.</p>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-2">
                @php
                    $specialties = [
                        [
                            'icon' => 'globe',
                            'title' => 'Website dan aplikasi web',
                            'text' => 'Dari profil perusahaan sampai sistem informasi dengan banyak peran pengguna.',
                            'items' => [
                                'Company profile dan website sekolah',
                                'Sistem informasi dan dashboard',
                                'Panel admin untuk mengelola konten',
                                'Integrasi API dan layanan pihak ketiga',
                            ],
                            'dark' => true,
                        ],
                        [
                            'icon' => 'chip',
                            'title' => 'Perangkat IoT',
                            'text' => 'Alat yang kami rakit dan program sendiri, lalu kami hubungkan ke website.',
                            'items' => [
                                'Rangkaian dan firmware ESP32',
                                'Sensor, monitoring, dan pencatat data',
                                'Alat presensi sidik jari',
                                'Koneksi alat ke web lewat API atau MQTT',
                            ],
                            'dark' => false,
                        ],
                    ];
                @endphp

                @foreach ($specialties as $specialty)
                    <div @class([
                        'relative overflow-hidden rounded-2xl p-8 sm:p-10',
                        'bg-night text-white' => $specialty['dark'],
                        'bg-linear-to-br from-brand-deep via-brand to-brand-sky text-white' => !$specialty[
                            'dark'
                        ],
                    ])>
                        <svg class="absolute -right-16 -bottom-20 h-64 text-white/[0.06]" viewBox="0 0 220 220"
                            fill="currentColor" aria-hidden="true">
                            <path
                                d="M30 0 H108 Q120 0 128 8 L210 93 Q216 100 210 107 L130 200 Q116 216 98 216 H0 L111 108 Z" />
                        </svg>
                        <span class="relative flex size-12 items-center justify-center rounded-xl bg-white/10">
                            <x-icon :name="$specialty['icon']" class="size-6" />
                        </span>
                        <h3 class="relative mt-6 font-display text-2xl font-semibold">{{ $specialty['title'] }}</h3>
                        <p class="relative mt-3 max-w-md leading-relaxed text-white/75">{{ $specialty['text'] }}</p>
                        <ul class="relative mt-8 grid gap-3 sm:grid-cols-2">
                            @foreach ($specialty['items'] as $item)
                                <li class="flex gap-2 text-sm text-white/90">
                                    <x-icon name="chevron-right" class="mt-0.5 size-4 shrink-0 text-white/50" />
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl border border-line bg-white p-8">
                <h3 class="font-display text-lg font-semibold text-ink">Di luar spesialisasi, kami juga mengerjakan</h3>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([['icon' => 'swatch', 'title' => 'Desain UI/UX', 'text' => 'Alur dan tampilan yang mudah dipakai semua umur.'], ['icon' => 'database', 'title' => 'Desain database', 'text' => 'Struktur data rapi yang siap berkembang.'], ['icon' => 'arrows', 'title' => 'Integrasi sistem', 'text' => 'Menghubungkan aplikasi, perangkat, dan layanan lain.'], ['icon' => 'wrench', 'title' => 'Perawatan', 'text' => 'Pendampingan dan perbaikan setelah sistem berjalan.']] as $service)
                        <div>
                            <x-icon :name="$service['icon']" class="size-6 text-brand" />
                            <p class="mt-3 font-semibold text-ink">{{ $service['title'] }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-ink-soft">{{ $service['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Proses --}}
    <section class="bg-night py-24 text-white">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-sm font-medium text-white/50">Cara kami bekerja</p>
                <h2 class="mt-3 font-display text-3xl font-bold tracking-tight sm:text-4xl">Dari ide menjadi karya,
                    dalam empat langkah.</h2>
            </div>

            <ol class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([['title' => 'Dengar kebutuhan', 'text' => 'Kami memahami masalah dan siapa yang akan memakai sistemnya.'], ['title' => 'Rancang', 'text' => 'Alur, tampilan, database, dan rangkaian alat disepakati bersama.'], ['title' => 'Bangun dan uji', 'text' => 'Dikerjakan bertahap dan diuji di kondisi nyata, bukan hanya di laptop.'], ['title' => 'Serahkan dan dampingi', 'text' => 'Sistem dipakai, kami tetap ada untuk perbaikan dan pengembangan.']] as $step)
                    <li class="rounded-2xl border border-white/10 bg-night-soft p-6">
                        <span
                            class="font-display text-sm font-semibold text-brand-sky">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-4 font-display text-lg font-semibold">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/65">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Proyek --}}
    <section id="proyek" class="bg-canvas py-24">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm font-medium text-muted">Hasil proyek</p>
                    <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Karya yang
                        sudah dipakai di dunia nyata.</h2>
                </div>

                @php
                    $filters = [
                        ['key' => 'semua', 'label' => 'Semua', 'count' => $projects->count()],
                        [
                            'key' => 'website',
                            'label' => 'Website',
                            'count' => $projects
                                ->filter(fn($project) => $project->categories->contains(\App\ProjectCategory::Website))
                                ->count(),
                        ],
                        [
                            'key' => 'iot',
                            'label' => 'IoT',
                            'count' => $projects
                                ->filter(fn($project) => $project->categories->contains(\App\ProjectCategory::Iot))
                                ->count(),
                        ],
                    ];
                @endphp

                <div class="flex gap-1 self-start rounded-xl border border-line bg-white p-1" role="group"
                    aria-label="Saring proyek">
                    @foreach ($filters as $filter)
                        <button type="button" data-filter="{{ $filter['key'] }}"
                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-ink-soft transition hover:text-ink aria-pressed:bg-ink aria-pressed:text-white">
                            {{ $filter['label'] }} <span class="ml-1 text-xs opacity-60">{{ $filter['count'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-project-grid>
                @forelse ($projects as $project)
                    <x-project-card :project="$project" />
                @empty
                    <p class="text-ink-soft">Proyek akan segera ditampilkan.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Tim --}}
    <section id="tim" class="border-t border-line bg-white py-24">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-sm font-medium text-muted">Tim</p>
                <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Orang-orang di
                    balik Tekara.</h2>
                {{-- <p class="mt-4 text-lg leading-relaxed text-ink-soft">Pilih kartu untuk melihat CV lengkap setiap anggota.</p> --}}
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($members as $member)
                    <x-member-card :member="$member" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Berita --}}
    <section id="berita" class="bg-canvas py-24">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm font-medium text-muted">Berita</p>
                    <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Kabar terbaru
                        dari tim.</h2>
                </div>

                <a href="{{ route('posts.index') }}"
                    class="inline-flex shrink-0 items-center gap-2 self-start rounded-lg border border-line bg-white px-4 py-2 text-sm font-semibold text-ink transition hover:border-ink">
                    Semua berita
                    <x-icon name="arrow-right" class="size-4" />
                </a>
            </div>

            @if ($latestPosts->isEmpty())
                <p class="mt-12 text-ink-soft">Belum ada berita yang terbit.</p>
            @else
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestPosts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Kontak --}}
    <section id="kontak" class="bg-canvas py-24">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="relative overflow-hidden rounded-3xl bg-ink px-6 py-14 text-white sm:px-14">
                <x-logo-mark tone="light"
                    class="pointer-events-none absolute -right-10 -bottom-16 w-80 opacity-10" />
                <div class="relative max-w-xl">
                    <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">
                        {{ $site['contact_title'] }}</h2>
                    <p class="mt-4 text-lg leading-relaxed text-white/70">{{ $site['contact_text'] }}</p>

                    <div class="mt-9 flex flex-wrap gap-3">
                        @if ($site['contact_whatsapp'])
                            <a href="https://wa.me/{{ $site['contact_whatsapp'] }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-ink transition hover:bg-brand-tint">
                                <x-icon name="chat" class="size-4" />
                                Chat WhatsApp
                            </a>
                        @endif
                        @if ($site['contact_email'])
                            <a href="mailto:{{ $site['contact_email'] }}"
                                class="inline-flex items-center gap-2 rounded-lg border border-white/25 px-5 py-3 text-sm font-semibold text-white transition hover:border-white">
                                <x-icon name="mail" class="size-4" />
                                {{ $site['contact_email'] }}
                            </a>
                        @endif
                        @if (!$site['contact_whatsapp'] && !$site['contact_email'])
                            <a href="#tim"
                                class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-ink transition hover:bg-brand-tint">
                                Hubungi lewat anggota tim
                                <x-icon name="arrow-right" class="size-4" />
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
