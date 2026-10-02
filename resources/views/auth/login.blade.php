<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Masuk | {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col bg-canvas">
    <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-6 sm:px-6">
        <a href="{{ route('home') }}" aria-label="{{ config('app.name') }}, kembali ke beranda">
            <x-logo />
        </a>
        <a href="{{ route('home') }}" class="text-sm font-medium text-ink-soft transition hover:text-brand">Kembali ke
            website</a>
    </header>

    <main class="flex flex-1 items-start justify-center px-4 pb-20">
        <div class="w-full max-w-md">
            <div class="panel-card p-8">
                <h1 class="font-display text-2xl font-bold tracking-tight text-ink">Masuk ke panel</h1>
                <p class="mt-2 text-sm leading-relaxed text-ink-soft">Gunakan email dan sandi yang diberikan admin.
                    Sandi awal wajib diganti saat pertama kali masuk.</p>

                @if (session('status'))
                    <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
                        role="status">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                        role="alert">
                        <p class="font-semibold">Tidak bisa masuk.</p>
                        <ul class="mt-1 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label class="form-label" for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="form-input" autocomplete="username" autofocus required>
                    </div>

                    <div>
                        <label class="form-label" for="password">Sandi</label>
                        <input id="password" name="password" type="password" class="form-input"
                            autocomplete="current-password" required>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-ink-soft">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                            class="size-4 rounded border-line text-brand focus:ring-brand">
                        Ingat saya di perangkat ini
                    </label>

                    <button type="submit" class="btn-primary w-full">Masuk</button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-muted">Lupa sandi? Hubungi admin untuk mengembalikannya ke sandi
                awal.</p>
        </div>
    </main>
</body>

</html>
