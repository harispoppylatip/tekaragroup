<x-panel-layout title="Ganti sandi" description="Sandi baru minimal 8 karakter dan tidak boleh sama dengan sandi awal.">
    @if ($isFirstLogin)
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="alert">
            <p class="font-semibold">Ganti sandi dulu sebelum melanjutkan.</p>
            <p class="mt-1">Ini kali pertama Anda masuk, jadi halaman lain belum bisa dibuka sebelum sandi diganti.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('panel.password.update') }}" class="panel-card max-w-xl p-6">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="form-label" for="current_password">Sandi saat ini</label>
                <input id="current_password" name="current_password" type="password" class="form-input"
                    autocomplete="current-password" required>
                @error('current_password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label" for="password">Sandi baru</label>
                <input id="password" name="password" type="password" class="form-input" autocomplete="new-password"
                    required>
                <p class="form-hint">Minimal 8 karakter dan tidak sama dengan sandi awal.</p>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label" for="password_confirmation">Ulangi sandi baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-input"
                    autocomplete="new-password" required>
                @error('password_confirmation')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-line pt-5">
            <button type="submit" class="btn-primary">Simpan sandi baru</button>
            @unless ($isFirstLogin)
                <a href="{{ route('panel.dashboard') }}" class="btn-secondary">Batal</a>
            @endunless
        </div>
    </form>
</x-panel-layout>
