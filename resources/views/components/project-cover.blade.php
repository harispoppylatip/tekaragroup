@props(['project', 'fit' => 'cover'])

@php
    $isWeb = $project->categories->contains(\App\ProjectCategory::Website);
    $isIot = $project->categories->contains(\App\ProjectCategory::Iot);
    $background = $isWeb ? 'bg-night' : 'bg-linear-to-br from-brand-deep via-brand to-brand-sky';
    $hasCover = (bool) $project->cover_image;
    // "natural" lets the frame follow the image ratio so nothing is cropped.
    $naturalFit = $hasCover && $fit === 'natural';
    $frameClass = $naturalFit ? 'relative overflow-hidden bg-canvas' : 'relative aspect-[16/10] overflow-hidden';
@endphp

<div {{ $attributes->merge(['class' => $frameClass]) }}>
    @if ($hasCover)
        <img src="{{ asset('storage/'.$project->cover_image) }}" alt="Tampilan {{ $project->title }}"
            @class([
                'size-full object-cover' => ! $naturalFit,
                'block h-auto w-full' => $naturalFit,            ])
            loading="lazy">
    @else
        <div class="absolute inset-0 {{ $background }}" aria-hidden="true">
            <svg class="absolute -right-10 -bottom-12 h-[85%] text-white/[0.06]" viewBox="0 0 220 220" fill="currentColor">
                <path d="M30 0 H108 Q120 0 128 8 L210 93 Q216 100 210 107 L130 200 Q116 216 98 216 H0 L111 108 Z" />
            </svg>

            @if ($isWeb)
                <div class="absolute top-[16%] left-[9%] w-[62%] overflow-hidden rounded-lg border border-white/10 bg-night-soft shadow-2xl transition duration-500 group-hover:-translate-y-1">
                    <div class="h-4 border-b border-white/10 bg-white/5"></div>
                    <div class="grid grid-cols-[1fr_2fr] gap-2 p-3">
                        <div class="space-y-1.5">
                            <div class="h-1.5 w-4/5 rounded-sm bg-white/15"></div>
                            <div class="h-1.5 w-3/5 rounded-sm bg-white/10"></div>
                            <div class="h-1.5 w-2/3 rounded-sm bg-white/10"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-2 w-3/4 rounded-sm bg-white/25"></div>
                            <div class="flex h-10 items-end gap-1">
                                @foreach ([45, 70, 55, 85, 60, 95] as $height)
                                    <div class="flex-1 rounded-t-sm bg-brand/80" style="height: {{ $height }}%"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($isIot)
                <div @class([
                    'absolute flex items-center justify-center rounded-xl text-white shadow-2xl transition duration-500 group-hover:-translate-y-1',
                    'right-[9%] bottom-[14%] size-[30%] bg-linear-to-br from-brand-deep to-brand-sky' => $isWeb,
                    'top-1/2 left-1/2 size-[34%] -translate-x-1/2 -translate-y-1/2 border border-white/25 bg-white/10 backdrop-blur-sm group-hover:-translate-y-[54%]' => ! $isWeb,
                ])>
                    <x-icon name="chip" class="size-1/2" />
                </div>
            @endif

            @if (! $isWeb && ! $isIot)
                <div class="absolute inset-0 flex items-center justify-center text-white/80">
                    <x-icon name="code" class="size-1/4" />
                </div>
            @endif
        </div>
    @endif
</div>
