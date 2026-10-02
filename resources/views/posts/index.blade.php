<x-layout title="Berita">
    <section class="border-b border-line bg-white">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:py-20">
            <p class="text-sm font-medium text-muted">Berita</p>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Kabar dan catatan dari
                tim kami.</h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-ink-soft">Perkembangan proyek, pekerjaan baru, dan hal
                teknis yang kami kerjakan sehari-hari.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            @if ($posts->isEmpty())
                <div class="rounded-2xl border border-line bg-white px-6 py-16 text-center">
                    <p class="font-display text-lg font-semibold text-ink">Belum ada berita</p>
                    <p class="mt-2 text-sm text-ink-soft">Tulisan pertama akan segera tampil di sini.</p>
                    <a href="{{ route('home') }}#proyek"
                        class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-brand transition hover:text-brand-deep">
                        Lihat hasil proyek
                        <x-icon name="arrow-right" class="size-4" />
                    </a>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>

                @if ($posts->hasPages())
                    <div class="mt-12">{{ $posts->links() }}</div>
                @endif
            @endif
        </div>
    </section>
</x-layout>
