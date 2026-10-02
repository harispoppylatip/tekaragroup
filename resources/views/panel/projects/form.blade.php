@php
    $isAdmin = auth()->user()->isAdmin();

    $selectedCategories = old('categories', $project->categories->map(fn($category) => $category->value)->all());
    $teamSelection =
        old('team') ??
        $project->members
            ->mapWithKeys(
                fn($member) => [
                    $member->id => ['selected' => true, 'contribution' => $member->pivot->contribution],
                ],
            )
            ->all();
@endphp

<x-panel-layout :title="$project->exists ? 'Ubah proyek' : 'Tambah proyek'" description="Isian ini tampil di halaman proyek pada website.">
    <form method="POST"
        action="{{ $project->exists ? route('panel.projects.update', $project) : route('panel.projects.store') }}"
        enctype="multipart/form-data">
        @csrf
        @if ($project->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Keterangan utama</h2>
                <p class="mt-1 text-sm text-ink-soft">Judul dan ringkasan adalah yang pertama dibaca pengunjung.</p>

                <div class="mt-6">
                    <label class="form-label" for="title">Judul proyek</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $project->title) }}"
                        class="form-input" placeholder="Sistem presensi sidik jari sekolah" required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label class="form-label" for="summary">Ringkasan singkat</label>
                    <input id="summary" name="summary" type="text" value="{{ old('summary', $project->summary) }}"
                        class="form-input" placeholder="Presensi siswa dengan sensor sidik jari dan dashboard guru."
                        required>
                    <p class="form-hint">Satu kalimat, tampil di kartu proyek.</p>
                    @error('summary')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="6" class="form-input"
                        placeholder="Latar belakang, cara kerja, dan hasil yang dicapai." required>{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <span class="form-label">Kategori</span>
                    <div class="mt-2 flex flex-wrap gap-6">
                        @foreach (\App\ProjectCategory::cases() as $category)
                            <label class="flex items-center gap-2 text-sm text-ink">
                                <input type="checkbox" name="categories[]" value="{{ $category->value }}"
                                    @checked(in_array($category->value, $selectedCategories, true))
                                    class="size-4 rounded border-line text-brand focus:ring-brand">
                                {{ $category->label() }}
                            </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Rincian</h2>
                <p class="mt-1 text-sm text-ink-soft">Semua isian di bagian ini opsional.</p>

                <div class="mt-6 grid gap-5 sm:grid-cols-3">
                    <div>
                        <label class="form-label" for="client">Klien atau pengguna</label>
                        <input id="client" name="client" type="text"
                            value="{{ old('client', $project->client) }}" class="form-input"
                            placeholder="SMK Negeri 1">
                        @error('client')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="year">Tahun</label>
                        <input id="year" name="year" type="number" min="2000" max="{{ now()->year + 1 }}"
                            value="{{ old('year', $project->year) }}" class="form-input" required>
                        @error('year')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="url">Tautan proyek</label>
                        <input id="url" name="url" type="text" value="{{ old('url', $project->url) }}"
                            class="form-input" placeholder="https://contoh.com">
                        @error('url')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label class="form-label" for="tech_stack">Teknologi</label>
                    <input id="tech_stack" name="tech_stack" type="text"
                        value="{{ old('tech_stack', implode(', ', $project->tech_stack ?? [])) }}" class="form-input"
                        placeholder="Laravel, Tailwind, ESP32">
                    <p class="form-hint">Pisahkan setiap teknologi dengan koma.</p>
                    @error('tech_stack')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label class="form-label" for="highlights">Poin penting</label>
                    <textarea id="highlights" name="highlights" rows="4" class="form-input"
                        placeholder="Absen terbuka otomatis sesuai jadwal&#10;Rekap kehadiran bisa diunduh">{{ old('highlights', implode("\n", $project->highlights ?? [])) }}</textarea>
                    <p class="form-hint">Tulis satu poin per baris.</p>
                    @error('highlights')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Gambar sampul</h2>
                <p class="mt-1 text-sm text-ink-soft">JPG, PNG, atau WebP, maksimal 4 MB.</p>

                @if ($project->cover_image)
                    <div class="mt-5 flex flex-wrap items-center gap-4">
                        <img src="{{ asset('storage/' . $project->cover_image) }}" alt="Sampul {{ $project->title }}"
                            class="h-24 w-40 rounded-xl object-cover">
                        <label class="flex items-center gap-2 text-sm text-ink-soft">
                            <input type="checkbox" name="remove_cover_image" value="1"
                                @checked(old('remove_cover_image'))
                                class="size-4 rounded border-line text-brand focus:ring-brand">
                            Hapus gambar sampul
                        </label>
                    </div>
                @endif

                <div class="mt-5">
                    <input id="cover_image" name="cover_image" type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="form-input file:mr-3 file:rounded-md file:border-0 file:bg-canvas file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-ink">
                    @error('cover_image')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Tim</h2>
                <p class="mt-1 text-sm text-ink-soft">Pilih anggota yang mengerjakan proyek ini dan tulis perannya.</p>

                @unless ($isAdmin)
                    <p class="mt-4 rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink-soft">
                        Sebagai anggota, Anda otomatis tercatat di tim proyek ini.
                    </p>
                @endunless

                <div class="mt-5 space-y-3">
                    @foreach ($members as $member)
                        @php
                            $row = $teamSelection[$member->id] ?? [];
                        @endphp

                        <div
                            class="grid items-center gap-4 rounded-xl border border-line bg-canvas p-4 sm:grid-cols-[1fr_1.2fr]">
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="team[{{ $member->id }}][selected]" value="1"
                                    @checked(!empty($row['selected']))
                                    class="mt-0.5 size-4 rounded border-line text-brand focus:ring-brand">
                                <span>
                                    <span class="block text-sm font-medium text-ink">{{ $member->name }}</span>
                                    <span class="block text-xs text-muted">{{ $member->role }}</span>
                                </span>
                            </label>

                            <div>
                                <label class="sr-only" for="team-contribution-{{ $member->id }}">Peran
                                    {{ $member->name }} di proyek ini</label>
                                <input id="team-contribution-{{ $member->id }}"
                                    name="team[{{ $member->id }}][contribution]" type="text"
                                    value="{{ $row['contribution'] ?? '' }}" class="form-input mt-0"
                                    placeholder="Peran di proyek ini">
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            @if ($isAdmin)
                <section class="panel-card p-6">
                    <h2 class="font-display text-lg font-semibold text-ink">Pengaturan tampil</h2>
                    <p class="mt-1 text-sm text-ink-soft">Khusus admin.</p>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <label class="flex items-start gap-3 rounded-xl border border-line bg-canvas p-4">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))
                                class="mt-0.5 size-4 rounded border-line text-brand focus:ring-brand">
                            <span>
                                <span class="block text-sm font-medium text-ink">Jadikan proyek unggulan</span>
                                <span class="mt-0.5 block text-xs text-muted">Tampil lebih dulu di halaman
                                    depan.</span>
                            </span>
                        </label>

                        <div>
                            <label class="form-label" for="sort_order">Urutan tampil</label>
                            <input id="sort_order" name="sort_order" type="number" min="0" max="999"
                                value="{{ old('sort_order', $project->sort_order) }}" class="form-input">
                            <p class="form-hint">Angka lebih kecil tampil lebih dulu.</p>
                            @error('sort_order')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
            @endif
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button type="submit"
                class="btn-primary">{{ $project->exists ? 'Simpan perubahan' : 'Tambah proyek' }}</button>
            <a href="{{ route('panel.projects.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</x-panel-layout>
