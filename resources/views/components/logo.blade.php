@props(['tone' => 'dark'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <x-logo-mark :tone="$tone" class="h-8 w-auto" />
    <x-logo-wordmark :tone="$tone" class="h-3.5 w-auto" />
    <span class="sr-only">Tekara</span>
</span>
