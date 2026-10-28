<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ULFAMUZA MUA - Make Up Banyuwangi')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poly&family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Primer & interaksi
                        primary: '#480C18',
                        'primary-hover': '#350A12',
                        accent: '#83624B',
                        'accent-gold-from': '#D6BD9F',
                        'accent-gold-to': '#B89A72',

                        // Latar
                        'bg-krem': '#FAF5F2',
                        'bg-form': '#FFF8F5',
                        'card-peach': '#EFD9CF',
                        'card-alt': '#E1C1B6',
                        'card-alt-2': '#F9EDE8',
                        'card-alt-3': '#F5E8E2',
                        'input-bg': '#FEFCFA',

                        // Border
                        'card-border': '#C9A090',
                        'card-border-alt': '#C4A48E',
                        'card-border-deep': '#A67865',

                        // Teks
                        'text-main': '#2E2320',
                        'abu': '#6B5E55',
                        'text-muted': '#8E7E73',
                        'footer-text': '#C8BAB0',

                        // Status
                        success: '#059669',
                        'success-text': '#065F46',
                        'success-bg': '#ECFDF5',
                        wa: '#25D366',
                        danger: '#DC2626',
                        'danger-bg': '#FEBCBC',

                        // Badge tier paket
                        'badge-bronze': '#83624B',
                        'badge-silver': '#D9D1CC',
                        'badge-gold': '#FCD34D',

                        // Kalender
                        'cal-selected': '#964B64',
                        'cal-highlight': '#F5ECD9',

                        'footer-bg': '#480C18',
                    },
                    borderRadius: {
                        card: '1.5rem',
                        input: '0.5rem',
                    },
                    fontFamily: {
                        serif: ['Poly', 'Playfair Display', 'serif'],
                        sans: ['Poppins', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', 'Inter', sans-serif; background-color: #FAF5F2; color: #2E2320; }
        .font-serif { font-family: 'Poly', 'Playfair Display', serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .rounded-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .rounded-card:hover { transform: translateY(-2px); box-shadow: 0 20px 40px -12px rgba(72,12,24,0.18); }
    </style>
    @stack('styles')
</head>
<body class="antialiased">

    {{-- Mobile overlay --}}
    <div id="navOverlay" class="fixed inset-0 bg-black/40 z-30 hidden"
         onclick="closeMobileMenu()"></div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="fixed top-0 right-0 z-40 h-full w-72 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 lg:hidden">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <a href="{{ route('home') }}">
                <span class="font-serif text-xl font-bold text-[#480C18]">ULFAMUZA MUA</span>
            </a>
            <button onclick="closeMobileMenu()" class="text-[#480C18]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="p-5 space-y-2">
            @php
                $navItems = [
                    ['route' => 'home',             'label' => 'Beranda'],
                    ['route' => 'layanan.index',    'label' => 'Layanan'],
                    ['route' => 'galeri.index',     'label' => 'Galeri'],
                    ['route' => 'kalender.index',   'label' => 'Kalender'],
                    ['route' => 'booking.cek',      'label' => 'Cek Booking'],
                ];
                try { $currentRoute = request()->route()->getName(); } catch (\Throwable $e) { $currentRoute = ''; }
            @endphp
            @foreach($navItems as $item)
                @php
                    if ($item['route'] === 'home') {
                        $active = $currentRoute === 'home';
                    } elseif ($item['route'] === 'layanan.index') {
                        $active = $currentRoute === 'layanan.index' || $currentRoute === 'layanan.show' || $currentRoute === 'pricelist.show';
                    } else {
                        $active = str_starts_with($currentRoute, $item['route']);
                    }
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="block px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ $active ? 'bg-[#EFD9CF] text-[#480C18]' : 'text-[#4A3F3A] hover:bg-[#EFD9CF]' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <hr class="border-gray-100 my-3">
            <a href="{{ route('booking.create') }}"
               class="block text-center bg-[#EFD9CF] text-[#480C18] px-4 py-3 rounded-[5px] text-sm font-medium hover:bg-card-alt transition-all duration-200">
                Pesan Sekarang
            </a>
            <a href="{{ route('login') }}"
               class="block text-center text-[#4A3F3A] text-sm font-medium py-2 hover:text-[#480C18] transition">
                Login Admin
            </a>
        </nav>
    </div>

    {{-- Desktop Header --}}
    <div class="sticky top-0 z-20 hidden lg:block">
        {{-- Logo strip (khusus Homepage) --}}
        @if($currentRoute === 'home')
        <div class="bg-white border-b border-[#EFD9CF]">
            <div class="max-w-[1440px] mx-auto px-6 h-[144px] flex items-center justify-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo-header.png') }}" alt="ULFA MUZA" class="h-[114px] w-auto object-contain">
                </a>
            </div>
        </div>
        @endif
        {{-- Nav bar --}}
        <nav class="bg-[#480C18]">
            <div class="max-w-[1440px] mx-auto px-6 h-16 flex items-center justify-center relative">
                <div class="flex items-center gap-8">
                    @foreach($navItems as $item)
                        @php
                            if ($item['route'] === 'home') {
                                $active = $currentRoute === 'home';
                            } elseif ($item['route'] === 'layanan.index') {
                                $active = $currentRoute === 'layanan.index' || $currentRoute === 'layanan.show' || $currentRoute === 'pricelist.show';
                            } else {
                                $active = str_starts_with($currentRoute, $item['route']);
                            }
                        @endphp
                        <a href="{{ route($item['route']) }}"
                           class="text-sm font-medium transition-all duration-200 {{ $active ? 'text-white font-semibold' : 'text-white/90 hover:text-white' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
                <div class="absolute right-6 flex items-center gap-3">
                    <a href="{{ route('booking.create') }}"
                       class="bg-[#EFD9CF] hover:bg-card-alt text-[#480C18] h-10 px-4 rounded-[5px] text-sm font-medium transition-all duration-200 flex items-center">
                        Pesan Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                       class="bg-[#EFD9CF] hover:bg-card-alt text-[#480C18] h-10 px-4 rounded-[5px] text-sm font-medium transition-all duration-200 flex items-center">
                        Login Admin
                    </a>
                </div>
            </div>
        </nav>
    </div>

    {{-- Mobile Navbar --}}
    <header class="sticky top-0 z-20 bg-white border-b border-[#EFD9CF] lg:hidden">
        <div class="flex items-center justify-between px-5 h-16">
            @if($currentRoute === 'home')
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo-header.png') }}" alt="ULFA MUZA" class="h-11 w-auto object-contain">
            </a>
            @else
            <a href="{{ route('home') }}" class="font-serif text-lg font-bold text-[#480C18]">ULFAMUZA MUA</a>
            @endif
            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ route('booking.create') }}"
                   class="bg-[#EFD9CF] hover:bg-card-alt text-[#480C18] text-xs font-medium px-4 h-9 rounded-[5px] transition flex items-center">
                    Pesan
                </a>
                <button onclick="toggleMobileMenu()" class="text-[#480C18] p-1">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#480C18] text-white">
        <div class="max-w-[1440px] mx-auto px-6 py-8 md:py-0 md:h-40">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 h-full">
                <div class="shrink-0">
                    <img src="{{ asset('images/logo-footer.png') }}" alt="ULFA MUZA" class="w-[155px] h-auto object-contain">
                </div>
                <div class="text-xs text-[#C8BAB0]">
                    &copy; {{ date('Y') }} ULFAMUZA Make Up Artist. All rights reserved.
                </div>
                <div class="flex items-center gap-5 text-xs text-[#C8BAB0]">
                    <a href="https://instagram.com/ulfamuza_makeup" target="_blank" rel="noopener"
                       class="hover:text-white transition">Instagram</a>
                    <span>Tiktok</span>
                    <a href="https://wa.me/6289539478239" target="_blank" rel="noopener"
                       class="hover:text-white transition">WhatsApp</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('translate-x-full');
            document.getElementById('navOverlay').classList.toggle('hidden');
        }
        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.add('translate-x-full');
            document.getElementById('navOverlay').classList.add('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
