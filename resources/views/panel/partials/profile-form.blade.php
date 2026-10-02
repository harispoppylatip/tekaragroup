{{--
    Isian profil anggota, dipakai bersama oleh panel/cv.blade.php (anggota
    mengubah CV sendiri) dan panel/members/form.blade.php (admin mengubah atau
    menambah anggota). Variabel: $member (Member), $isAdminForm (bool).

    Baris dinamis memakai atribut data-repeater yang diproses resources/js/app.js.
--}}
@php
    $isAdminForm = $isAdminForm ?? false;
    $isSelf = $member->exists && $member->user_id !== null && $member->user_id === auth()->id();

    $links = old('links', $member->links ?? []);
    $skills = old('skills', $member->skills ?? []);
    $experiences = old('experiences', $member->experiences ?? []);
    $educations = old('educations', $member->educations ?? []);
    $certifications = old('certifications', $member->certifications ?? []);
@endphp

<div class="space-y-6">
    {{-- Identitas --}}
    <section class="panel-card p-6">
        <h2 class="font-display text-lg font-semibold text-ink">Identitas</h2>
        <p class="mt-1 text-sm text-ink-soft">Nama dan peran ini tampil di halaman tim dan CV Anda.</p>

        <div class="mt-6 grid gap-5 sm:grid-cols-2">
            <div>
                <label class="form-label" for="name">Nama lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $member->name) }}"
                    class="form-input" required>
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label" for="role">Peran</label>
                <input id="role" name="role" type="text" value="{{ old('role', $member->role) }}"
                    class="form-input" placeholder="Web Developer" required>
                @error('role')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-5">
            <label class="form-label" for="headline">Kalimat singkat</label>
            <input id="headline" name="headline" type="text" value="{{ old('headline', $member->headline) }}"
                class="form-input" placeholder="Merancang sistem dari sensor sampai dashboard." required>
            @error('headline')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-5">
            <label class="form-label" for="summary">Tentang</label>
            <textarea id="summary" name="summary" rows="5" class="form-input" required>{{ old('summary', $member->summary) }}</textarea>
            @error('summary')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-5">
            <label class="form-label" for="photo">Foto profil (JPG, PNG, atau WebP, maksimal 2 MB)</label>

            @if ($member->photo)
                <div class="mt-3 flex items-center gap-4">
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="Foto {{ $member->name }}"
                        class="size-16 rounded-xl object-cover">
                    <label class="flex items-center gap-2 text-sm text-ink-soft">
                        <input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo'))
                            class="size-4 rounded border-line text-brand focus:ring-brand">
                        Hapus foto
                    </label>
                </div>
            @endif

            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
                class="form-input file:mr-3 file:rounded-md file:border-0 file:bg-canvas file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-ink">
            @error('photo')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-5 grid gap-5 sm:grid-cols-3">
            <div>
                <label class="form-label" for="location">Lokasi</label>
                <input id="location" name="location" type="text" value="{{ old('location', $member->location) }}"
                    class="form-input" placeholder="Samarinda, Kalimantan Timur">
                @error('location')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label" for="email">Surel</label>
                <input id="email" name="email" type="email" value="{{ old('email', $member->email) }}"
                    class="form-input" placeholder="nama@contoh.com">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label" for="phone">Telepon</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $member->phone) }}"
                    class="form-input" placeholder="0812...">
                @error('phone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Berkas --}}
    <section class="panel-card p-6">
        <h2 class="font-display text-lg font-semibold text-ink">Berkas</h2>
        <p class="mt-1 text-sm text-ink-soft">File CV hanya muncul sebagai tombol unduh.</p>

        <div class="mt-6">
            <div>
                <label class="form-label" for="cv_file">File CV (PDF, maksimal 5 MB)</label>

                @if ($member->cv_file)
                    <div class="mt-3 flex items-center gap-4">
                        <a href="{{ asset('storage/' . $member->cv_file) }}" target="_blank" rel="noopener"
                            class="text-sm font-medium text-brand hover:text-brand-deep">Lihat file saat ini</a>
                        <label class="flex items-center gap-2 text-sm text-ink-soft">
                            <input type="checkbox" name="remove_cv_file" value="1" @checked(old('remove_cv_file'))
                                class="size-4 rounded border-line text-brand focus:ring-brand">
                            Hapus file CV
                        </label>
                    </div>
                @endif

                <input id="cv_file" name="cv_file" type="file" accept="application/pdf"
                    class="form-input file:mr-3 file:rounded-md file:border-0 file:bg-canvas file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-ink">
                @error('cv_file')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Tautan --}}
    <section class="panel-card p-6" data-repeater>
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-display text-lg font-semibold text-ink">Tautan</h2>
                <p class="mt-1 text-sm text-ink-soft">Misalnya GitHub, LinkedIn, atau portofolio.</p>
            </div>
            <button type="button" data-repeater-add class="btn-secondary">Tambah tautan</button>
        </div>

        <div class="mt-5 space-y-4" data-repeater-list>
            @forelse ($links as $index => $row)
                @include('panel.partials.row-link', ['index' => $index, 'row' => (array) $row])
            @empty
                @include('panel.partials.row-link', ['index' => 0, 'row' => []])
            @endforelse
        </div>

        <template data-repeater-template>
            @include('panel.partials.row-link', ['index' => '__INDEX__', 'row' => []])
        </template>

        @error('links.*.label')
            <p class="form-error">{{ $message }}</p>
        @enderror
        @error('links.*.url')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </section>

    {{-- Keahlian --}}
    <section class="panel-card p-6" data-repeater>
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-display text-lg font-semibold text-ink">Keahlian</h2>
                <p class="mt-1 text-sm text-ink-soft">Kelompokkan keahlian agar mudah dibaca, misalnya Web dan IoT.</p>
            </div>
            <button type="button" data-repeater-add class="btn-secondary">Tambah kelompok</button>
        </div>

        <div class="mt-5 space-y-4" data-repeater-list>
            @forelse ($skills as $index => $row)
                @include('panel.partials.row-skill', ['index' => $index, 'row' => (array) $row])
            @empty
                @include('panel.partials.row-skill', ['index' => 0, 'row' => []])
            @endforelse
        </div>

        <template data-repeater-template>
            @include('panel.partials.row-skill', ['index' => '__INDEX__', 'row' => []])
        </template>

        @error('skills.*.group')
            <p class="form-error">{{ $message }}</p>
        @enderror
        @error('skills.*.items')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </section>

    {{-- Pengalaman --}}
    <section class="panel-card p-6" data-repeater>
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-display text-lg font-semibold text-ink">Pengalaman</h2>
                <p class="mt-1 text-sm text-ink-soft">Urutkan dari yang paling baru.</p>
            </div>
            <button type="button" data-repeater-add class="btn-secondary">Tambah pengalaman</button>
        </div>

        <div class="mt-5 space-y-4" data-repeater-list>
            @forelse ($experiences as $index => $row)
                @include('panel.partials.row-experience', ['index' => $index, 'row' => (array) $row])
            @empty
                @include('panel.partials.row-experience', ['index' => 0, 'row' => []])
            @endforelse
        </div>

        <template data-repeater-template>
            @include('panel.partials.row-experience', ['index' => '__INDEX__', 'row' => []])
        </template>

        @error('experiences.*.title')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </section>

    {{-- Pendidikan --}}
    <section class="panel-card p-6" data-repeater>
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-display text-lg font-semibold text-ink">Pendidikan</h2>
                <p class="mt-1 text-sm text-ink-soft">Sekolah atau kampus yang sedang dan pernah ditempuh.</p>
            </div>
            <button type="button" data-repeater-add class="btn-secondary">Tambah pendidikan</button>
        </div>

        <div class="mt-5 space-y-4" data-repeater-list>
            @forelse ($educations as $index => $row)
                @include('panel.partials.row-education', ['index' => $index, 'row' => (array) $row])
            @empty
                @include('panel.partials.row-education', ['index' => 0, 'row' => []])
            @endforelse
        </div>

        <template data-repeater-template>
            @include('panel.partials.row-education', ['index' => '__INDEX__', 'row' => []])
        </template>

        @error('educations.*.school')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </section>

    {{-- Sertifikasi --}}
    <section class="panel-card p-6" data-repeater>
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-display text-lg font-semibold text-ink">Sertifikasi</h2>
                <p class="mt-1 text-sm text-ink-soft">Opsional. Kosongkan bila belum ada.</p>
            </div>
            <button type="button" data-repeater-add class="btn-secondary">Tambah sertifikat</button>
        </div>

        <div class="mt-5 space-y-4" data-repeater-list>
            @forelse ($certifications as $index => $row)
                @include('panel.partials.row-certification', ['index' => $index, 'row' => (array) $row])
            @empty
                @include('panel.partials.row-certification', ['index' => 0, 'row' => []])
            @endforelse
        </div>

        <template data-repeater-template>
            @include('panel.partials.row-certification', ['index' => '__INDEX__', 'row' => []])
        </template>

        @error('certifications.*.name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </section>

    @if ($isAdminForm)
        <section class="panel-card p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Akun dan urutan</h2>
            <p class="mt-1 text-sm text-ink-soft">Akun ini dipakai anggota untuk masuk ke panel. Sandi awal
                {{ config('tekara.default_password') }} wajib diganti saat pertama masuk.</p>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="login_email">Email login</label>
                    <input id="login_email" name="login_email" type="email"
                        value="{{ old('login_email', $member->user?->email) }}" class="form-input"
                        placeholder="nama@tekara.my.id" @required($member->user_id === null)>
                    @error('login_email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="account_role">Peran akun</label>
                    <select id="account_role" name="account_role" class="form-input" @disabled($isSelf)>
                        @foreach (\App\UserRole::cases() as $role)
                            <option value="{{ $role->value }}" @selected(old('account_role', $member->user?->role?->value ?? \App\UserRole::Member->value) === $role->value)>{{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    @if ($isSelf)
                        <p class="form-hint">Peran akun Anda sendiri tidak bisa diubah dari sini.</p>
                    @else
                        <p class="form-hint">Admin boleh mengelola semuanya, anggota hanya CV, proyek, dan beritanya.
                        </p>
                    @endif
                    @error('account_role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="sort_order">Urutan tampil</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" max="999"
                        value="{{ old('sort_order', $member->sort_order) }}" class="form-input">
                    <p class="form-hint">Angka lebih kecil tampil lebih dulu.</p>
                    @error('sort_order')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if ($member->exists && $member->user && !$isSelf)
                <div class="mt-6 border-t border-line pt-5">
                    <p class="text-sm font-semibold text-ink">Sandi akun</p>
                    <p class="mt-1 text-sm text-ink-soft">Kembalikan sandi ke sandi awal bila anggota lupa sandinya. Ia
                        wajib menggantinya saat masuk.</p>
                    <button type="submit" form="reset-password-{{ $member->getKey() }}"
                        class="btn-secondary mt-3">Atur ulang sandi</button>
                </div>
            @endif
        </section>
    @endif
</div>
