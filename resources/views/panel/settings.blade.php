<x-panel-layout title="Pengaturan website"
    description="Teks yang dipakai di halaman depan dan bagian bawah semua halaman.">
    <x-slot:actions>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-secondary">Lihat website</a>
    </x-slot:actions>

    <form method="POST" action="{{ route('panel.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Identitas dan halaman depan</h2>
                <p class="mt-1 text-sm text-ink-soft">Muncul paling atas saat pengunjung membuka website.</p>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="form-label" for="site_tagline">Semboyan</label>
                        <input id="site_tagline" name="site_tagline" type="text"
                            value="{{ old('site_tagline', $settings['site_tagline']) }}" class="form-input" required>
                        <p class="form-hint">Tiga kata dipisah koma, misalnya Teknologi, Karya, Rancang.</p>
                        @error('site_tagline')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="meta_description">Deskripsi untuk mesin pencari</label>
                        <input id="meta_description" name="meta_description" type="text"
                            value="{{ old('meta_description', $settings['meta_description']) }}" class="form-input"
                            required>
                        <p class="form-hint">Maksimal 160 karakter.</p>
                        @error('meta_description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label class="form-label" for="hero_title">Judul utama</label>
                    <input id="hero_title" name="hero_title" type="text"
                        value="{{ old('hero_title', $settings['hero_title']) }}" class="form-input" required>
                    @error('hero_title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label class="form-label" for="hero_text">Penjelasan singkat</label>
                    <textarea id="hero_text" name="hero_text" rows="3" class="form-input" required>{{ old('hero_text', $settings['hero_text']) }}</textarea>
                    @error('hero_text')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Kontak</h2>
                <p class="mt-1 text-sm text-ink-soft">Dipakai di bagian ajakan menghubungi kami dan di kaki halaman.</p>

                <div class="mt-6">
                    <label class="form-label" for="contact_title">Judul ajakan</label>
                    <input id="contact_title" name="contact_title" type="text"
                        value="{{ old('contact_title', $settings['contact_title']) }}" class="form-input" required>
                    @error('contact_title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label class="form-label" for="contact_text">Penjelasan ajakan</label>
                    <textarea id="contact_text" name="contact_text" rows="3" class="form-input" required>{{ old('contact_text', $settings['contact_text']) }}</textarea>
                    @error('contact_text')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="form-label" for="contact_email">Surel</label>
                        <input id="contact_email" name="contact_email" type="email"
                            value="{{ old('contact_email', $settings['contact_email']) }}" class="form-input"
                            placeholder="halo@contoh.com">
                        @error('contact_email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="contact_whatsapp">WhatsApp</label>
                        <input id="contact_whatsapp" name="contact_whatsapp" type="text"
                            value="{{ old('contact_whatsapp', $settings['contact_whatsapp']) }}" class="form-input"
                            placeholder="6281234567890">
                        <p class="form-hint">Awali dengan 62, tanpa tanda plus atau spasi.</p>
                        @error('contact_whatsapp')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="contact_instagram">Instagram</label>
                        <input id="contact_instagram" name="contact_instagram" type="text"
                            value="{{ old('contact_instagram', $settings['contact_instagram']) }}" class="form-input"
                            placeholder="tekara.id">
                        <p class="form-hint">Nama pengguna saja, tanpa tanda at dan tanpa tautan.</p>
                        @error('contact_instagram')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="contact_location">Lokasi</label>
                        <input id="contact_location" name="contact_location" type="text"
                            value="{{ old('contact_location', $settings['contact_location']) }}" class="form-input"
                            placeholder="Samarinda, Kalimantan Timur">
                        @error('contact_location')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="panel-card p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Kaki halaman</h2>

                <div class="mt-6">
                    <label class="form-label" for="footer_text">Penjelasan singkat di kaki halaman</label>
                    <textarea id="footer_text" name="footer_text" rows="3" class="form-input" required>{{ old('footer_text', $settings['footer_text']) }}</textarea>
                    @error('footer_text')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button type="submit" class="btn-primary">Simpan pengaturan</button>
            <a href="{{ route('panel.dashboard') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</x-panel-layout>
