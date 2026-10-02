{{-- Satu baris tautan. Dipakai oleh isian, tombol tambah, dan template baris. --}}
<div data-repeater-row data-index="{{ $index }}"
    class="grid gap-4 rounded-xl border border-line bg-canvas p-4 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
    <div>
        <label class="form-label" for="link-label-{{ $index }}">Nama tautan</label>
        <input id="link-label-{{ $index }}" type="text" name="links[{{ $index }}][label]"
            value="{{ $row['label'] ?? '' }}" class="form-input" placeholder="GitHub">
    </div>
    <div>
        <label class="form-label" for="link-url-{{ $index }}">Alamat</label>
        <input id="link-url-{{ $index }}" type="text" name="links[{{ $index }}][url]"
            value="{{ $row['url'] ?? '' }}" class="form-input" placeholder="https://contoh.com">
    </div>
    <button type="button" data-repeater-remove class="btn-danger">Hapus</button>
</div>
