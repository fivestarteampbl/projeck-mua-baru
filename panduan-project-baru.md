# PANDUAN PROJECT BARU — OOP, SRP & Clean Code (ULFAMUZA MUA)

Dokumen ini adalah **resep replikasi**: cara menyesuaikan project baru supaya **identik persis** dengan project ini — dari prinsip pemrograman (OOP, SRP, Clean Code), tampilan (UI), sampai logika (alur bisnis).

Gunakan dokumen ini bersama:

| Dokumen | Fungsi |
|---|---|
| `panduan-project-baru.md` (ini) | **Sumber kebenaran #1** — prinsip, konvensi, token UI, logika |
| `desain.md` | Niat desain (warna/spacing/komponen) — referensi visual |
| `mua-vendor-booking-system/*.md` | Rencana awal — **bisa beda** dengan kondisi final kode |

> Catatan: jika dokumen lama bertentangan dengan **kode aktual**, yang diikuti adalah **kode aktual**.
> Contoh: dokumen lama memakai namespace `Customer/`, kondisi final memakai **`Public/`**.

---

## 1. Stack yang Wajib Sama

| Aspek | Nilai |
|---|---|
| PHP | `^8.3` |
| Laravel | `^13.8` |
| Auth | **Laravel Breeze** (`routes/auth.php`, `resources/views/auth/*`, `layouts/guest|app|navigation`) |
| View | **Blade murni** — `@extends` / `@section` / `@yield` / `@stack` (bukan `<x-app-layout>` untuk halaman utama) |
| CSS | **Tailwind via CDN** (`https://cdn.tailwindcss.com`) + `tailwind.config` **inline di `<head>` layout** |
| Ikon | Halaman Publik: **SVG inline** · Halaman Admin: **Lucide CDN** (`<i data-lucide="...">` + `lucide.createIcons()`) |
| Font | Google Fonts di `<head>` layout (Poly, Playfair Display, Poppins, Inter) |
| Test | **Pest 4** (`tests/Feature`, `tests/Unit`) |
| Formatter | **Laravel Pint** (`vendor/bin/pint`) |

> Larangan: `tailwind.config.js` di root **hanya bawaan Breeze** — halaman publik/admin **tidak memakainya**.
> Token warna asli ada di blok `<script> tailwind.config = {...}` di dalam `layouts/public.blade.php` dan `layouts/admin.blade.php`.

---

## 2. Struktur Folder (Wajib sama)

```bash
app/
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php                 # abstract, semua controller extends ini
│   │   ├── CalendarController.php         # kalender (publik + admin, 1 controller)
│   │   ├── ProfileController.php          # bawaan Breeze
│   │   ├── Auth/                          # bawaan Breeze
│   │   ├── Admin/                         # panel admin
│   │   │   ├── DashboardController.php
│   │   │   ├── AdminBookingController.php
│   │   │   ├── AdminLayananController.php
│   │   │   ├── GaleriController.php
│   │   │   ├── TestimoniController.php
│   │   │   ├── LaporanController.php
│   │   │   └── NotifikasiController.php
│   │   └── Public/                        # halaman publik/customer
│   │       ├── HomeController.php
│   │       ├── LayananController.php
│   │       ├── PricelistController.php
│   │       ├── GaleriController.php
│   │       ├── BookingController.php
│   │       └── TestimoniController.php
│   └── Requests/                          # HANYA milik Breeze (LoginRequest, ProfileUpdateRequest)
├── Mail/BookingDibuat.php                 # mailable booking
├── Models/                                # 1 model = 1 tabel
│   ├── Booking.php  Layanan.php  Pricelist.php
│   ├── Galeri.php  Testimoni.php  Notifikasi.php
│   ├── DetailLayanan.php  User.php
├── Providers/AppServiceProvider.php
└── View/Components/                       # layout class Breeze (AppLayout, GuestLayout)

resources/views/
├── layouts/
│   ├── public.blade.php                   # layout halaman publik
│   ├── admin.blade.php                    # layout panel admin
│   ├── guest.blade.php  app.blade.php  navigation.blade.php   # Breeze
├── public/                                # halaman publik (home, layanan, pricelist, galeri, booking/, kalender/, testimoni/)
├── admin/                                 # halaman admin (dashboard, booking/, layanan/, galeri/, testimoni/, laporan/, notifikasi/, kalender/)
├── auth/  profile/  components/           # Breeze
└── emails/booking-dibuat.blade.php        # template email

routes/
├── web.php  auth.php  console.php
```

