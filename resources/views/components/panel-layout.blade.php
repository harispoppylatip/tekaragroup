@props(['title', 'description' => null])

@php
    $user = auth()->user();
    $menu = array_filter([
        ['label' => 'Ringkasan', 'route' => 'panel.dashboard', 'active' => 'panel.dashboard', 'icon' => 'globe'],
        ['label' => 'Profil saya', 'route' => 'panel.profile.edit', 'active' => 'panel.profile.*', 'icon' => 'user'],
        $user->member
            ? ['label' => 'CV saya', 'route' => 'panel.cv.edit', 'active' => 'panel.cv.*', 'icon' => 'briefcase']
            : null,
        ['label' => 'Proyek', 'route' => 'panel.projects.index', 'active' => 'panel.projects.*', 'icon' => 'code'],
        ['label' => 'Berita', 'route' => 'panel.posts.index', 'active' => 'panel.posts.*', 'icon' => 'chat'],
        $user->isAdmin()
            ? [
                'label' => 'Anggota',
                'route' => 'panel.members.index',
                'active' => 'panel.members.*',
                'icon' => 'academic',
            ]
            : null,
        $user->isAdmin()
            ? [
                'label' => 'Pengaturan website',
                'route' => 'panel.settings.edit',
                'active' => 'panel.settings.*',
                'icon' => 'wrench',
            ]
            : null,
        ['label' => 'Ganti sandi', 'route' => 'panel.password.edit', 'active' => 'panel.password.*', 'icon' => 'link'],
    ]);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title }} | Panel {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen lg:flex">
    <aside class="bg-night text-white/70 lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-64 lg:shrink-0 lg:flex-col">
        <div class="flex h-16 items-center justify-between px-5">
            <a href="{{ route('panel.dashboard') }}" aria-label="Panel Tekara">
                <x-logo tone="light" />
            </a>
            <button type="button" class="rounded-md p-2 text-white lg:hidden" aria-expanded="false"
                data-panel-menu-toggle>
                <span class="sr-only">Buka menu panel</span>
                <x-icon name="menu" class="size-6" />
            </button>
        </div>

        <div class="flex flex-1 flex-col px-3 pb-5 max-lg:hidden" data-panel-sidebar>
            <nav class="space-y-1" aria-label="Menu panel">
                @foreach ($menu as $item)
                    <a href="{{ route($item['route']) }}" @class([
                        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
                        'bg-white/10 text-white' => request()->routeIs($item['active']),
                        'hover:bg-white/5 hover:text-white' => !request()->routeIs($item['active']),
                    ])
                        @if (request()->routeIs($item['active'])) aria-current="page" @endif>
                        <x-icon :name="$item['icon']" class="size-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-6 border-t border-white/10 pt-4 lg:mt-auto">
                <a href="{{ route('panel.profile.edit') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-1 transition hover:bg-white/5">
                    @if ($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto {{ $user->name }}"
                            class="size-9 shrink-0 rounded-full object-cover">
                    @else
                        <span
                            class="flex size-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-xs font-semibold text-white">{{ $user->initials }}</span>
                    @endif
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-medium text-white">{{ $user->name }}</span>
                        <span class="block truncate text-xs text-white/50">{{ $user->email }}
                            ({{ $user->role->label() }})</span>
                    </span>
                </a>
                <div class="mt-3 flex gap-2 px-3">
                    <a href="{{ route('home') }}" target="_blank"
                        class="flex-1 rounded-lg border border-white/15 px-3 py-2 text-center text-xs font-medium text-white transition hover:border-white/40">Lihat
                        website</a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg border border-white/15 px-3 py-2 text-xs font-medium text-white transition hover:border-white/40">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="border-b border-line bg-white">
            <div
                class="mx-auto flex max-w-5xl flex-col gap-4 px-4 py-6 sm:flex-row sm:items-end sm:justify-between sm:px-8">
                <div>
                    <h1 class="font-display text-2xl font-bold tracking-tight text-ink">{{ $title }}</h1>
                    @if ($description)
                        <p class="mt-1 text-sm text-ink-soft">{{ $description }}</p>
                    @endif
                </div>
                @isset($actions)
                    <div class="flex shrink-0 flex-wrap gap-2">{{ $actions }}</div>
                @endisset
            </div>
        </header>

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
                    role="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                    role="alert">
                    <p class="font-semibold">Ada isian yang perlu diperbaiki.</p>
                    <ul class="mt-1 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>
</body>

</html>
