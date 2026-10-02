<x-panel-layout title="CV saya" description="Isian di bawah tampil di halaman tim dan halaman profil Anda.">
    <x-slot:actions>
        <a href="{{ route('members.show', $member) }}" target="_blank" rel="noopener" class="btn-secondary">Lihat halaman
            profil</a>
    </x-slot:actions>

    <form method="POST" action="{{ route('panel.cv.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('panel.partials.profile-form', ['member' => $member, 'isAdminForm' => false])

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button type="submit" class="btn-primary">Simpan CV</button>
            <a href="{{ route('panel.dashboard') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</x-panel-layout>
