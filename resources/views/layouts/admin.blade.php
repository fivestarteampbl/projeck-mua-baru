<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - ULFAMUZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { pearly: '#FBF6F1', blush: '#E7C2C0', dark: '#4E3A43' }, fontFamily: { poppins: ['Poppins', 'sans-serif'] } } }
        }
    </script>
    <style>
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-1px); box-shadow: 0 8px 24px -8px rgba(74,58,67,0.15); }
    </style>
</head>
<body class="bg-pearly font-poppins text-dark flex h-screen overflow-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"
         onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden')"></div>

    <aside id="sidebar" class="fixed md:static z-40 w-64 h-full bg-pearly border-r border-dark/10 flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0">
        <div class="p-6 border-b border-dark/10">
            <a href="{{ route('home') }}">
                <h1 class="text-2xl font-bold leading-none">ULFAMUZA MUA</h1>
                <p class="text-[8px] tracking-widest mt-1">MAKE UP BANYUWANGI</p>
            </a>
        </div>
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto text-sm font-medium">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard',    'icon' => 'layout-dashboard',     'label' => 'Dashboard'],
                    ['route' => 'admin.kalender.index','icon' => 'calendar-days',        'label' => 'Kalender Booking'],
                    ['route' => 'admin.booking.index','icon' => 'calendar-check',        'label' => 'Kelola Booking'],
                    ['route' => 'admin.layanan.index','icon' => 'briefcase',             'label' => 'Kelola Layanan'],
                    ['route' => 'admin.layanan-kategori.index','icon' => 'folder-tree',  'label' => 'Kategori Layanan'],
                    ['route' => 'admin.testimoni.index','icon'=>'message-square-quote',  'label' => 'Moderasi Testimoni'],
                    ['route' => 'admin.galeri.index', 'icon' => 'images',                'label' => 'Kelola Galeri'],
                    ['route' => 'admin.notifikasi.index','icon' => 'bell-ring',          'label' => 'Notifikasi'],
                    ['route' => 'admin.laporan.index','icon' => 'bar-chart-2',           'label' => 'Laporan'],
                ];
                $currentRoute = request()->route()->getName();
            @endphp

            @foreach($navItems as $item)
                @php
                    $prefix = str_contains($item['route'], '.index') ? substr($item['route'], 0, -6) : $item['route'];
                    $active = $currentRoute === $prefix || str_starts_with($currentRoute, $prefix.'.');
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active ? 'bg-blush' : 'hover:bg-blush/50' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="p-4 border-t border-dark/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-50 text-red-600 transition text-sm font-medium">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 px-4 md:px-8 flex justify-between items-center bg-pearly border-b border-dark/10">
            <div class="flex items-center gap-3">
                <button class="md:hidden p-1"
                        onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('sidebarOverlay').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2 class="font-semibold text-lg">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('admin.kalender.index') }}" class="relative p-2 text-dark/60 hover:text-dark transition" title="Kalender Booking">
                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                </a>
                <a href="{{ route('admin.notifikasi.index') }}" class="relative p-2 text-dark/60 hover:text-dark transition">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    @php $unreadNotif = \App\Models\Notifikasi::where('sudah_dibaca', false)->count(); @endphp
                    @if($unreadNotif > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 font-bold">{{ $unreadNotif > 99 ? '99+' : $unreadNotif }}</span>
                    @endif
                </a>
                <div class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="font-medium text-sm hidden sm:inline">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-white/50">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>