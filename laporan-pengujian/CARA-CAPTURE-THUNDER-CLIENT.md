# Cara Capture Pengujian Manual (Thunder Client) — Ikuti Ini

Ini yang diminta dosen: **uji manual + screenshot**.

## 0. Persiapan

1. Pastikan server jalan:
   ```powershell
   php artisan serve
   ```
2. Install ekstensi **Thunder Client** di Cursor/VS Code  
   (Extensions → cari `Thunder Client` → Install)
3. Buka Thunder Client dari sidebar kiri (ikon petir)

---

## 1. GET daftar (Screenshot 1)

1. New Request
2. Method: **GET**
3. URL: `http://127.0.0.1:8000/api/mahasiswa?per_page=2`
4. Headers → tambah:
   - `Accept` = `application/json`
5. Klik **Send**
6. Screenshot: URL + status **200** + JSON `data/links/meta`

---

## 2. GET search (Screenshot 2)

1. Method: **GET**
2. URL: `http://127.0.0.1:8000/api/mahasiswa?search=andi`
3. Header `Accept: application/json`
4. Send → harus **200**, data Andi muncul
5. Screenshot

---

## 3. POST valid (Screenshot 3)

1. Method: **POST**
2. URL: `http://127.0.0.1:8000/api/mahasiswa`
3. Headers:
   - `Accept` = `application/json`
   - `Content-Type` = `application/json`
4. Body → JSON:
```json
{
  "nim": "UJI001",
  "nama": "Mahasiswa Uji",
  "prodi": "Sistem Informasi",
  "email": "uji@example.com",
  "nohp": "08111111111"
}
```
5. Send → harus **201**
6. **Catat `id`** dari response (contoh: 53)
7. Screenshot

---

## 4. POST invalid / 422 (Screenshot 4)

1. Method: **POST**
2. URL sama: `http://127.0.0.1:8000/api/mahasiswa`
3. Body:
```json
{
  "nim": "240001",
  "email": "bukan-email"
}
```
4. Send → harus **422** + object `errors`
5. Screenshot

---

## 5. GET detail (Screenshot 5)

1. Method: **GET**
2. URL: `http://127.0.0.1:8000/api/mahasiswa/ID`  
   (ganti ID dari langkah 3)
3. Send → **200**
4. Screenshot

---

## 6. GET 404 (Screenshot 6)

1. Method: **GET**
2. URL: `http://127.0.0.1:8000/api/mahasiswa/99999`
3. Send → **404** + `"Data tidak ditemukan."`
4. Screenshot

---

## 7. PUT update (Screenshot 7)

1. Method: **PUT**
2. URL: `http://127.0.0.1:8000/api/mahasiswa/ID`
3. Headers: `Accept` + `Content-Type` application/json
4. Body:
```json
{
  "nim": "UJI001",
  "nama": "Mahasiswa Uji Diubah",
  "prodi": "Teknik Informatika",
  "email": "uji@example.com",
  "nohp": "08111111111"
}
```
5. Send → **200**
6. Screenshot

---

## 8. DELETE (Screenshot 8)

1. Method: **DELETE**
2. URL: `http://127.0.0.1:8000/api/mahasiswa/ID`
3. Header `Accept: application/json`
4. Send → **200** + `"Data mahasiswa berhasil dihapus."`
5. Screenshot

---

## Susun file upload

Buat dokumen Word/PDF berisi:
1. Judul + nama/NIM
2. 8 screenshot (urut)
3. Di bawah tiap screenshot: **salin penjelasan** dari `LAPORAN-PENGUJIAN-MANUAL.md`
4. Kesimpulan (sudah ada di laporan)

Selesai.
