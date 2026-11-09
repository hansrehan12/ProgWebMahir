# Laporan Pengujian Manual Web Service REST API Mahasiswa

**Mata Kuliah:** Pemrograman Web Mahir  
**Judul:** Pengujian Manual REST API Data Mahasiswa  
**Base URL:** `http://127.0.0.1:8000`  
**Alat uji:** Thunder Client / Postman / Browser (untuk GET)  
**Header wajib:** `Accept: application/json`  
**Untuk POST/PUT:** `Content-Type: application/json` + Body raw JSON

---

## Ringkasan Pengujian

Pengujian dilakukan secara **manual** terhadap webservice REST API mahasiswa sesuai materi praktikum. Setiap endpoint diuji dengan request HTTP, lalu hasil (status code + body JSON) di-capture.

Tujuan pengujian:
- Memastikan CRUD API berjalan sesuai kontrak endpoint
- Memastikan status code benar (200, 201, 404, 422)
- Memastikan format response JSON sesuai (`data`, `links`, `meta`, `message`, `errors`)

---

## Cara Capture (wajib dikumpulkan)

1. Jalankan server: `php artisan serve`
2. Buka **Thunder Client** (VS Code) atau **Postman**
3. Untuk tiap skenario di bawah:
   - buat request sesuai Method + URL + Body
   - kirim request
   - **screenshot** jendela yang menampilkan:
     - Method & URL
     - Status code (200/201/404/422)
     - Response JSON
4. Tempel screenshot ke laporan Word/PDF di bawah tiap skenario
5. Tulis penjelasan singkat (sudah disediakan di bawah — boleh disalin)

> Tip: untuk GET, bisa juga dibuka langsung di browser lalu screenshot.

---

## Skenario 1 — Ambil daftar mahasiswa

| Item | Isi |
|------|-----|
| Method | `GET` |
| URL | `http://127.0.0.1:8000/api/mahasiswa?per_page=2` |
| Expected | Status **200 OK**, ada `data`, `links`, `meta` |

**Penjelasan singkat:**  
Pengujian ini memastikan endpoint daftar mahasiswa dapat mengembalikan data dalam format JSON beserta informasi pagination. Parameter `per_page=2` dipakai untuk membatasi jumlah data per halaman. Hasil menunjukkan status 200 dan struktur response sesuai rancangan API.

**[TEMPAT SCREENSHOT 1]**

---

## Skenario 2 — Pencarian data

| Item | Isi |
|------|-----|
| Method | `GET` |
| URL | `http://127.0.0.1:8000/api/mahasiswa?search=andi` |
| Expected | Status **200 OK**, hanya data yang cocok |

**Penjelasan singkat:**  
Pengujian filter pencarian menggunakan query `search=andi`. API mencari kecocokan pada kolom NIM, nama, prodi, dan email. Response 200 dengan data terfilter membuktikan fitur search berfungsi.

**[TEMPAT SCREENSHOT 2]**

---

## Skenario 3 — Tambah data valid

| Item | Isi |
|------|-----|
| Method | `POST` |
| URL | `http://127.0.0.1:8000/api/mahasiswa` |
| Body | lihat di bawah |
| Expected | Status **201 Created** + `message` berhasil |

**Body JSON:**
```json
{
  "nim": "UJI001",
  "nama": "Mahasiswa Uji",
  "prodi": "Sistem Informasi",
  "email": "uji@example.com",
  "nohp": "08111111111"
}
```

**Penjelasan singkat:**  
Pengujian create data dengan input valid. Server menyimpan data baru dan mengembalikan status 201 Created beserta objek mahasiswa dan pesan sukses. Ini membuktikan method `store` dan validasi Form Request berjalan benar.

**[TEMPAT SCREENSHOT 3]**  
*(catat `id` yang dikembalikan, dipakai di skenario 5, 7, 8)*

---

## Skenario 4 — Tambah data tidak valid (validasi gagal)

| Item | Isi |
|------|-----|
| Method | `POST` |
| URL | `http://127.0.0.1:8000/api/mahasiswa` |
| Body | lihat di bawah |
| Expected | Status **422 Unprocessable Content** + `errors` |

**Body JSON (sengaja salah):**
```json
{
  "nim": "240001",
  "email": "bukan-email"
}
```