**Aturan penamaan:**

- Class: **PascalCase** + suffix `Controller` / nama tabel (`Booking`, `Layanan`)
- Method: **camelCase** (`index`, `store`, `updateStatus`, `updatePayment`, `tandaiDibaca`)
- Kolom tabel: **snake_case bahasa Indonesia** (`tanggal_acara`, `status_booking`, `nama_pricelist`)
- Path view: **dotted** sesuai folder (`admin.booking.index`, `public.booking.create`)
- Route name: **dot notation berprefix aktor** (`admin.booking.updateStatus`, `booking.create`)
- Folder controller: `Admin/` dan `Public/` (bukan `Customer/`)

---

## 3. Prinsip OOP — Penerapan di Project Ini

### 3.1 Encapsulation

Data model dibungkus lewat whitelist, cast, dan aksesor — kolom tidak diakses sembarangan.

```php
// app/Models/Booking.php
protected $table = 'booking';
protected $primaryKey = 'id_booking';

protected $fillable = [ 'id_pricelist', 'kode_booking', /* ... */ ];

protected $casts = [
    'tanggal_acara'   => 'date',
    'total_harga'     => 'decimal:2',
    'dp'              => 'decimal:2',
    'sudah_testimoni' => 'boolean',
];
```

**Aksesor attribute** (method `getXAttribute`) — representasi turunan data di layer model:

```php
// formatting uang -> dipakai view sebagai $paket->harga_format
public function getHargaFormatAttribute(): string
{
    return 'Rp. ' . number_format((float) $this->harga_pricelist, 0, ',', '.');
}

// badge status -> dipakai view sebagai {!! $booking->status_badge !!}
public function getStatusBadgeAttribute(): string
{
    return match ($this->status_booking) {
        'pending'     => '<span class="badge bg-warning">pending</span>',
        'konfirmasi'  => '<span class="badge bg-info">konfirmasi</span>',
        'selesai'     => '<span class="badge bg-success">selesai</span>',
        'batal'       => '<span class="badge bg-danger">batal</span>',
        default       => $this->status_booking,
    };
}
```

**Aturan project ini:**

- Kolom non-standar -> declare `$table`, `$primaryKey`; key string -> `$incrementing = false` + `$keyType = 'string'` (contoh: `Layanan`, `Pricelist`)
- Timestamp tidak ada -> `public $timestamps = false` (contoh: `Pricelist`)
- Kolom JSON -> `$casts = ['isi_paket' => 'array']`
- View **tidak pernah** format uang/badge sendiri — panggil aksesor model

### 3.2 Inheritance

```php
abstract class Controller {}                     // semua controller extends
class Booking extends Model { use HasFactory; }  // semua model extends Model
@extends('layouts.public')                       // semua view extends layout (Blade inheritance)
```

- 1 level inheritance saja — controller tidak saling waris antar modul
- Layout Blade = induk yang menyediakan `@yield('title')`, `@yield('page-title')`, `@yield('content')`, `@stack('scripts')`

### 3.3 Association (Relasi Eloquent)

Relasi **selalu dengan FK & owner key eksplisit** (karena key proyek non-default):

```php
// Booking -> Pricelist
public function pricelist()
{
    return $this->belongsTo(Pricelist::class, 'id_pricelist', 'id_pricelist');
}

// Layanan -> Pricelist (1-to-many)
public function pricelist()
{
    return $this->hasMany(Pricelist::class, 'id_layanan', 'id_layanan');
}
```

**Aturan wajib:**

- Selalu **eager loading**: `Booking::with(['pricelist.layanan'])` — hindari N+1
- Null-safe di view: `{{ $booking->pricelist?->nama_pricelist }}`
- Route model binding hanya untuk key standar (`{booking}` -> `Booking $booking`);
  key non-default (`id_pricelist`) -> query manual `->where('id_pricelist', $id)->firstOrFail()`

### 3.4 Lifecycle Hook (Event OOP)

Generasi kode booking dilakukan di **hook model** (otoritas ada di model, bukan controller):

