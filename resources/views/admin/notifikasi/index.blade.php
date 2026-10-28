@extends('layouts.admin')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold">
        Daftar Notifikasi
    </h3>

    <form action="{{ route('admin.notifikasi.bacaSemua') }}" method="POST">
        @csrf
        <button
            type="submit"
            class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm">
            Tandai Semua Dibaca
        </button>
    </form>
</div>

{{-- Desktop Table --}}
<div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden hidden md:block">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-4 text-left">Pesan</th>
                <th class="p-4 text-left">Tanggal</th>
                <th class="p-4 text-center">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($notifikasis as $n)
                <tr class="border-b">
                    <td class="p-4">
                        {{ $n->pesan }}
                    </td>

                    <td class="p-4">
                        {{ $n->created_at->format('d M Y H:i') }}
                    </td>

                    <td class="p-4 text-center">
                        @if($n->sudah_dibaca)
                            <span class="text-green-600 font-medium">
                                Sudah Dibaca
                            </span>
                        @else
                            <span class="text-red-600 font-medium">
                                Belum Dibaca
                            </span>
                        @endif
                    </td>

                    <td class="p-4 text-center">
                        <div class="flex gap-2 justify-center">

                            @unless($n->sudah_dibaca)
                            <form action="{{ route('admin.notifikasi.baca', $n) }}"
                                  method="POST">
                                @csrf
                                @method('PATCH')

                                <button
                                    class="px-3 py-1 bg-blue-500 text-white rounded">
                                    Baca
                                </button>
                            </form>
                            @endunless

                            <form action="{{ route('admin.notifikasi.destroy', $n) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus notifikasi?')">
                                @csrf
                                @method('DELETE')

                                <button
                                    class="px-3 py-1 bg-red-500 text-white rounded">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"
                        class="text-center p-8 text-gray-500">
                        Belum ada notifikasi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Mobile Cards --}}
<div class="block md:hidden space-y-3">
    @forelse($notifikasis as $n)
    <div class="bg-white rounded-xl shadow-sm border border-dark/10 p-4">
        <div class="flex items-start justify-between mb-2">
            <p class="text-sm">{{ $n->pesan }}</p>
            @unless($n->sudah_dibaca)
                <span class="w-2 h-2 bg-red-500 rounded-full shrink-0 mt-1.5"></span>
            @endunless
        </div>
        <p class="text-xs text-dark/50 mb-3">{{ $n->created_at->format('d M Y H:i') }}</p>
        <div class="flex gap-2">
            @unless($n->sudah_dibaca)
            <form action="{{ route('admin.notifikasi.baca', $n) }}" method="POST">
                @csrf @method('PATCH')
                <button class="px-3 py-1.5 bg-blue-500 text-white rounded text-xs">Baca</button>
            </form>
            @endunless
            <form action="{{ route('admin.notifikasi.destroy', $n) }}" method="POST"
                  onsubmit="return confirm('Hapus notifikasi?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1.5 bg-red-500 text-white rounded text-xs">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-gray-500 text-sm">Belum ada notifikasi</div>
    @endforelse
</div>

<div class="mt-4">
    {{ $notifikasis->links() }}
</div>

@endsection
