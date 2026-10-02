<x-panel-layout title="Profil saya" description="Nama, surel, dan foto akun yang Anda pakai untuk masuk ke panel ini.">
    <form method="POST" action="{{ route('panel.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="panel-card p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Identitas akun</h2>
            <p class="mt-1 text-sm text-ink-soft">Nama ini tampil di menu panel dan sebagai penulis berita.</p>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="name">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        class="form-input" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="email">Surel untuk masuk</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                        class="form-input" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section class="panel-card p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Foto profil</h2>
            <p class="mt-1 text-sm text-ink-soft">Foto tampil bulat di menu panel. JPG, PNG, atau WebP maksimal 2 MB.
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-5">
                @if ($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto {{ $user->name }}"
                        class="size-16 shrink-0 rounded-full object-cover">
                @else
                    <span
                        class="flex size-16 shrink-0 items-center justify-center rounded-full bg-canvas font-display text-lg font-semibold text-muted">{{ $user->initials }}</span>
                @endif

                <div class="min-w-0 flex-1">
                    <label class="form-label" for="photo">Ganti foto</label>
                    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
                        class="form-input file:mr-3 file:rounded-md file:border-0 file:bg-canvas file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-ink">
                    @error('photo')
                        <p class="form-error">{{ $message }}</p>
                    @enderror

                    @if ($user->photo)
                        <label class="mt-3 flex items-center gap-2 text-sm text-ink-soft">
                            <input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo'))
                                class="size-4 rounded border-line text-brand focus:ring-brand">
                            Hapus foto
                        </label>
                    @endif
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">Simpan profil</button>
            <a href="{{ route('panel.dashboard') }}" class="btn-secondary">Batal</a>
        </div>
    </form>

    @if ($user->member)
        <section class="panel-card mt-6 p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Profil yang tampil di website</h2>
            <p class="mt-1 text-sm text-ink-soft">Foto dan CV di halaman tim diatur di halaman CV saya.</p>
            <a href="{{ route('panel.cv.edit') }}" class="btn-secondary mt-5">Buka CV saya</a>
        </section>
    @else
        <section class="panel-card mt-6 p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Belum tampil di halaman tim</h2>
            <p class="mt-1 text-sm text-ink-soft">Akun ini hanya mengelola panel. Agar tampil di halaman tim, tambahkan
                data anggota lalu hubungkan akun ini dari halaman Anggota.</p>
            <a href="{{ route('panel.dashboard') }}" class="btn-secondary mt-5">Kembali ke ringkasan</a>
        </section>
    @endif
</x-panel-layout>