```php
// app/Models/Booking.php
protected static function boot()
{
    parent::boot();

    static::creating(function ($booking) {
        $booking->kode_booking = 'MUA-' . strtoupper(Str::random(5));
    });
}
```

Catatan penting: `BookingController::store()` juga menghitung kode dengan do-while,
tetapi **nilai akhir selalu ditentukan hook `creating` di atas** (menimpa nilai controller).
Project baru: pertahankan pola ini agar perilaku identik.

### 3.5 Single Responsibility kelas

| Kelas | Satu tanggung jawab |
|---|---|
| 1 Model | 1 tabel + relasi/cast/aksesor/hook tabel itu |
| 1 Controller | 1 modul per aktor (`Public\BookingController`, `Admin\AdminBookingController`) |
| `App\Mail\BookingDibuat` | Hanya mengirim email booking (template: `emails/booking-dibuat.blade.php`) |
| `App\Http\Requests\*` | **Hanya milik Breeze** (jangan dipakai untuk modul baru — lihat bagian 4) |
| `CalendarController` | Semua endpoint kalender (halaman + data JSON publik & admin) |

---

## 4. SRP — Pembagian Tanggung Jawab per Layer

| Layer | TANGGUNG JAWAB | Contoh file | Yang TIDAK boleh ada di sini |
|---|---|---|---|
| **Route** (`routes/web.php`) | URL, nama route, grouping prefix+name, middleware | `Route::prefix('booking')->name('booking.')` | Logika bisnis, query, view |
| **Controller** | Orkestrasi HTTP: baca request -> validasi -> query -> side effect -> pilih view/redirect | `Admin/AdminBookingController.php` | Markup HTML, definisi tabel |
| **Model** | Skema, relasi, cast, aksesor, hook | `Models/Booking.php` | Baca `Request`, redirect, query filter user |
| **View** | Presentasi: markup, `@if/@forelse`, escape `{{ }}` | `resources/views/**` | Query kompleks (kecuali pola resmi bagian 6), aksi bisnis |
| **Mailable** | Template & pengiriman email | `Mail/BookingDibuat.php` | Logika booking |
| **Flash/Session** | Umpan balik hasil aksi | key `success` / `error` | — |

### Konvensi route yang dipakai

```php
// routes/web.php — grouping prefix + name bersamaan
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/buat',   [PublicBookingController::class, 'create'])->name('create');
    Route::post('/buat',  [PublicBookingController::class, 'store'])
        ->middleware('throttle:5,10')->name('store');      // rate limit wajib di aksi tulis publik
    Route::get('/sukses/{kode}', [PublicBookingController::class, 'sukses'])->name('sukses');
});

// Admin: prefix + name + middleware gabungan
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::patch('/booking/{booking}/status', [AdminBookingController::class, 'updateStatus'])
        ->name('booking.updateStatus');
    // ...
});
```

- Aksi tulis memakai **GET/POST/PATCH/DELETE** sesuai aksi (`@method('DELETE')` di form)
- Nama route selalu `aktor.modul.aksi` — cek menu aktif di nav memakai `str_starts_with($currentRoute, ...)`

### Konvensi validasi (penting — jangan dicampur)

