# Preferensi Coding — ProgWebMahir (Laravel)

## Bahasa
- Penjelasan ke user: **Bahasa Indonesia**.
- Kode (nama variabel, fungsi, class, route, kolom DB): **Bahasa Inggris**.
- Komentar kode: Bahasa Indonesia, singkat, hanya bila perlu.

## Gaya Kode Laravel
- **Controller tipis**: controller hanya menerima request, memanggil model/logic, dan mengembalikan response.
- **Validasi wajib lewat Form Request** (`php artisan make:request NamaRequest`), jangan `$request->validate()` di controller.
- Gunakan Eloquent + mass assignment (`$fillable`) dan route model binding.
- Ikuti konvensi penamaan Laravel (Model singular, tabel plural, controller `XxxController`).
- Response API konsisten dalam JSON dengan status code HTTP yang tepat (200, 201, 404, 422).
- **Cek seeder/data dummy yang sudah ada** di `database/seeders/` (dan isi tabel DB) sebelum mengusulkan atau membuat data dummy baru; jangan buat ulang jika sudah tersedia.

## Lingkungan
- Jalan lokal: `php artisan serve` (http://127.0.0.1:8000), database SQLite.

## Gaya Komunikasi
- Singkat dan langsung ke poin.

## Mode Belajar (Tugas Praktikum)
- User mengetik kode sendiri di VS Code. Jangan edit file project kecuali user bilang "kerjakan".
- Beri daftar langkah + petunjuk (perintah artisan, nama file, konsep), **bukan kode lengkap**.
- Kode lengkap hanya diberikan saat user bilang menyerah / minta jawabannya.
- Kalau user minta dicek, review kodenya dan tunjukkan letak kesalahan dulu sebelum memberi perbaikan.

## Anti-Slop
- Skill antislop terpasang di `.agents/skills/` (sumber: github.com/miqdadbadjuber/anti-slop).
- Mode default: **during** (terapkan saat mengerjakan, tutup dengan laporan Delivery Gate). Jangan tanya mode di awal sesi.
- Kode PHP/Laravel → `antislop-code`. Tampilan Blade/CSS → `antislop-ui`.
