@props(['tone' => 'dark'])

@php
    $solid = $tone === 'light' ? '#ffffff' : '#1b2029';
    $gradientId = 'tekara-chevron-'.\Illuminate\Support\Str::random(6);
@endphp

<svg {{ $attributes->merge(['viewBox' => '0 0 440 318', 'fill' => 'none', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="{{ $gradientId }}" x1="222" y1="315" x2="438" y2="140" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#1530d8" />
            <stop offset="0.55" stop-color="#1d5bff" />
            <stop offset="1" stop-color="#12b4f7" />
        </linearGradient>
    </defs>
    <path d="M5 74 L75 6 Q78 3 83 3 H380 Q384 3 381 6 L316 69 Q307 78 293 78 H7 Q2 78 5 74 Z" fill="{{ $solid }}" />
    <path d="M137 103 Q137 99 141 99 H223 Q227 99 227 103 V214 Q227 222 221 228 L144 305 Q137 312 137 302 Z" fill="{{ $solid }}" />
    <path d="M252 99 H330 Q342 99 350 107 L432 192 Q438 199 432 206 L352 299 Q338 315 320 315 H222 L333 207 Z" fill="url(#{{ $gradientId }})" />
</svg>