- **Modul sendiri -> validasi INLINE di controller:**

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'nama_pelanggan' => 'required|string|max:50',
        'tanggal_acara'  => 'required|date|after:today',
        // ...
    ]);
}
```

- **Form Request (`app/Http/Requests/`) hanya untuk fitur Breeze** (`LoginRequest`, `ProfileUpdateRequest`)
- Project baru: **jangan** membuat Form Request untuk modul baru — ikuti pola inline agar konsisten

### Konvensi side effect

Setiap aksi bisnis yang menghasilkan jejak selalu di controller, dengan pola tetap:

```php
Notifikasi::create(['pesan' => 'Booking ' . $booking->kode_booking . ' telah dikonfirmasi...']);
```

| Aksi | Side effect wajib |
|---|---|
| Booking dibuat | `Notifikasi::create` + `Mail::to(...)->send(new BookingDibuat)` (dalam try/catch) |
| Status -> `konfirmasi` | `Notifikasi::create` (pesan DP) |
| Status -> `selesai` | generate `token_testimoni` (`Str::random(40)`) + `expired_testimoni` (`now()->addHours(24)`) + `Notifikasi::create` |
| Status -> `batal` | `Notifikasi::create` |
| Testimoni masuk | update `booking.sudah_testimoni = true` + `Notifikasi::create` |
| Upload/ganti gambar | hapus file lama (`Storage::disk('public')->delete(...)` / `@unlink(public_path(...))`) |

---

## 5. Clean Code — Konvensi Kode

### 5.1 Gaya penulisan PHP

- `<?php` di baris pertama, namespace sesuai folder (`App\Http\Controllers\Admin`)
- `use` diurutkan: framework dulu, baru `App\...`
- Method **satu aksi** per method, nama menjelaskan aksi (`updateStatus`, `updatePayment`, `tandaiSemuaDibaca`)
- Pass data ke view selalu dengan **`compact()`** (boleh multi-baris bila panjang)
- Komentar hanya penanda bagian: `// Booking`, `{{-- Filter Tabs --}}` — jangan komentari kode yang sudah jelas
- Format class mengikuti gaya Breeze: `class X extends Model` + baris `//` kosong di awal body
- Formatting: jalankan **`vendor/bin/pint`** sebelum commit

### 5.2 Pola query (harus identik)

**List + filter + search + pagination:**

```php
public function index(Request $request)
{
    $query = Booking::with(['pricelist.layanan'])->latest();

    if ($request->filled('status')) {
        $query->where('status_booking', $request->status);
    }

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nama_pelanggan', 'like', '%'.$request->search.'%')
              ->orWhere('no_hp', 'like', '%'.$request->search.'%')
              ->orWhere('email', 'like', '%'.$request->search.'%');
        });
    }

    $bookings = $query->paginate(15)->withQueryString();

    return view('admin.booking.index', compact('bookings'));
}
```

Aturan turunan:

- Pagination selalu **`paginate(15)->withQueryString()`** (50 untuk notifikasi)
- Data wajib ada -> **`firstOrFail()`** (404 otomatis)
- Agregasi dari query yang sama -> **`(clone $query)->where(...)`**
- Cek jadwal bentrok -> `whereDate(...)` + `whereRaw('waktu_acara LIKE ?', [...])`
- Data sudah diambil lalu perlu grup -> `->groupBy('kategori_layanan')` di collection
- Filter default: `->latest()` (terbaru di atas), agregasi laporan: `->orderBy('tanggal_acara', 'desc')`

### 5.3 Umpan balik (flash message)

| Kunci | Penampil |
|---|---|
| `->with('success', '...')` | layout admin otomatis (hijau) & view tertentu |
| `->with('error', '...')` | layout admin otomatis (merah) |
| `->withErrors(['field' => 'pesan'])` | `@if($errors->any())` di halaman form |

- Selalu `back()->with('success', '...')` untuk aksi di halaman yang sama
- `redirect()->route('...')->with('success', '...')` setelah create
- Form gagal -> `back()->withErrors([...])->withInput()`
- Guard token/izin -> `abort(403, 'pesan dalam Bahasa Indonesia')`
- Pesan selalu **Bahasa Indonesia**

### 5.4 Error handling

Email **tidak boleh** menggagalkan booking — wajib dibungkus:

```php
try {
    $booking->load('pricelist.layanan');
    Mail::to($booking->email)->send(new BookingDibuat($booking));
} catch (\Throwable $e) {
    Log::error('Gagal kirim email booking: ' . $e->getMessage());
}
```

### 5.5 Upload file (2 pola yang dipakai — jangan diganti lintas modul)

```php
// Pola A — storage/public (dipakai: QR pembayaran, testimoni, galeri)
$data['payment_qr'] = $request->file('payment_qr')->store('qr', 'public');
Storage::disk('public')->delete($booking->payment_qr);   // hapus file lama dulu

// Pola B — langsung ke public/ (dipakai: kategori & paket layanan)
$request->file('foto')->move(public_path('images/layanan'), $filename);
$data['foto'] = 'images/layanan/' . $filename;
@unlink(public_path($kategoriLayanan->foto));             // hapus file lama dulu
```

- Rule validasi gambar seragam: `nullable|image|mimes:jpg,jpeg,png|max:2048`
- Nama file Pola B: `strtolower(str_replace(' ', '-', $nama)) . '.png'`

