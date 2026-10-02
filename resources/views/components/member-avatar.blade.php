@props(['member'])

@if ($member->photo)
    <img src="{{ asset('storage/'.$member->photo) }}" alt="Foto {{ $member->name }}" {{ $attributes->merge(['class' => 'object-cover']) }}>
@else
    <div {{ $attributes->merge(['class' => 'relative flex items-center justify-center overflow-hidden bg-linear-to-br from-ink to-night-soft']) }} aria-hidden="true">
        <svg class="absolute -right-[20%] -bottom-[25%] h-[90%] text-brand/40" viewBox="0 0 220 220" fill="currentColor">
            <path d="M30 0 H108 Q120 0 128 8 L210 93 Q216 100 210 107 L130 200 Q116 216 98 216 H0 L111 108 Z" />
        </svg>
        <span class="relative font-display font-semibold text-white">{{ $member->initials }}</span>
    </div>
@endif
