@extends('layouts.admin')
@section('title', 'Moderasi Testimoni')
@section('page-title', 'Moderasi Testimoni')

@section('content')
{{-- Desktop Table --}}
<div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[700px] text-left border-collapse">
            <thead>
                <tr class="bg-pearly border-b border-dark/10 text-sm">
                    <th class="p-4 font-semibold w-10">Foto</th>
                    <th class="p-4 font-semibold">Pelanggan</th>
                    <th class="p-4 font-semibold w-32">Rating</th>
                    <th class="p-4 font-semibold w-1/3">Ulasan</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($testimonis as $t)
                @php $inisial = strtoupper(substr($t->nama_pelanggan, 0, 1)); @endphp
                <tr class="border-b border-dark/5 hover:bg-pearly/50">
                    <td class="p-4">
                        @if($t->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($t->foto))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($t->foto) }}"
                                 class="w-10 h-10 rounded-full object-cover" alt="">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blush flex items-center justify-center text-dark font-semibold text-xs">
                                {{ $inisial }}
                            </div>
                        @endif
                    </td>
                    <td class="p-4 font-medium">
                        {{ $t->nama_pelanggan }}<br>
                        <span class="text-xs text-dark/60 font-normal">{{ $t->booking?->pricelist?->layanan?->nama_layanan }}</span>
                    </td>
                    <td class="p-4">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $t->rating ? 'text-[#C9A227]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </td>
                    <td class="p-4 text-dark/80">{{ $t->ulasan }}</td>
                    <td class="p-4">
                        @if($t->status->value === 'ditampilkan')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Ditampilkan</span>
                        @elseif($t->status->value === 'disembunyikan')
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">Disembunyikan</span>
                        @else
                            <span class="px-3 py-1 bg-[#D9B45C]/15 text-[#8E6A1E] rounded-full text-xs font-semibold">Pending</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex gap-2 flex-wrap">
                            @if($t->status->value !== 'ditampilkan')
                            <form action="{{ route('admin.testimoni.updateStatus', $t) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="ditampilkan">
                                <button class="text-green-600 hover:text-green-800 text-xs font-semibold">Tampilkan</button>
                            </form>
                            @endif
                            @if($t->status->value !== 'disembunyikan')
                            <form action="{{ route('admin.testimoni.updateStatus', $t) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="disembunyikan">
                                <button class="text-red-500 hover:text-red-700 text-xs font-semibold">Sembunyikan</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.testimoni.destroy', $t) }}" method="POST"
                                  onsubmit="return confirm('Hapus testimoni ini?')">
                                @csrf @method('DELETE')
                                <button class="text-dark/40 hover:text-dark text-xs font-semibold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-dark/40 text-sm">Belum ada testimoni.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="block md:hidden space-y-3">
    @forelse($testimonis as $t)
    @php $inisial = strtoupper(substr($t->nama_pelanggan, 0, 1)); @endphp
    <div class="bg-white rounded-xl shadow-sm border border-dark/10 p-4">
        <div class="flex items-center gap-3 mb-2">
            @if($t->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($t->foto))
                <img src="{{ \Illuminate\Support\Facades\Storage::url($t->foto) }}"
                     class="w-10 h-10 rounded-full object-cover" alt="">
            @else
                <div class="w-10 h-10 rounded-full bg-blush flex items-center justify-center text-dark font-semibold text-xs shrink-0">
                    {{ $inisial }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm truncate">{{ $t->nama_pelanggan }}</p>
                <p class="text-xs text-dark/50 truncate">{{ $t->booking?->pricelist?->layanan?->nama_layanan }}</p>
            </div>
            @if($t->status->value === 'ditampilkan')
                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Ditampilkan</span>
            @elseif($t->status->value === 'disembunyikan')
                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">Disembunyikan</span>
            @else
                <span class="px-2 py-0.5 bg-[#D9B45C]/15 text-[#8E6A1E] rounded-full text-xs font-semibold">Pending</span>
            @endif
        </div>
        <div class="flex mb-2">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= $t->rating ? 'text-[#C9A227]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>
        <p class="text-sm text-dark/80 mb-3">{{ $t->ulasan }}</p>
        <div class="flex gap-2 flex-wrap">
            @if($t->status->value !== 'ditampilkan')
            <form action="{{ route('admin.testimoni.updateStatus', $t) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="ditampilkan">
                <button class="text-xs font-semibold px-3 py-1.5 bg-green-100 text-green-700 rounded-lg">Tampilkan</button>
            </form>
            @endif
            @if($t->status->value !== 'disembunyikan')
            <form action="{{ route('admin.testimoni.updateStatus', $t) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="disembunyikan">
                <button class="text-xs font-semibold px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg">Sembunyikan</button>
            </form>
            @endif
            <form action="{{ route('admin.testimoni.destroy', $t) }}" method="POST"
                  onsubmit="return confirm('Hapus testimoni ini?')">
                @csrf @method('DELETE')
                <button class="text-xs font-semibold px-3 py-1.5 bg-red-50 text-red-500 rounded-lg">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-dark/40 text-sm">Belum ada testimoni.</div>
    @endforelse
</div>

<div class="mt-4">{{ $testimonis->links() }}</div>
@endsection
