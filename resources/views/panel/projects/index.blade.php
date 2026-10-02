<x-panel-layout title="Proyek" description="Karya yang ditampilkan di halaman depan website.">
    <x-slot:actions>
        <a href="{{ route('panel.projects.create') }}" class="btn-primary">Tambah proyek</a>
    </x-slot:actions>

    @if ($projects->isEmpty())
        <div class="panel-card px-6 py-12 text-center">
            <p class="font-display text-lg font-semibold text-ink">Belum ada proyek</p>
            <p class="mt-1 text-sm text-ink-soft">Tambahkan proyek pertama yang dikerjakan tim.</p>
        </div>
    @else
        <div class="panel-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[52rem] text-left text-sm">
                    <thead
                        class="border-b border-line bg-canvas text-xs font-semibold tracking-wide text-muted uppercase">
                        <tr>
                            <th scope="col" class="px-6 py-3">Proyek</th>
                            <th scope="col" class="px-6 py-3">Kategori</th>
                            <th scope="col" class="px-6 py-3">Klien</th>
                            <th scope="col" class="px-6 py-3">Tahun</th>
                            <th scope="col" class="px-6 py-3">Tim</th>
                            <th scope="col" class="px-6 py-3">Urutan</th>
                            <th scope="col" class="px-6 py-3 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($projects as $project)
                            @php
                                $canEdit =
                                    auth()->user()->isAdmin() ||
                                    ($myMemberId !== null && $project->members->contains('id', $myMemberId));
                            @endphp

                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-2">
                                        <span class="font-medium text-ink">{{ $project->title }}</span>
                                        @if ($project->is_featured)
                                            <span
                                                class="mt-0.5 shrink-0 text-xs font-semibold text-brand-deep">Unggulan</span>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 max-w-sm truncate text-xs text-muted">{{ $project->summary }}</p>
                                </td>
                                <td class="px-6 py-4 text-ink-soft">
                                    {{ $project->categories->map(fn($category) => $category->label())->join(', ') }}
                                </td>
                                <td class="px-6 py-4 text-ink-soft">{{ $project->client ?: 'Tidak dicatat' }}</td>
                                <td class="px-6 py-4 text-ink-soft">{{ $project->year }}</td>
                                <td class="px-6 py-4">
                                    <p class="max-w-[14rem] truncate text-ink-soft">
                                        {{ $project->members->pluck('name')->join(', ') ?: 'Belum ada' }}</p>
                                </td>
                                <td class="px-6 py-4 text-ink-soft">{{ $project->sort_order }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @if ($canEdit)
                                            <a href="{{ route('panel.projects.edit', $project) }}"
                                                class="btn-secondary">Ubah</a>
                                        @endif

                                        <a href="{{ route('projects.show', $project) }}" target="_blank" rel="noopener"
                                            class="btn-secondary">Lihat</a>

                                        @can('delete', $project)
                                            <form method="POST" action="{{ route('panel.projects.destroy', $project) }}"
                                                data-confirm="Hapus proyek {{ $project->title }}? Tindakan ini tidak bisa dibatalkan.">
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

        <p class="mt-4 text-xs text-muted">{{ $projects->count() }} proyek terdaftar.</p>
    @endif
</x-panel-layout>