### 5.6 Test & quality

- Test memakai **Pest** (`tests/Feature/...`); jalankan: `composer test` / `php artisan test`
- Sebelum commit: `vendor/bin/pint` (Laravel Pint sudah ada di `require-dev`)

---

## 6. Tampilan — Supaya Sama Persis

Ada **3 sistem tampil yang terpisah** — jangan disamakan:

| Sistem | Layout | Dipakai untuk |
|---|---|---|
| **A. Publik** | `layouts/public.blade.php` | home, layanan, pricelist, galeri, booking, kalender, testimoni |
| **B. Admin** | `layouts/admin.blade.php` | semua halaman `admin/*` |
| **C. Breeze** | `layouts/guest.blade.php`, `app.blade.php` | auth & profile — **biarkan bawaan, jangan diubah** |

### 6.1 Sistem A — Halaman Publik

**Cara kerja:** Tailwind CDN + `tailwind.config` inline di `<head>` layout. Salin blok ini **persis** ke project baru:

```html
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
```

**Google Fonts (urutan persis):** `Poly`, `Poppins` (300–700), `Playfair Display` (italic+normal 400–700), `Inter` (300–700).

**Resep komponen publik (kelas persis):**

| Elemen | Kelas |
|---|---|
| Kontainer halaman | `max-w-7xl mx-auto px-6 lg:px-10 pt-8 lg:pt-12` + pembungkus `pb-16 bg-bg-krem` (atau `bg-bg-form` untuk form) |
| Kontainer header/footer | `max-w-[1440px] mx-auto px-6` |
| Header halaman | divider `h-px ... bg-gradient-to-r from-transparent to-text-main/60` + label `text-xs font-semibold uppercase tracking-[1.2px]` + `h1 font-serif text-3xl lg:text-4xl text-primary font-bold` |
| Kartu | `bg-white rounded-card border border-card-border overflow-hidden shadow-md hover:shadow-lg transition-all duration-300` |
| Tombol CTA utama | `bg-primary hover:bg-primary-hover text-white rounded-full text-sm font-semibold transition-all duration-200` (pill) |
| Tombol header | `bg-[#EFD9CF] hover:bg-card-alt text-[#480C18] h-10 px-4 rounded-[5px] text-sm font-medium` |
| Input form | `rounded-input border border-card-border px-4 py-3 text-sm bg-input-bg focus:outline-none focus:border-[#A32D3B] focus:ring-2 focus:ring-[#A32D3B]/10 transition` |
| Label form | `block text-xs font-semibold mb-1.5 text-primary` |
| Link kembali | SVG panah inline + `inline-flex items-center gap-2 text-text-main hover:text-primary text-sm font-medium transition` |
| Banner error form | `p-5 bg-red-50 border border-red-200 rounded-card text-sm text-red-700` + daftar `@foreach($errors->all())` |
| Ikon | **SVG inline** (`<svg class="w-4 h-4" fill="none" stroke="currentColor" ...>`), gaya heroicon |

**Struktur view publik (wajib urut begini):**

```blade
@extends('layouts.public')

@section('title', 'Judul Halaman - ULFAMUZA MUA')

@section('content')
<div class="pb-16 bg-bg-krem">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-8 lg:pt-12">
        {{-- konten --}}
    </div>
</div>
@endsection
```

Layout publik juga menyediakan: nav mobile slide (`#mobileMenu` + `#navOverlay`, fungsi `toggleMobileMenu()`/`closeMobileMenu()`), nav desktop `bg-[#480C18]` tinggi `h-16`, footer `bg-[#480C18]`, deteksi menu aktif via `@php $currentRoute` + `str_starts_with`, dan `@stack('scripts')` di akhir body.

### 6.2 Sistem B — Halaman Admin

```html
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
```

