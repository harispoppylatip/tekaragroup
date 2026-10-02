<x-panel-layout title="Berita" description="Kabar dan tulisan yang terbit di halaman berita website.">
    <x-slot:actions>
        <a href="{{ route('panel.posts.create') }}" class="btn-primary">Tulis berita</a>
    </x-slot:actions>

    @if ($posts->isEmpty())
        <div class="panel-card px-6 py-12 text-center">
            <p class="font-display text-lg font-semibold text-ink">Belum ada berita</p>
            <p class="mt-1 text-sm text-ink-soft">Tulis kabar pertama dari tim.</p>
        </div>
    @else
        <div class="panel-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[48rem] text-left text-sm">
                    <thead class="border-b border-line bg-canvas text-xs font-semibold tracking-wide text-muted uppercase">
                        <tr>
                            <th scope="col" class="px-6 py-3">Judul</th>
                            <th scope="col" class="px-6 py-3">Penulis</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($posts as $post)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-ink">{{ $post->title }}</p>
                                    <p class="mt-0.5 max-w-sm truncate text-xs text-muted">{{ $post->excerpt }}</p>
                                </td>
                                <td class="px-6 py-4 text-ink-soft">{{ $post->author?->name ?? 'Tanpa penulis' }}</td>
                                <td class="px-6 py-4">
                                    @if ($post->isPublished())
                                        <span class="text-sm font-medium text-emerald-700">Terbit</span>
                                    @else
                                        <span class="text-sm font-medium text-amber-700">Draf</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-ink-soft">
                                    {{ ($post->published_at ?? $post->created_at)?->translatedFormat('j F Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @can('update', $post)
                                            <a href="{{ route('panel.posts.edit', $post) }}" class="btn-secondary">Ubah</a>
                                        @endcan

                                        @if ($post->isPublished())
                                            <a href="{{ route('posts.show', $post) }}" target="_blank" rel="noopener" class="btn-secondary">Lihat</a>
                                        @endif

                                        @can('delete', $post)
                                            <form method="POST" action="{{ route('panel.posts.destroy', $post) }}" data-confirm="Hapus berita {{ $post->title }}? Tindakan ini tidak bisa dibatalkan.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger">Hapus</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($posts->hasPages())
            <div class="mt-6">{{ $posts->links() }}</div>
        @endif
    @endif
</x-panel-layout>
