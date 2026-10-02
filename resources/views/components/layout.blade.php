@props(['title' => null, 'description' => null])

@php
    $navigation = [
        ['label' => 'Layanan', 'href' => route('home') . '#layanan', 'route' => null],
        ['label' => 'Proyek', 'href' => route('home') . '#proyek', 'route' => null],
        ['label' => 'Tim', 'href' => route('home') . '#tim', 'route' => null],
        ['label' => 'Berita', 'href' => route('posts.index'), 'route' => 'posts.*'],
        ['label' => 'Kontak', 'href' => route('home') . '#kontak', 'route' => null],
    ];

    $metaDescription = $description ?? ($site['meta_description'] ?? null);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' | ' . config('app.name') : config('app.name') . ' | Website dan IoT' }}</title>
    @if ($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen">
    <a href="#konten"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:shadow">Langsung
        ke konten</a>

    <header class="no-print sticky top-0 z-40 border-b border-line/80 bg-white/90 backdrop-blur" data-site-header>
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('home') }}" class="shrink-0" aria-label="Tekara, kembali ke beranda">
                <x-logo />
            </a>

            <nav class="hidden items-center gap-6 md:flex lg:gap-8" aria-label="Navigasi utama">
                @foreach ($navigation as $item)
                    @php($active = $item['route'] && request()->routeIs($item['route']))
                    <a href="{{ $item['href'] }}" @if ($active) aria-current="page" @endif
                        @class([
                            'text-sm font-medium transition',
                            'text-brand' => $active,
                            'text-ink-soft hover:text-brand' => !$active,
                        ])>{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ route('login') }}"
                    class="text-sm font-medium text-ink-soft transition hover:text-brand">Masuk</a>
                <a href="{{ route('home') }}#kontak"
                    class="rounded-lg bg-ink px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-deep">Mulai
                    proyek</a>
            </nav>

            <button type="button" class="-mr-2 rounded-md p-2 text-ink md:hidden" aria-controls="menu-ponsel"
                aria-expanded="false" data-menu-toggle>
                <span class="sr-only">Buka menu</span>
                <x-icon name="menu" class="size-6" data-menu-icon="open" />
                <x-icon name="close" class="hidden size-6" data-menu-icon="close" />
            </button>
        </div>

        <nav id="menu-ponsel" class="hidden border-t border-line bg-white md:hidden" aria-label="Navigasi ponsel"
            data-menu>
            <div class="mx-auto flex max-w-6xl flex-col px-4 py-3">
                @foreach ($navigation as $item)
                    <a href="{{ $item['href'] }}"
                        class="rounded-md px-2 py-3 text-base font-medium text-ink hover:bg-canvas"
                        data-menu-link>{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ route('login') }}"
                    class="rounded-md px-2 py-3 text-base font-medium text-ink hover:bg-canvas" data-menu-link>Masuk</a>
            </div>
        </nav>
    </header>

    <main id="konten">
        {{ $slot }}
    </main>

    <footer class="no-print bg-night text-white/70">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-[1.4fr_1fr_1fr]">
            <div class="max-w-sm">
                <x-logo tone="light" />
                <p class="mt-5 text-sm leading-relaxed">{{ $site['footer_text'] }}</p>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-white">Jelajahi</h2>
                <ul class="mt-4 space-y-3 text-sm">
                    @foreach ($navigation as $item)
                        <li><a href="{{ $item['href'] }}" class="transition hover:text-white">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                    <li><a href="{{ route('login') }}" class="transition hover:text-white">Masuk panel</a></li>
                </ul>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-white">Hubungi</h2>
                <ul class="mt-4 space-y-3 text-sm">
                    @if ($site['contact_email'])
                        <li><a href="mailto:{{ $site['contact_email'] }}"
                                class="transition hover:text-white">{{ $site['contact_email'] }}</a></li>
                    @endif
                    @if ($site['contact_whatsapp'])
                        <li><a href="https://wa.me/{{ $site['contact_whatsapp'] }}" class="transition hover:text-white"
                                target="_blank" rel="noopener">WhatsApp</a></li>
                    @endif
                    @if ($site['contact_instagram'])
                        <li><a href="https://instagram.com/{{ $site['contact_instagram'] }}"
                                class="transition hover:text-white" target="_blank" rel="noopener">Instagram</a></li>
                    @endif
                    @if ($site['contact_location'])
                        <li>{{ $site['contact_location'] }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-6 text-xs sm:flex-row sm:justify-between sm:px-6">
                <p>&copy; {{ now()->year }} {{ config('app.name') }}. Hak cipta dilindungi.</p>
                <p>{{ $site['site_tagline'] }}</p>
            </div>
        </div>
    </footer>
</body>

</html>