- `<body class="bg-pearly font-poppins text-dark flex h-screen overflow-hidden">`
- Sidebar: `w-64` (off-canvas mobile: `-translate-x-full md:translate-x-0`) + overlay `#sidebarOverlay`
- Header: `h-16 px-4 md:px-8 bg-pearly border-b border-dark/10`
- Main: `flex-1 overflow-y-auto p-4 md:p-8 bg-white/50`
- Ikon nav: `<i data-lucide="icon-nama" class="w-5 h-5"></i>` + `lucide.createIcons()` di akhir body
- Menu nav didefinisikan sebagai array `@php $navItems = [['route','icon','label'], ...]` lalu di-`@foreach`
- Item aktif: `str_starts_with($currentRoute, str_replace('.index','', $item['route']))`
- Badge notifikasi header: query langsung `@php \App\Models\Notifikasi::where('status_baca', false)->count() @endphp` (pola resmi, jangan dipindah ke controller)
- Flash message otomatis di main (salin persis):

```blade
@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
@endif
```

**Resep tabel admin (pola wajib — desktop tabel + mobile kartu):**

1. Filter tabs: `@foreach([...status=>'LABEL'] as $val => $label)` + link `route(..., array_merge(request()->query(), ['status'=>$val]))`, kelas `border-b-2` aktif = `border-dark text-dark`
2. Form pencarian GET: `<input name="search" value="{{ request('search') }}">` + hidden status
3. Desktop: `<div class="... hidden md:block">` + `<table class="w-full min-w-[700px]">`, thead `bg-pearly`, baris `border-b border-dark/5 hover:bg-pearly/50`
4. Mobile: `<div class="block md:hidden space-y-3">` berisi kartu `bg-white rounded-xl shadow-sm border border-dark/10 p-4`
5. `@forelse ... @empty` (pesan `Belum ada data.`) lalu `{{ $x->links() }}`
6. Badge status: `{!! $booking->status_badge !!}` (dari aksesor model)
7. Status pembayaran: `@if == 'lunas'` hijau / `== 'dp'` biru / else merah, kelas `text-xs font-semibold`
8. Hapus: form POST + `@csrf @method('DELETE')` + `onsubmit="return confirm('...?')"`

---

## 7. Logika — Alur yang Harus Identik

### 7.1 Booking dibuat (`Public\BookingController::store`)

Urutan langkah **wajib sama**:

1. **Validasi inline**: `id_pricelist` (exists), `nama_pelanggan` (max:50), `no_hp` (max:14), `email` (max:30), `tanggal_acara` (`date|after:today`), `waktu_acara` (`date_format:H:i`), `lokasi_acara` (max:100), `catatan_tambahan` (nullable, max:225)
2. Ambil paket: `Pricelist::with('layanan')->where('id_pricelist', ...)->firstOrFail()`
3. Guard paket mati: `if (!$pricelist->layanan || !$pricelist->layanan->aktif)` -> `back()->withErrors(['id_pricelist' => 'Paket ini sudah tidak tersedia'])`
4. **Cek jadwal bentrok**: `whereDate('tanggal_acara', ...)` + `whereRaw('waktu_acara LIKE ?', [$waktu.'%'])` + `whereIn('status_booking', ['pending','konfirmasi'])` -> jika ada: `back()->withErrors(['waktu_acara' => 'Jadwal tersebut sudah terisi...'])->withInput()`
5. Snapshot harga: `total_harga = harga_pricelist`, `dp = harga_pricelist * 0.3`, `status_booking = 'pending'`
6. Kode booking (lihat hook model bagian 3.4) — di controller ada do-while `'MUA-'.strtoupper(Str::random(5))`
7. `Booking::create($validated)` -> `Notifikasi::create(['pesan' => nama . ' telah melakukan booking.' . nama_pricelist])`
8. Email dalam try/catch (bagian 5.4) -> `return redirect()->route('booking.sukses', $booking->kode_booking)`

Halaman `create` juga melakukan guard: jika `?pricelist=` mengarah ke layanan non-aktif -> `back()->withErrors(['pricelist' => 'Layanan ini sudah tidak tersedia'])`.

### 7.2 Cek booking (`cek` -> `hasil`)

- `GET /booking/cek` -> view form; `GET /hasil-booking?kode_booking=...` -> hasil
- Tidak ada -> `back()->withErrors(['kode_booking' => 'Booking tidak ditemukan...'])`
- Jika `$request->ajax()` -> **JSON**: `status_booking`, `status_pembayaran`, `has_qr` (bool), `has_testimoni_token` (bool)
- Jika bukan ajax -> render view `public.booking.hasil`

### 7.3 Ubah status booking (`Admin\AdminBookingController::updateStatus`)

