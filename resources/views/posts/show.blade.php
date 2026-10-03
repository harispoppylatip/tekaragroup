<x-layout :title="$post->title" :description="$post->excerpt">
    <article>
        <header class="border-b border-line bg-white">
            <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:py-16">
                <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-ink-soft transition hover:text-brand">
                    <x-icon name="arrow-left" class="size-4" />
                    Semua berita
                </a>

                <h1 class="mt-6 font-display text-3xl leading-tight font-bold tracking-tight text-ink sm:text-4xl lg:text-[2.75rem]">{{ $post->title }}</h1>
                <p class="mt-5 text-lg leading-relaxed text-ink-soft">{{ $post->excerpt }}</p>

                <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-2 text-sm text-muted">
                    @if ($post->author)
                        <span class="font-medium text-ink">{{ $post->author->name }}</span>
                    @endif
                    <span>{{ ($post->published_at ?? $post->created_at)?->translatedFormat('j F Y') }}</span>
                    <span>{{ $post->readingMinutes() }} menit baca</span>
                </div>
            </div>
        </header>

        @if ($post->cover_image)
            <div class="mx-auto max-w-4xl px-4 pt-10 sm:px-6">
                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="Sampul {{ $post->title }}" class="block h-auto w-full rounded-2xl border border-line">
            </div>
        @endif

        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:py-16">
            <div class="post-body">{!! $post->bodyHtml() !!}</div>

            @if ($post->author)
                <div class="mt-14 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-line bg-canvas px-6 py-5">
                    <div>
                        <p class="text-xs text-muted">Ditulis oleh</p>
                        <p class="mt-1 font-semibold text-ink">{{ $post->author->name }}</p>
                        @if ($post->author->member)
                            <p class="text-sm text-ink-soft">{{ $post->author->member->role }}</p>
                        @endif
                    </div>

                    @if ($post->author->member)
                        <a href="{{ route('members.show', $post->author->member) }}" class="btn-secondary">Lihat profil lengkap</a>
                    @endif
                </div>
            @endif
        </div>
    </article>

    @if ($morePosts->isNotEmpty())
        <section class="border-t border-line bg-canvas py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 class="font-display text-2xl font-bold tracking-tight text-ink">Berita lainnya</h2>
                    <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand transition hover:text-brand-deep">
                        Semua berita
                        <x-icon name="arrow-right" class="size-4" />
                    </a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($morePosts as $more)
                        <x-post-card :post="$more" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layout>
