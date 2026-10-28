<div class="space-y-3 text-sm">
    <div>
        <label class="block text-xs font-semibold mb-1">Nama Layanan</label>
        <input type="text" name="nama_layanan" @if($edit ?? false) id="editKatNama" @endif required
               class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark" placeholder="cth: Wedding Makeup">
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
        <textarea name="deskripsi" @if($edit ?? false) id="editKatDeskripsi" @endif rows="3"
                  class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Foto {{ ($edit ?? false) ? 'Baru (opsional)' : '(opsional)' }}</label>
        <input type="file" name="foto" accept="image/*" class="w-full text-xs">
        @if($edit ?? false)
        <p class="text-[10px] text-dark/50 mt-1">Kosongkan jika tidak ingin mengganti foto</p>
        @endif
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="aktif" id="{{ ($edit ?? false) ? 'editKatAktif' : 'aktifTambahLayanan' }}" value="1"
               @unless($edit ?? false) checked @endunless class="accent-dark">
        <label for="{{ ($edit ?? false) ? 'editKatAktif' : 'aktifTambahLayanan' }}" class="text-xs">Aktif / Tampilkan</label>
    </div>
</div>
