<x-panel-layout title="Anggota" description="Setiap anggota punya akun sendiri untuk masuk ke panel.">
    <x-slot:actions>
        <a href="{{ route('panel.members.create') }}" class="btn-primary">Tambah anggota</a>
    </x-slot:actions>

    @if ($members->isEmpty())
        <div class="panel-card px-6 py-12 text-center">
            <p class="font-display text-lg font-semibold text-ink">Belum ada anggota</p>
            <p class="mt-1 text-sm text-ink-soft">Tambahkan anggota pertama beserta akunnya.</p>
        </div>
    @else
        <div class="panel-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[52rem] text-left text-sm">
                    <thead
                        class="border-b border-line bg-canvas text-xs font-semibold tracking-wide text-muted uppercase">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Peran</th>
                            <th scope="col" class="px-6 py-3">Akun masuk</th>
                            <th scope="col" class="px-6 py-3">Proyek</th>
                            <th scope="col" class="px-6 py-3">Urutan</th>
                            <th scope="col" class="px-6 py-3 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($members as $member)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($member->photo)
                                            <img src="{{ asset('storage/' . $member->photo) }}"
                                                alt="Foto {{ $member->name }}"
                                                class="size-10 shrink-0 rounded-xl object-cover">
                                        @else
                                            <span
                                                class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-canvas text-xs font-semibold text-muted">{{ $member->initials }}</span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-medium text-ink">{{ $member->name }}</p>
                                            <p class="mt-0.5 max-w-xs truncate text-xs text-muted">
                                                {{ $member->headline }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-ink-soft">{{ $member->role }}</td>
                                <td class="px-6 py-4">
                                    @if ($member->user)
                                        <p class="text-ink-soft">{{ $member->user->email }}</p>
                                        <p class="mt-0.5 text-xs text-muted">{{ $member->user->role->label() }}</p>
                                    @else
                                        <p class="text-xs font-medium text-amber-700">Belum punya akun</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-ink-soft">{{ $member->projects_count }}</td>
                                <td class="px-6 py-4 text-ink-soft">{{ $member->sort_order }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a href="{{ route('panel.members.edit', $member) }}"
                                            class="btn-secondary">Ubah</a>

                                        @if ($member->user && $member->user_id !== auth()->id())
                                            <form method="POST"
                                                action="{{ route('panel.members.reset-password', $member) }}"
                                                data-confirm="Kembalikan sandi {{ $member->name }} ke sandi awal?">
                                                @csrf
                                                <button type="submit" class="btn-secondary">Atur ulang sandi</button>
                                            </form>
                                        @endif

                                        @if ($member->user_id !== auth()->id())
                                            <form method="POST" action="{{ route('panel.members.destroy', $member) }}"
                                                data-confirm="Hapus {{ $member->name }} beserta akunnya? Tindakan ini tidak bisa dibatalkan.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-4 text-xs text-muted">{{ $members->count() }} anggota terdaftar.</p>
    @endif
</x-panel-layout>
