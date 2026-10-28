@extends('layouts.admin')
@section('title', 'Kalender Booking')
@section('page-title', 'Kalender Booking')

@section('content')

{{-- Monthly Calendar Card --}}
<div class="bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden">
    {{-- Header Navigasi --}}
    <div class="flex items-center justify-between p-4 border-b border-dark/10">
        <button id="prevMonth" class="p-2 hover:bg-pearly rounded-lg transition" title="Bulan sebelumnya">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </button>
        <h3 id="monthLabel" class="font-semibold text-lg"></h3>
        <button id="nextMonth" class="p-2 hover:bg-pearly rounded-lg transition" title="Bulan berikutnya">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </button>
    </div>

    {{-- Hari --}}
    <div class="grid grid-cols-7 text-center text-xs font-semibold text-dark/50 border-b border-dark/10">
        @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $day)
            <div class="py-2">{{ $day }}</div>
        @endforeach
    </div>

    {{-- Grid Kalender --}}
    <div id="calendarGrid" class="grid grid-cols-7"></div>
</div>

{{-- Daily Timeline Panel --}}
<div id="dailyPanel" class="mt-6 bg-white rounded-xl shadow-sm border border-dark/10 overflow-hidden hidden">
    <div class="flex items-center justify-between px-4 py-3 border-b border-dark/10 bg-pearly">
        <div>
            <h4 id="dayTitle" class="font-semibold text-sm"></h4>
            <p id="daySubtitle" class="text-xs text-dark/50 mt-0.5"></p>
        </div>
        <button id="closeDay" class="p-1 hover:bg-blush/50 rounded-lg transition" title="Tutup">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>
    <div id="timelineBody" class="p-4 max-h-[560px] overflow-y-auto"></div>
    <div class="px-4 py-3 border-t border-dark/10 bg-pearly/50">
        <p class="text-xs text-dark/50">Klik <i data-lucide="eye" class="w-3.5 h-3.5 inline"></i> untuk melihat detail, atau <i data-lucide="arrow-right" class="w-3.5 h-3.5 inline"></i> untuk membuka halaman Kelola booking.</p>
    </div>
</div>

