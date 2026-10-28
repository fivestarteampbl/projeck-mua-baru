@extends('layouts.admin')
@section('title', 'Kelola Layanan')
@section('page-title', 'Kelola Layanan')

@section('content')
{{-- Tambah Paket --}}
<div class="flex justify-end mb-6">
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="bg-dark text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-dark/80 transition flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Paket
    </button>
</div>

@forelse($pricelists as $namaLayanan => $items)
<div class="mb-8 bg-[#F8EEE9] p-4 md:p-6 rounded-2xl shadow-sm border border-blush/30">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-lg">{{ $namaLayanan }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] text-sm text-left">
            <thead>
                <tr class="border-b border-dark/10">
                    <th class="pb-2 font-semibold">Foto</th>
                    <th class="pb-2 font-semibold">Nama Paket</th>
                    <th class="pb-2 font-semibold">Harga</th>
                    <th class="pb-2 font-semibold">Status</th>
                    <th class="pb-2 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr class="border-b border-dark/5 hover:bg-white/40">
                    <td class="py-3">
                        <x-photo-thumb :path="$item->foto_pricelist" :alt="$item->nama_pricelist" size="w-10 h-10" />
                    </td>
                    <td class="py-3 font-medium">
                        {{ $item->nama_pricelist }}
                    </td>
                    <td class="py-3">
                        <x-money :amount="$item->harga" />
                    </td>
                    <td class="py-3">
                        @if($item->layanan?->aktif)
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="py-3 text-center">
                        <button
                            data-id="{{ $item->id }}"
                            data-layanan="{{ $item->layanan_id }}"
                            data-nama="{{ $item->nama_pricelist }}"
                            data-deskripsi="{{ $item->deskripsi }}"
                            data-harga="{{ (int) $item->harga }}"
                            data-isi="{{ implode("\n", $item->isi_paket ?? []) }}"
                            data-syarat="{{ implode("\n", $item->syarat_ketentuan ?? []) }}"
                            onclick="bukaEditPaket(this)"
                            class="text-xs font-semibold text-dark hover:underline mr-2">
                            Edit
                        </button>

                        <form action="{{ route('admin.layanan.destroy', $item) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Hapus paket ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
    <p class="text-dark/40 text-sm">Belum ada paket.</p>
@endforelse

<x-admin.modal-form id="modalTambah" title="Tambah Paket" :action="route('admin.layanan.store')">
    @include('admin.layanan._form-paket')
</x-admin.modal-form>

<x-admin.modal-form id="modalEdit" title="Edit Paket" action="" method="PUT">
    @include('admin.layanan._form-paket', ['edit' => true])
</x-admin.modal-form>

@push('scripts')
<script>
function bukaEditPaket(btn) {
    const form = document.getElementById('modalEdit-form');
    form.action = '/admin/layanan/' + btn.dataset.id;
    form.querySelector('[name=layanan_id]').value = btn.dataset.layanan;
    form.querySelector('[name=nama_pricelist]').value = btn.dataset.nama;
    form.querySelector('[name=deskripsi]').value = btn.dataset.deskripsi;
    form.querySelector('[name=harga]').value = btn.dataset.harga;
    form.querySelector('[name=isi_paket]').value = btn.dataset.isi;
    form.querySelector('[name=syarat_ketentuan]').value = btn.dataset.syarat;
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>
@endpush
@endsection
