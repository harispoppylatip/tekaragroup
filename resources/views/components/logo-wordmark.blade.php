@props(['tone' => 'dark'])

<svg {{ $attributes->merge(['viewBox' => '0 0 506 70', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg" fill="none" stroke="{{ $tone === 'light' ? '#ffffff' : '#1b2029' }}" stroke-width="14" stroke-linejoin="round">
    <path d="M0 7 H58 M29 7 V70" />
    <path d="M97 0 V70 M90 7 H138 M90 35 H131 M90 63 H138" />
    <path d="M177 0 V70 M226 -7 L186 35 L228 79" />
    <path d="M252 79 L286 7 L320 79" />
    <path d="M357 0 V70 M350 7 H379 A14 14 0 0 1 379 35 H357 M373 35 L404 79" />
    <path d="M432 79 L466 7 L500 79" />
</svg>
