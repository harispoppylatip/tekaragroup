{{-- Satu baris sertifikasi. --}}
<div data-repeater-row data-index="{{ $index }}"
    class="grid gap-4 rounded-xl border border-line bg-canvas p-4 sm:grid-cols-[2fr_2fr_1fr_auto] sm:items-end">
    <div>
        <label class="form-label" for="certification-name-{{ $index }}">Nama sertifikat</label>
        <input id="certification-name-{{ $index }}" type="text" name="certifications[{{ $index }}][name]"
            value="{{ $row['name'] ?? '' }}" class="form-input" placeholder="Junior Web Developer">
    </div>
    <div>
        <label class="form-label" for="certification-issuer-{{ $index }}">Penerbit</label>
        <input id="certification-issuer-{{ $index }}" type="text"
            name="certifications[{{ $index }}][issuer]" value="{{ $row['issuer'] ?? '' }}" class="form-input"
            placeholder="BNSP">
    </div>
    <div>
        <label class="form-label" for="certification-year-{{ $index }}">Tahun</label>
        <input id="certification-year-{{ $index }}" type="text"
            name="certifications[{{ $index }}][year]" value="{{ $row['year'] ?? '' }}" class="form-input"
            placeholder="2026">
    </div>
    <button type="button" data-repeater-remove class="btn-danger">Hapus</button>
</div>