Validasi: `status_booking|required|in:pending,konfirmasi,selesai,batal`. Setelah `update`:

| Status baru | Efek |
|---|---|
| `konfirmasi` | Notifikasi: minta bayar DP |
| `selesai` | Jika belum ada `token_testimoni`: set token `Str::random(40)` + `expired_testimoni = now()->addHours(24)` + Notifikasi selesai |
| `batal` | Notifikasi pembatalan |

Kembali: `back()->with('success', 'Status booking berhasil diubah')`.

### 7.4 Ubah pembayaran (`updatePayment`)

Validasi: `payment_qr` (image), `metode_pembayaran`, `status_pembayaran|in:belum_dp,dp,lunas`.
QR lama (`bukan 'qr/default-qr.png'`) dihapus dulu, baru `store('qr','public')`. Flash: `Data pembayaran berhasil diperbarui`.

### 7.5 Testimoni via token (`Public\TestimoniController`)

- `create($token)`: cari `Booking::where('token_testimoni', $token)->firstOrFail()`
  - sudah test -> `abort(403, 'Anda sudah memberikan testimoni untuk booking ini.')`
  - expired/kosong -> `abort(403, 'Link testimoni sudah kadaluarsa')`
- `store($token)`: cari dengan `->where('expired_testimoni', '>', now())->firstOrFail()`
  - Validasi: `rating` (1..5), `ulasan` (max:500), `foto` (nullable image)
  - Simpan `status = 'pending'` -> update `booking.sudah_testimoni = true` -> Notifikasi -> `redirect()->route('home')->with('success', 'Terima kasih atas testimoni Anda')`

### 7.6 Kalender (`CalendarController`)

- Halaman: `GET /kalender` (publik) & `GET /admin/kalender`; data: `GET /kalender/data` & `GET /admin/kalender/data?year=&month=`
- Admin data: booking **`konfirmasi`** saja, `whereBetween` bulan berjalan, map ke array (id, kode, tanggal `Y-m-d`, waktu `H:i`, nama, hp, email, lokasi, catatan, layanan, pricelist, status, total, dp, `manage_url`) -> `response()->json(['events' => ...])`
- Publik data: booking `pending`/`konfirmasi`, hanya `tanggal_acara` + `waktu_acara` (tanpa data pribadi)

### 7.7 Laporan (`Admin\LaporanController::index`)

- Input `bulan`/`tahun` (default `now()`), query dasar `whereYear` + `whereMonth`
- `(clone $query)` untuk: total, pendapatan (selesai), hitung per status
- Statistik per layanan: `selectRaw('id_pricelist, count(*) as total, sum(total_harga) as omset')->groupBy('id_pricelist')` (status selesai)
- View: `admin.laporan.index` dengan `compact('total','pendapatan','pending','konfirmasi','selesai','batal','bookings','perLayanan','bulan','tahun')`

### 7.8 Manajemen layanan (`Admin\AdminLayananController`)

- `index`: ambil `Pricelist::with('layanan')->latest()`, kategori `Layanan::where('aktif', true)`, semua layanan
- ID kategori otomatis: `'lyn' . str_pad(Layanan::count() + 1, 2, '0', STR_PAD_LEFT)`
- `storeKategori` / `store` (paket) / `updateKategori` / `update` / `destroy` — semua **inline validate** + flash `back()->with('success', '...')`
- Upload memakai **Pola B** (`move(public_path('images/layanan'), ...)`)

---

## 8. Checklist Project Baru (ikuti urutannya)

**Setup**
1. `composer create-project laravel/laravel` (PHP 8.3, Laravel 13) + instal **Breeze** -> `php artisan breeze:install blade`
2. Salin `composer.json` scripts (`setup`, `dev`, `test`) dan pastikan ada `laravel/pint`, `pestphp/pest`

**Struktur & logika**
3. Buat folder `app/Http/Controllers/Admin` dan `app/Http/Controllers/Public` — jangan `Customer/`
4. Buat 1 model per tabel, lengkapi `$table`, `$primaryKey`, `$fillable`, `$casts`, relasi **dengan FK eksplisit**, aksesor (`getHargaFormatAttribute`, `getStatusBadgeAttribute`), hook `boot()` jika ada field generated
5. Tulis `routes/web.php` dengan pola `prefix()->name()->group()`, middleware `['auth','verified']` untuk admin, `throttle:5,10` untuk POST booking publik
6. Controller: inline `$request->validate()`, query pola `$query` + `filled()` + `paginate(15)->withQueryString()`, side effect `Notifikasi::create`, flash `success`/`error`
7. Email booking: kelas `App\Mail\BookingDibuat` + view `emails/booking-dibuat.blade.php` + try/catch `Log::error`

