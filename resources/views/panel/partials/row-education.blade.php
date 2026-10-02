{{-- Satu baris pendidikan. --}}
<div data-repeater-row data-index="{{ $index }}"
    class="grid gap-4 rounded-xl border border-line bg-canvas p-4 sm:grid-cols-[2fr_2fr_1fr_auto] sm:items-end">
    <div>
        <label class="form-label" for="education-school-{{ $index }}">Sekolah atau kampus</label>
        <input id="education-school-{{ $index }}" type="text" name="educations[{{ $index }}][school]"
            value="{{ $row['school'] ?? '' }}" class="form-input" placeholder="Nama sekolah">
    </div>
    <div>
        <label class="form-label" for="education-major-{{ $index }}">Jurusan</label>
        <input id="education-major-{{ $index }}" type="text" name="educations[{{ $index }}][major]"
            value="{{ $row['major'] ?? '' }}" class="form-input" placeholder="Informatika">
    </div>
    <div>
        <label class="form-label" for="education-period-{{ $index }}">Periode</label>
        <input id="education-period-{{ $index }}" type="text" name="educations[{{ $index }}][period]"
            value="{{ $row['period'] ?? '' }}" class="form-input" placeholder="2024 - sekarang">
    </div>
    <button type="button" data-repeater-remove class="btn-danger">Hapus</button>
</div>
