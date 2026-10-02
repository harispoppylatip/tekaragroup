@php
    $isEdit = $member->exists;
    $canResetPassword = $isEdit && $member->user !== null && $member->user_id !== auth()->id();
@endphp

<x-panel-layout :title="$isEdit ? 'Ubah anggota' : 'Tambah anggota'" :description="$isEdit
    ? 'Perubahan di sini juga memperbarui halaman profil anggota di website.'
    : 'Anggota baru langsung mendapat akun masuk dengan sandi awal ' . config('tekara.default_password') . '.'">
    <form method="POST" action="{{ $isEdit ? route('panel.members.update', $member) : route('panel.members.store') }}"
        enctype="multipart/form-data">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        @include('panel.partials.profile-form', ['member' => $member, 'isAdminForm' => true])

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button type="submit" class="btn-primary">{{ $isEdit ? 'Simpan perubahan' : 'Tambah anggota' }}</button>
            <a href="{{ route('panel.members.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>

    {{-- Form terpisah, dipanggil tombol di dalam isian profil lewat atribut form. --}}
    @if ($canResetPassword)
        <form id="reset-password-{{ $member->getKey() }}" method="POST"
            action="{{ route('panel.members.reset-password', $member) }}"
            data-confirm="Kembalikan sandi {{ $member->name }} ke sandi awal? Ia wajib menggantinya saat masuk.">
            @csrf
        </form>
    @endif
</x-panel-layout>
