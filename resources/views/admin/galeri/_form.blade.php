{{-- resources/views/admin/galeri/_form.blade.php --}}
<div class="space-y-3 text-sm">
    <div>
        <label class="block text-xs font-semibold mb-1">Judul</label>
        <input type="text" name="judul" required class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark" placeholder="Contoh: Wedding A">
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Kategori</label>
        <input type="text" name="kategori" required class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark" placeholder="Contoh: Wedding">
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
        <textarea name="deskripsi" rows="2" required class="w-full border border-dark/20 rounded-lg p-2 outline-none focus:border-dark"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Foto{{ ($edit ?? false) ? ' Baru (opsional)' : ' *' }}</label>
        <input type="file" name="foto" accept="image/*" class="w-full text-xs" {{ ($edit ?? false) ? '' : 'required' }}>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="aktif" value="1" checked class="accent-dark" id="galeri-aktif-{{ ($edit ?? false) ? 'edit' : 'tambah' }}">
        <label for="galeri-aktif-{{ ($edit ?? false) ? 'edit' : 'tambah' }}" class="text-xs">Tampilkan di Halaman Utama</label>
    </div>
</div>
