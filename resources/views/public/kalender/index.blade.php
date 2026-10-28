@extends('layouts.public')

@section('title', 'Kalender Ketersediaan - ULFAMUZA MUA')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10 lg:py-14">

    <div class="text-center mb-10">
        <span class="text-[#A32D3B] text-xs font-semibold tracking-[4px] uppercase">Cek Ketersediaan</span>
        <h1 class="font-serif text-3xl lg:text-4xl text-primary font-bold mt-3">Kalender Ketersediaan</h1>
        <p class="text-text-main/70 mt-2 text-sm">Lihat jadwal yang sudah terisi sebelum melakukan pemesanan</p>
    </div>

    <div class="bg-white rounded-2xl border border-[#F0DBD3] shadow-lg overflow-hidden">
        {{-- Navigasi --}}
        <div class="flex items-center justify-between p-5 border-b border-[#F0DBD3] bg-[#F8E9E3]">
            <button id="prevMonth" class="p-2 hover:bg-white rounded-lg transition">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h3 id="monthLabel" class="font-serif font-bold text-lg text-primary"></h3>
            <button id="nextMonth" class="p-2 hover:bg-white rounded-lg transition">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Hari --}}
        <div class="grid grid-cols-7 text-center text-xs font-semibold text-text-main/50 border-b border-[#F0DBD3]">
            @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $day)
                <div class="py-2">{{ $day }}</div>
            @endforeach
        </div>

        {{-- Grid --}}
        <div id="calendarGrid" class="grid grid-cols-7"></div>
    </div>

    {{-- Legenda --}}
    <div class="flex items-center justify-center gap-6 mt-6 text-xs text-text-main/70">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            <span>Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-danger"></span>
            <span>Tidak Tersedia</span>
        </div>
    </div>

    {{-- Detail Hari --}}
    <div id="dayDetail" class="mt-8 hidden">
        <div class="bg-white rounded-2xl border border-[#F0DBD3] shadow-lg overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F0DBD3] bg-[#F8E9E3]">
                <h4 id="dayDetailTitle" class="font-serif font-bold text-primary"></h4>
            </div>
            <div id="dayDetailContent" class="p-5 space-y-3"></div>
        </div>
    </div>

    {{-- Peringatan --}}
    <div class="mt-8 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 text-danger shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.34 16a2 2 0 001.73 3z"/>
        </svg>
        <p class="text-xs text-danger leading-relaxed">
            Tanggal bertanda merah sudah terisi booking. Silakan pilih tanggal lain atau hubungi kami untuk ketersediaan lebih lanjut.
        </p>
    </div>

    <div class="text-center mt-8">
        <a href="{{ route('booking.create') }}"
           class="inline-block bg-[#A32D3B] hover:bg-[#8A2531] text-white px-8 py-3 rounded-full text-sm font-semibold transition-all duration-200 shadow-lg">
            Pesan Sekarang
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    let currentYear = {{ now()->year }};
    let currentMonth = {{ now()->month }};
    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    async function fetchEvents() {
        const res = await fetch(`{{ route('kalender.data') }}?year=${currentYear}&month=${currentMonth}`);
        const data = await res.json();
        return data.events || [];
    }

    function renderCalendar(events) {
        const grid = document.getElementById('calendarGrid');
        const label = document.getElementById('monthLabel');
        label.textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        const today = new Date();

        const bookedTimes = {};
        events.forEach(e => {
            if (!bookedTimes[e.tanggal_acara]) bookedTimes[e.tanggal_acara] = [];
            bookedTimes[e.tanggal_acara].push(e.waktu_acara);
        });

        let html = '';
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="min-h-[60px] md:min-h-[80px] border-b border-r border-[#F0DBD3]/50 bg-[#F8E9E3]/30"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            const isToday = today.getFullYear() === currentYear && today.getMonth() + 1 === currentMonth && today.getDate() === day;
            const hasBookings = bookedTimes[dateStr] && bookedTimes[dateStr].length > 0;
            const isPast = new Date(dateStr + 'T00:00:00') < new Date(today.getFullYear(), today.getMonth(), today.getDate());

            html += `<div class="min-h-[60px] md:min-h-[80px] border-b border-r border-[#F0DBD3]/50 p-1 cursor-pointer hover:bg-[#F8E9E3] transition day-cell ${isToday ? 'bg-[#A32D3B]/10' : ''} ${isPast ? 'opacity-50' : ''}" data-date="${dateStr}">`;
            html += `<div class="flex items-center justify-between mb-1">`;
            html += `<span class="text-xs font-medium ${isToday ? 'bg-cal-selected text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-text-main/60'}">${day}</span>`;
            if (hasBookings && !isPast) {
                html += `<span class="w-2 h-2 rounded-full bg-danger"></span>`;
            }
            html += `</div></div>`;
        }

        const totalCells = firstDay + daysInMonth;
        const remaining = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
        for (let i = 0; i < remaining; i++) {
            html += '<div class="min-h-[60px] md:min-h-[80px] border-b border-r border-[#F0DBD3]/50 bg-[#F8E9E3]/30"></div>';
        }

        grid.innerHTML = html;
        bindDayClicks(events, bookedTimes);
    }

    function bindDayClicks(events, bookedTimes) {
        document.querySelectorAll('.day-cell').forEach(cell => {
            cell.addEventListener('click', function() {
                const dateStr = this.dataset.date;
                const d = new Date(dateStr + 'T00:00:00');
                const dayName = d.toLocaleDateString('id-ID', { weekday: 'long' });
                const formatted = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                const times = bookedTimes[dateStr] || [];

                document.getElementById('dayDetailTitle').textContent = `${dayName}, ${formatted}`;

                let content = '';
                if (times.length === 0) {
                    content = `
                        <div class="flex items-center gap-3 text-sm text-green-700 bg-green-50 rounded-xl p-4">
                            <span class="w-3 h-3 rounded-full bg-green-400 shrink-0"></span>
                            <span>Belum ada booking. Tersedia untuk jadwal apapun.</span>
                        </div>`;
                } else {
                    const sorted = [...times].sort();
                    sorted.forEach(t => {
                        content += `
                            <div class="flex items-center gap-3 text-sm text-red-700 bg-red-50 rounded-xl p-4">
                                <span class="w-3 h-3 rounded-full bg-danger shrink-0"></span>
                                <span><strong>${t}</strong> — Tidak tersedia</span>
                            </div>`;
                    });
                }

                document.getElementById('dayDetailContent').innerHTML = content;
                document.getElementById('dayDetail').classList.remove('hidden');
                document.getElementById('dayDetail').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        });
    }

    async function loadCalendar() {
        const events = await fetchEvents();
        renderCalendar(events);
    }

    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 1) { currentMonth = 12; currentYear--; }
        document.getElementById('dayDetail').classList.add('hidden');
        loadCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 12) { currentMonth = 1; currentYear++; }
        document.getElementById('dayDetail').classList.add('hidden');
        loadCalendar();
    });

    loadCalendar();
})();
</script>
@endpush