**Tampilan**
8. Buat `layouts/public.blade.php` dan `layouts/admin.blade.php` — **salin persis** blok `tailwind.config`, `<style>`, nav, footer, dan flash message dari project ini
9. Semua halaman memakai `@extends` + `@section('title')` + `@section('content')` (admin juga `@section('page-title')`)
10. Terapkan resep komponen di bagian 6 (kelas identik) — jangan ganti token warna
11. Halaman admin wajib punya versi **tabel desktop (`hidden md:block`)** + **kartu mobile (`block md:hidden`)**
12. Ikon: SVG inline (publik) / Lucide (admin)

**Verifikasi**
13. `vendor/bin/pint` -> `php artisan test` -> cek visual: warna, font, radius, spacing harus sesuai bagian 6

---

## 9. Anti-Pattern (DILARANG di project baru)

- Membuat Form Request untuk modul sendiri (project ini pakai inline validate)
- Memindahkan token warna ke `tailwind.config.js` (config asli inline di layout)
- Memakai `<x-app-layout>` untuk halaman utama (khusus Breeze)
- Query N+1: akses `$booking->pricelist->layanan` tanpa `with()`
- Format rupiah/badge langsung di view (harus lewat aksesor model)
- Mengirim email tanpa try/catch (bisa membatalkan booking)
- Pesan flash selain kunci `success`/`error` (admin layout tidak menampilkannya)
- Route name tanpa prefix aktor (`admin.` / `booking.`)
- Mengubah pola upload lintas modul (Pola A storage vs Pola B public — lihat bagian 5.5)
- Komentar panjang menjelaskan kode yang sudah jelas

---

## 10. Referensi Cepat Route -> View

| URL | Controller@method | View |
|---|---|---|
| `/` | `Public\HomeController@index` | `public.home` |
| `/layanan` | `Public\LayananController@index` | `public.layanan.index` |
| `/layanan/{layanan}` | `Public\LayananController@show` | `public.layanan.show` |
| `/pricelist/{id}` | `Public\PricelistController@show` | `public.pricelist.show` |
| `/booking/buat` (GET/POST) | `Public\BookingController@create/store` | `public.booking.create` |
| `/booking/sukses/{kode}` | `Public\BookingController@sukses` | `public.booking.sukses` |
| `/booking/cek-booking`, `/booking/hasil-booking` | `Public\BookingController@cek/hasil` | `public.booking.cek` / `.hasil` |
| `/galeri` | `Public\GaleriController@index` | `public.galeri` |
| `/kalender`, `/kalender/data` | `CalendarController@publicPage/publicData` | `public.kalender.index` |
| `/testimoni/{token}` (GET/POST) | `Public\TestimoniController@create/store` | `public.testimoni.create` |
| `/admin/dashboard` | `Admin\DashboardController@index` | `admin.dashboard` |
| `/admin/booking` | `Admin\AdminBookingController@index/show/updateStatus/updatePayment/destroy` | `admin.booking.index` / `.show` |
| `/admin/layanan` | `Admin\AdminLayananController@index/store/update/destroy` + kategori | `admin.layanan.index` |
| `/admin/galeri` | `Admin\GaleriController` | `admin.galeri.index` |
| `/admin/testimoni` | `Admin\TestimoniController` | `admin.testimoni.index` |
| `/admin/laporan` | `Admin\LaporanController@index` | `admin.laporan.index` |
| `/admin/notifikasi` | `Admin\NotifikasiController` | `admin.notifikasi.index` |
| `/admin/kalender`, `/admin/kalender/data` | `CalendarController@admin/adminData` | `admin.kalender.index` |

---

*Dokumen ini merupakan sumber kebenaran untuk prinsip OOP, SRP, Clean Code, tampilan, dan logika project ULFAMUZA MUA — gunakan saat menyesuaikan project baru agar identik.*