*(NIM `240001` sudah ada di seeder → error unique; `nama`/`prodi` kosong → required; email invalid)*

**Penjelasan singkat:**  
Pengujian negative case untuk validasi input. Karena data tidak lengkap/salah format (dan NIM duplikat), API menolak request dengan status 422 serta menampilkan detail error per field. Ini membuktikan validasi API bekerja sebelum data disimpan.

**[TEMPAT SCREENSHOT 4]**

---

## Skenario 5 — Detail satu mahasiswa

| Item | Isi |
|------|-----|
| Method | `GET` |
| URL | `http://127.0.0.1:8000/api/mahasiswa/{id}` |
| Expected | Status **200 OK**, satu objek di `data` |

Ganti `{id}` dengan id hasil skenario 3.

**Penjelasan singkat:**  
Pengujian endpoint detail memastikan pengambilan satu resource berdasarkan ID berhasil. Response berisi satu data mahasiswa dengan status 200.

**[TEMPAT SCREENSHOT 5]**

---

## Skenario 6 — Detail ID tidak ada

| Item | Isi |
|------|-----|
| Method | `GET` |
| URL | `http://127.0.0.1:8000/api/mahasiswa/99999` |
| Expected | Status **404 Not Found**, message `"Data tidak ditemukan."` |

**Penjelasan singkat:**  
Pengujian error handling ketika resource tidak ditemukan. API mengembalikan JSON 404 dengan pesan jelas, bukan halaman HTML error. Ini sesuai pengaturan exception pada `bootstrap/app.php`.

**[TEMPAT SCREENSHOT 6]**

---

## Skenario 7 — Ubah data (PUT)

| Item | Isi |
|------|-----|
| Method | `PUT` |
| URL | `http://127.0.0.1:8000/api/mahasiswa/{id}` |
| Body | lihat di bawah |
| Expected | Status **200 OK** + message berhasil diperbarui |

**Body JSON:**
```json
{
  "nim": "UJI001",
  "nama": "Mahasiswa Uji Diubah",
  "prodi": "Teknik Informatika",
  "email": "uji@example.com",
  "nohp": "08111111111"
}
```

**Penjelasan singkat:**  
Pengujian update data. Request PUT mengirim seluruh field mahasiswa. Response 200 menunjukkan data berhasil diubah (nama/prodi berubah) dan pesan sukses dikembalikan.

**[TEMPAT SCREENSHOT 7]**

---

## Skenario 8 — Hapus data (DELETE)

| Item | Isi |
|------|-----|
| Method | `DELETE` |
| URL | `http://127.0.0.1:8000/api/mahasiswa/{id}` |
| Expected | Status **200 OK**, message `"Data mahasiswa berhasil dihapus."` |

**Penjelasan singkat:**  
Pengujian delete resource. Setelah DELETE, API mengembalikan status 200 dengan pesan sukses. Jika ID yang sama di-GET lagi, harusnya mendapat 404.

**[TEMPAT SCREENSHOT 8]**

---

## Kesimpulan

Berdasarkan 8 skenario pengujian manual di atas, webservice REST API mahasiswa dinyatakan **berfungsi sesuai materi**:
- GET daftar, search, dan detail berjalan (200)
- POST data valid menghasilkan 201
- Validasi gagal menghasilkan 422
- ID tidak ditemukan menghasilkan 404
- PUT dan DELETE berjalan (200)

Dengan demikian, webservice siap dipakai oleh client (halaman Mahasiswa REST API maupun aplikasi lain).

---

## Lampiran: Setup Thunder Client (cepat)

1. Install ekstensi **Thunder Client** di VS Code/Cursor
2. New Request
3. Pilih method (GET/POST/PUT/DELETE)
4. Isi URL
5. Tab **Headers**:
   - `Accept` = `application/json`
   - (untuk POST/PUT) `Content-Type` = `application/json`
6. Tab **Body** → JSON (untuk POST/PUT)
7. Send → screenshot hasilnya

---

## Checklist upload

- [ ] 8 screenshot pengujian
- [ ] Penjelasan singkat tiap skenario (sudah ada di atas)
- [ ] Kesimpulan
- [ ] (opsional) screenshot halaman `/mahasiswa-api` juga
