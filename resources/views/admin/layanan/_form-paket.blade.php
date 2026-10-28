{{-- resources/views/admin/layanan/_form-paket.blade.php --}}
<div class="space-y-3 text-sm max-h-[70vh] overflow-y-auto">
    <div>
        <label class="block text-xs font-semibold mb-1">Nama Paket</label>
        <input type="text" name="nama_pricelist" required class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark">
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Kategori</label>
        <select name="layanan_id" required class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark">
            @foreach($layanans as $l)
                <option value="{{ $l->id }}">{{ $l->nama_layanan }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Harga (Rp)</label>
        <input type="number" name="harga" min="0" required class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark">
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
        <textarea name="deskripsi" rows="2" required class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Isi Paket (satu per baris)</label>
        <textarea name="isi_paket" rows="5" class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark" placeholder="Make Up&#10;Hair Do&#10;Softlens"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Syarat &amp; Ketentuan</label>
        <textarea name="syarat_ketentuan" rows="5" class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark" placeholder="Syarat 1&#10;Syarat 2&#10;Syarat 3"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Foto{{ ($edit ?? false) ? ' Baru (opsional)' : '' }}</label>
        <input type="file" name="foto" accept="image/*" class="w-full text-xs">
    </div>
</div>
