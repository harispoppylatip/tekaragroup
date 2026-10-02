{{-- Satu baris pengalaman kerja. --}}
<div data-repeater-row data-index="{{ $index }}" class="rounded-xl border border-line bg-canvas p-4">
    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label class="form-label" for="experience-title-{{ $index }}">Jabatan</label>
            <input id="experience-title-{{ $index }}" type="text" name="experiences[{{ $index }}][title]"
                value="{{ $row['title'] ?? '' }}" class="form-input" placeholder="Web Developer">
        </div>
        <div>
            <label class="form-label" for="experience-place-{{ $index }}">Tempat</label>
            <input id="experience-place-{{ $index }}" type="text"
                name="experiences[{{ $index }}][place]" value="{{ $row['place'] ?? '' }}" class="form-input"
                placeholder="Tekara">
        </div>
        <div>
            <label class="form-label" for="experience-period-{{ $index }}">Periode</label>
            <input id="experience-period-{{ $index }}" type="text"
                name="experiences[{{ $index }}][period]" value="{{ $row['period'] ?? '' }}" class="form-input"
                placeholder="2026 - sekarang">
        </div>
    </div>

    <div class="mt-4">
        <label class="form-label" for="experience-description-{{ $index }}">Keterangan</label>
        <textarea id="experience-description-{{ $index }}" name="experiences[{{ $index }}][description]"
            rows="3" class="form-input" placeholder="Apa yang dikerjakan dan hasilnya.">{{ $row['description'] ?? '' }}</textarea>
    </div>

    <div class="mt-4 flex justify-end">
        <button type="button" data-repeater-remove class="btn-danger">Hapus</button>
    </div>
</div>
