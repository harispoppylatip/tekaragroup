@props(['post'])

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-line bg-white transition duration-300 hover:-translate-y-1 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5">
    <div class="relative aspect-[16/10] overflow-hidden">
        @if ($post->cover_image)
            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="Sampul {{ $post->title }}" class="size-full object-cover" loading="lazy">
        @else
            <div class="absolute inset-0 bg-night" aria-hidden="true">
                <x-logo-mark tone="light" class="absolute -right-8 -bottom-12 w-52 opacity-[0.08]" />
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-6">
        <div class="flex items-center justify-between gap-3 text-xs font-medium text-muted">
            <span>{{ $post->author?->name ?? 'Tim Tekara' }}</span>
            <span>{{ ($post->published_at ?? $post->created_at)?->translatedFormat('j F Y') }}</span>
        </div>

        <h3 class="mt-3 font-display text-lg font-semibold text-ink">
            <a href="{{ route('posts.show', $post) }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
        </h3>
        <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ $post->excerpt }}</p>

        <span class="mt-auto flex items-center gap-1.5 pt-6 text-sm font-semibold text-brand">
            Baca berita
            <x-icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" />
        </span>
    </div>
</article>