{{-- Modal Detail Booking --}}
<div id="bookingModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-dark/10">
            <h4 class="font-semibold text-base">Detail Booking</h4>
            <button id="closeModal" class="p-1 hover:bg-pearly rounded-lg transition" title="Tutup">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-5 space-y-4 text-sm max-h-[60vh] overflow-y-auto">
            <div>
                <p class="text-dark/50 text-xs mb-1">Nama</p>
                <p id="modalNama" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Layanan / Paket</p>
                <p id="modalLayanan" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Tanggal Acara</p>
                <p id="modalTanggal" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Waktu Acara</p>
                <p id="modalWaktu" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Lokasi</p>
                <p id="modalLokasi" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">No. WhatsApp</p>
                <p id="modalNoHp" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Email</p>
                <p id="modalEmail" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Kode Booking</p>
                <p id="modalKode" class="font-medium"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Status</p>
                <p id="modalStatus"></p>
            </div>
            <div>
                <p class="text-dark/50 text-xs mb-1">Catatan</p>
                <p id="modalCatatan" class="font-medium"></p>
            </div>
        </div>
        <div class="p-5 border-t border-dark/10 bg-pearly/50 flex items-center gap-2">
            <button id="closeModalBottom" class="flex-1 py-2 text-sm font-semibold rounded-lg border border-dark/20 hover:bg-dark/5 transition">Tutup</button>
            <a id="modalManage" href="#" class="flex-1 inline-flex items-center justify-center gap-2 py-2 text-sm font-semibold rounded-lg bg-dark text-white hover:opacity-90 transition">
                Kelola Booking
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    let currentYear = {{ now()->year }};
    let currentMonth = {{ now()->month }};
    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    const statusPeta = {
        'pending':      { label: 'pending',      warna: 'bg-amber-100 text-amber-700' },
        'konfirmasi':   { label: 'dikonfirmasi', warna: 'bg-blue-100 text-blue-700' },
        'selesai':      { label: 'selesai',      warna: 'bg-green-100 text-green-700' },
        'dibatalkan':   { label: 'dibatalkan',   warna: 'bg-red-100 text-red-700' },
        'ditolak':      { label: 'ditolak',      warna: 'bg-red-100 text-red-700' }
    };

    let eventsById = {};

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }

    async function fetchEvents() {
        const res = await fetch(`{{ route('admin.kalender.data') }}?year=${currentYear}&month=${currentMonth}`);
        const data = await res.json();
        return data.events;
    }

    function renderCalendar(events) {
        eventsById = {};
        events.forEach(e => { eventsById[e.id] = e; });

        const grid = document.getElementById('calendarGrid');
        const label = document.getElementById('monthLabel');
        label.textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        const today = new Date();

        const eventsByDate = {};
        events.forEach(e => {
            if (!eventsByDate[e.tanggal_acara]) eventsByDate[e.tanggal_acara] = [];
            eventsByDate[e.tanggal_acara].push(e);
        });

        let html = '';
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="min-h-[92px] md:min-h-[104px] border-b border-r border-dark/5 bg-pearly/30 p-1.5"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            const isToday = today.getFullYear() === currentYear && today.getMonth() + 1 === currentMonth && today.getDate() === day;
            const dayEvents = (eventsByDate[dateStr] || []).slice().sort((a, b) => a.waktu_acara.localeCompare(b.waktu_acara));
            const hasEvents = dayEvents.length > 0;

            html += `<div data-date="${dateStr}"
                          class="min-h-[92px] md:min-h-[104px] border-b border-r border-dark/5 p-1.5 ${isToday ? 'bg-blush/20' : ''} ${hasEvents ? 'cursor-pointer hover:bg-blush/10 transition calendar-day' : ''}">`;

            html += `<div class="flex items-center justify-between mb-1">`;
            html += `<span class="text-xs font-medium ${isToday ? 'bg-dark text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-dark/60'}">${day}</span>`;
            if (hasEvents) {
                html += `<span class="text-[9px] font-semibold text-[#8E6A1E] bg-[#D9B45C]/10 px-1.5 py-0.5 rounded-full">${dayEvents.length} booking</span>`;
            }
            html += `</div>`;

            if (hasEvents) {
                dayEvents.slice(0, 2).forEach(ev => {
                    html += `<div class="leading-tight mb-1">
                        <p class="text-[10px] font-semibold text-dark truncate"><span class="text-[#8E6A1E]">&#9679;</span> ${esc(ev.nama_pelanggan)}</p>
                        <p class="text-[9px] text-dark/50 truncate pl-2.5">${esc(ev.nama_pricelist)}</p>
                    </div>`;
                });
                if (dayEvents.length > 2) {
                    html += `<p class="text-[9px] font-medium text-dark/50 truncate pl-2.5">+ ${dayEvents.length - 2} booking lainnya</p>`;
                }
            }

            html += '</div>';
        }

        const totalCells = firstDay + daysInMonth;
        const remaining = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
        for (let i = 0; i < remaining; i++) {
            html += '<div class="min-h-[92px] md:min-h-[104px] border-b border-r border-dark/5 bg-pearly/30 p-1.5"></div>';
        }

        grid.innerHTML = html;
        lucide.createIcons();
        bindDayCells();
    }

    function bindDayCells() {
        document.querySelectorAll('.calendar-day').forEach(el => {
            el.addEventListener('click', function() {
                openDay(this.dataset.date);
            });
        });
    }

    function openDay(dateStr) {
        const dayEvents = Object.values(eventsById)
            .filter(e => e.tanggal_acara === dateStr)
            .sort((a, b) => a.waktu_acara.localeCompare(b.waktu_acara));

        const d = new Date(dateStr + 'T00:00:00');
        const dayName = d.toLocaleDateString('id-ID', { weekday: 'long' });
        const formatted = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        document.getElementById('dayTitle').textContent = `${dayName}, ${formatted}`;
        document.getElementById('daySubtitle').textContent = `${dayEvents.length} booking terkonfirmasi`;

        let html = '';
        for (let h = 0; h < 24; h++) {
            const hourLabel = `${String(h).padStart(2,'0')}:00`;
            const hourEvents = dayEvents.filter(e => parseInt(e.waktu_acara.substring(0,2), 10) === h);

            html += `<div class="flex">
                <div class="w-16 shrink-0 pt-1.5">
                    <span class="text-[11px] font-medium text-dark/40">${hourLabel}</span>
                </div>
                <div class="flex-1 border-l border-dark/10 pl-3 pb-3 min-h-[58px]">
                    ${hourEvents.map(renderBookingCard).join('')}
                </div>
            </div>`;
        }

        html += `<div class="flex">
            <div class="w-16 shrink-0 pt-1.5">
                <span class="text-[11px] font-medium text-dark/40">24:00</span>
            </div>
            <div class="flex-1 border-l border-dark/10 pl-3">
                <div class="border-t border-dashed border-dark/20 pt-1 text-[10px] text-dark/30">Akhir hari</div>
            </div>
        </div>`;

        document.getElementById('timelineBody').innerHTML = html;
        lucide.createIcons();
        bindCardActions();

        const panel = document.getElementById('dailyPanel');
        panel.classList.remove('hidden');
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function renderBookingCard(ev) {
        return `<div class="rounded-lg border border-dark/10 shadow-sm bg-white p-3 mb-2 booking-card">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-dark truncate">${esc(ev.nama_pelanggan)}</p>
                    <p class="text-[11px] text-dark/60 truncate mt-0.5">${esc(ev.nama_pricelist)}</p>
                    <p class="text-[11px] font-medium text-[#8E6A1E] mt-0.5">${esc(ev.waktu_acara)}</p>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button type="button" data-id="${ev.id}" class="booking-eye p-1.5 rounded-lg hover:bg-pearly transition" title="Lihat Detail">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                    <a href="${esc(ev.manage_url)}" class="p-1.5 rounded-lg hover:bg-pearly transition" title="Kelola booking">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>`;
    }

    function bindCardActions() {
        document.querySelectorAll('.booking-eye').forEach(el => {
            el.addEventListener('click', function() {
                const ev = eventsById[this.dataset.id];
                if (ev) openBookingModal(ev);
            });
        });
    }

    function openBookingModal(ev) {
        const d = new Date(ev.tanggal_acara + 'T00:00:00');
        const st = statusPeta[ev.status_booking] || { label: ev.status_booking || '-', warna: 'bg-blue-100 text-blue-700' };

        document.getElementById('modalNama').textContent = ev.nama_pelanggan;
 document.getElementById('modalLayanan').textContent = [ev.layanan, ev.nama_pricelist].filter(Boolean).join(' - ') || '-';
 document.getElementById('modalTanggal').textContent = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
 document.getElementById('modalWaktu').textContent = ev.waktu_acara;
 document.getElementById('modalLokasi').textContent = ev.lokasi_acara || '-';
 document.getElementById('modalNoHp').textContent = ev.no_hp || '-';
 document.getElementById('modalEmail').textContent = ev.email || '-';
 document.getElementById('modalKode').textContent = ev.kode_booking || '-';
 document.getElementById('modalStatus').innerHTML = `<span class="px-2 py-0.5 ${st.warna} rounded-full text-xs font-semibold">${st.label}</span>`;
 document.getElementById('modalCatatan').textContent = ev.catatan_tambahan || '-';
        document.getElementById('modalManage').setAttribute('href', ev.manage_url);

        const modal = document.getElementById('bookingModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        lucide.createIcons();
    }

    function closeModal() {
        const modal = document.getElementById('bookingModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function loadCalendar() {
        const events = await fetchEvents();
        renderCalendar(events);
    }

    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 1) { currentMonth = 12; currentYear--; }
        loadCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 12) { currentMonth = 1; currentYear++; }
        loadCalendar();
    });

    document.getElementById('closeDay').addEventListener('click', () => {
        document.getElementById('dailyPanel').classList.add('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', closeModal);
    document.getElementById('closeModalBottom').addEventListener('click', closeModal);
    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    loadCalendar();
})();
</script>
@endpush
