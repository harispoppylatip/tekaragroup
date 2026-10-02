<x-panel-layout :title="$post->exists ? 'Ubah berita' : 'Tulis berita'" :description="$post->exists
    ? 'Perubahan langsung tampil di website setelah disimpan.'
    : 'Simpan sebagai draf dahulu bila belum siap terbit.'">
    <form method="POST" action="{{ $post->exists ? route('panel.posts.update', $post) : route('panel.posts.store') }}"
        enctype="multipart/form-data">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Isi berita</h2>

                <div class="mt-6">
                    <label class="form-label" for="title">Judul</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}"
                        class="form-input" placeholder="Tim Tekara merilis alat presensi baru" required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label class="form-label" for="excerpt">Ringkasan</label>
                    <textarea id="excerpt" name="excerpt" rows="2" class="form-input"
                        placeholder="Satu atau dua kalimat yang muncul di daftar berita." required>{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label class="form-label" for="body">Isi berita</label>
                    <textarea id="body" name="body" rows="16" class="form-input font-mono text-[13px]" required>{{ old('body', $post->body) }}</textarea>
                    <p class="form-hint">Boleh memakai penulisan Markdown sederhana, misalnya ## untuk sub judul dan
                        bintang dua untuk huruf tebal.</p>
                    @error('body')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Gambar sampul</h2>
                <p class="mt-1 text-sm text-ink-soft">JPG, PNG, atau WebP, maksimal 4 MB.</p>

                @if ($post->cover_image)
                    <div class="mt-5 flex flex-wrap items-center gap-4">
                        <img src="{{ asset('storage/' . $post->cover_image) }}" alt="Sampul {{ $post->title }}"
                            class="h-24 w-40 rounded-xl object-cover">
                        <label class="flex items-center gap-2 text-sm text-ink-soft">
                            <input type="checkbox" name="remove_cover_image" value="1" @checked(old('remove_cover_image'))
                                class="size-4 rounded border-line text-brand focus:ring-brand">
                            Hapus gambar sampul
                        </label>
                    </div>
                @endif

                <div class="mt-5">
                    <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp"
                        class="form-input file:mr-3 file:rounded-md file:border-0 file:bg-canvas file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-ink">
                    @error('cover_image')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Terbitkan</h2>

                <label class="mt-5 flex items-start gap-3 rounded-xl border border-line bg-canvas p-4">
                    <input type="checkbox" name="publish" value="1" @checked(old('publish', $post->isPublished()))
                        class="mt-0.5 size-4 rounded border-line text-brand focus:ring-brand">
                    <span>
                        <span class="block text-sm font-medium text-ink">Terbitkan di halaman berita</span>
                        <span class="mt-0.5 block text-xs text-muted">Bila tidak dicentang, berita disimpan sebagai draf
                            dan belum bisa dibaca pengunjung.</span>
                    </span>
                </label>
            </section>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button type="submit"
                class="btn-primary">{{ $post->exists ? 'Simpan perubahan' : 'Simpan berita' }}</button>
            <a href="{{ route('panel.posts.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</x-panel-layout>
