{{-- Satu baris keahlian; daftar keahlian dipisah koma di dalam satu isian. --}}
@php
    $items = $row['items'] ?? '';
    $items = is_array($items) ? implode(', ', $items) : $items;
@endphp

<div data-repeater-row data-index="{{ $index }}"
    class="grid gap-4 rounded-xl border border-line bg-canvas p-4 sm:grid-cols-[1fr_2fr_auto] sm:items-end">
    <div>
        <label class="form-label" for="skill-group-{{ $index }}">Kelompok</label>
        <input id="skill-group-{{ $index }}" type="text" name="skills[{{ $index }}][group]"
            value="{{ $row['group'] ?? '' }}" class="form-input" placeholder="Web">
    </div>
    <div>
        <label class="form-label" for="skill-items-{{ $index }}">Keahlian</label>
        <input id="skill-items-{{ $index }}" type="text" name="skills[{{ $index }}][items]"
            value="{{ $items }}" class="form-input" placeholder="Laravel, PHP, MySQL">
        <p class="form-hint">Pisahkan setiap keahlian dengan koma.</p>
    </div>
    <button type="button" data-repeater-remove class="btn-danger">Hapus</button>
</div>
