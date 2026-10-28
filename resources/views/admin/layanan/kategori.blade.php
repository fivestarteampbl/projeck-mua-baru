@extends('layouts.admin')
@section('title', 'Kategori Layanan')
@section('page-title', 'Kategori Layanan')

@section('content')
{{-- Kategori Layanan --}}
<div class="bg-white rounded-xl shadow-sm border border-dark/10 p-4 md:p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold">Kategori Layanan</h3>
        <button onclick="document.getElementById('modalTambahLayanan').classList.remove('hidden')"
                class="text-xs font-semibold px-3 py-1.5 bg-dark text-white rounded-lg hover:bg-dark/80 transition flex items-center gap-1">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Layanan
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @forelse($layanans as $kat)
        <div class="border border-dark/10 rounded-xl p-4 flex items-center gap-4">
            @if($kat->foto_layanan && file_exists(public_path($kat->foto_layanan)))
                <img src="{{ asset($kat->foto_layanan) }}" class="w-16 h-16 rounded-lg object-cover shrink-0">
            @elseif($kat->foto_layanan && file_exists(storage_path('app/public/' . $kat->foto_layanan)))
                <img src="{{ \Illuminate\Support\Facades\Storage::url($kat->foto_layanan) }}" class="w-16 h-16 rounded-lg object-cover shrink-0">
            @else
                <div class="w-16 h-16 rounded-lg bg-blush/30 flex items-center justify-center shrink-0">
                    <span class="text-xs text-dark/40">No img</span>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm truncate">{{ $kat->nama_layanan }}</p>
                <p class="text-xs text-dark/50 truncate">{{ $kat->deskripsi }}</p>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-xs {{ $kat->aktif ? 'text-green-600' : 'text-red-500' }}">{{ $kat->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                    <span class="text-xs text-dark/40">{{ $kat->pricelists_count }} paket</span>
                </div>
            </div>
            <button data-kategori="{{ json_encode($kat->only(['id', 'nama_layanan', 'deskripsi', 'aktif']), JSON_HEX_APOS | JSON_HEX_QUOT) }}"
                    onclick="openEditKategori(this)"
                    class="text-xs font-semibold px-3 py-1.5 bg-pearly border border-dark/20 rounded-lg hover:bg-blush/30 shrink-0">
                Edit
            </button>
        </div>
        @empty
        <p class="text-sm text-dark/40">Belum ada kategori layanan.</p>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Layanan --}}
<div id="modalTambahLayanan" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Tambah Kategori Layanan</h3>
            <button type="button" onclick="document.getElementById('modalTambahLayanan').classList.add('hidden')">
                <i data-lucide="x" class="w-5 h-5 text-dark/50"></i>
            </button>
        </div>
        <form action="{{ route('admin.layanan-kategori.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-sm">
            @csrf
            @include('admin.layanan._form-kategori')
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalTambahLayanan').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-xs font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 bg-dark text-white rounded-lg text-xs font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Kategori --}}
<div id="modalEditKategori" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Edit Kategori Layanan</h3>
            <button type="button" onclick="document.getElementById('modalEditKategori').classList.add('hidden')">
                <i data-lucide="x" class="w-5 h-5 text-dark/50"></i>
            </button>
        </div>
        <form id="editKategoriForm" method="POST" enctype="multipart/form-data" class="space-y-3 text-sm">
            @csrf @method('PUT')
            @include('admin.layanan._form-kategori', ['edit' => true])
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalEditKategori').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-xs font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 bg-dark text-white rounded-lg text-xs font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditKategori(btn) {
    const data = JSON.parse(btn.dataset.kategori);
    document.getElementById('editKategoriForm').action = '{{ route('admin.layanan-kategori.index') }}/' + data.id;
    document.getElementById('editKatNama').value = data.nama_layanan;
    document.getElementById('editKatDeskripsi').value = data.deskripsi || '';
    document.getElementById('editKatAktif').checked = data.aktif;
    document.getElementById('modalEditKategori').classList.remove('hidden');
}
</script>
@endpush
@endsection
