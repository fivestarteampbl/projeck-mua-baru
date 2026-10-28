<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ULFAMUZA MUA - Login Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#480C18',
                        'primary-hover': '#350A12',
                        accent: '#83624B',
                        'bg-krem': '#FFF8F5',
                        'abu': '#6B5E55',
                        'card-border': '#C9A090',
                        'input-bg': '#FEFCFA',
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-bg-krem font-sans text-primary antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10 relative overflow-hidden">

        {{-- Dekorasi latar --}}
        <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 bg-accent/10 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-24 w-96 h-96 bg-card-alt/20 rounded-full blur-3xl"></div>

        <div class="relative z-10 w-full max-w-md">

            {{-- Brand --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <span class="font-serif text-3xl font-bold text-primary tracking-tight block">ULFAMUZA</span>
                    <span class="block text-[10px] tracking-[4px] text-accent uppercase font-semibold mt-1.5">Make Up Artist Banyuwangi</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-primary/10 border border-card-border p-8 sm:p-10">
                {{ $slot }}
            </div>

            <p class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-sm text-abu hover:text-primary transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </p>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
