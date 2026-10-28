@extends('layouts.admin')
@section('title', 'Kelola Galeri')
@section('page-title', 'Kelola Galeri')

@section('content')
<div class="flex justify-end mb-6">
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="bg-dark text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-dark/80 transition flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Foto
    </button>
</div>

@if($galeris->isEmpty())
    <p class="text-center text-dark/40 text-sm mt-8">Belum ada foto di galeri.</p>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
    @foreach($galeris as $foto)
    <div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden group">
        <div class="relative">
            <x-photo-thumb :path="$foto->foto" :alt="$foto->judul" size="w-full h-48" />
            @if(!$foto->aktif)
                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                    <span class="text-white text-xs font-semibold bg-black/50 px-2 py-1 rounded">Disembunyikan</span>
                </div>
            @endif
        </div>
        <div class="p-3">
            <p class="font-semibold text-sm truncate">{{ $foto->judul }}</p>
            <p class="text-xs text-dark/50">{{ $foto->kategori }}</p>
            <p class="text-xs mt-1 text-dark/70">{{ $foto->deskripsi }}</p>
            <div class="flex gap-2 mt-3">
                <button
                    data-id="{{ $foto->id }}"
                    data-judul="{{ $foto->judul }}"
                    data-kategori="{{ $foto->kategori }}"
                    data-deskripsi="{{ $foto->deskripsi }}"
                    data-aktif="{{ $foto->aktif ? '1' : '0' }}"
                    onclick="bukaEditGaleri(this)"
                        class="flex-1 text-xs font-semibold bg-pearly border border-dark/20 rounded-lg py-1.5 hover:bg-blush/30">Edit</button>
                <form action="{{ route('admin.galeri.destroy', $foto) }}" method="POST"
                      onsubmit="return confirm('Hapus foto ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 border border-red-200 rounded-lg px-3 py-1.5">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<x-admin.modal-form id="modalTambah" title="Tambah Foto" :action="route('admin.galeri.store')">
    @include('admin.galeri._form')
</x-admin.modal-form>

<x-admin.modal-form id="modalEdit" title="Edit Foto" action="" method="PUT">
    @include('admin.galeri._form', ['edit' => true])
</x-admin.modal-form>

@push('scripts')
<script>
function bukaEditGaleri(btn) {
    const form = document.getElementById('modalEdit-form');
    form.action = '/admin/galeri/' + btn.dataset.id;
    form.querySelector('[name=judul]').value = btn.dataset.judul;
    form.querySelector('[name=kategori]').value = btn.dataset.kategori;
    form.querySelector('[name=deskripsi]').value = btn.dataset.deskripsi;
    form.querySelector('[name=aktif]').checked = btn.dataset.aktif === '1';
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>
@endpush
@endsection
