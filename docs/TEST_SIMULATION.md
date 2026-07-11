# HeliosCargo — Simulasi Test End-to-End

Dokumen ini berisi **2 skenario simulasi test** yang mencakup semua aktor dalam sistem HeliosCargo, dari awal hingga akhir. Setiap skenario dirancang agar bisa dijalankan secara manual melalui browser.

---

## Daftar Aktor

| No | Aktor | Role | Deskripsi |
|----|-------|------|-----------|
| 1 | **Pengunjung Publik** | - (tanpa login) | Akses halaman landing, cek tarif, lacak paket, kirim pesan kontak |
| 2 | **Operator / Staff** | `admin` | Login, kelola pelanggan, shipment, outlet, manifest, scan, cetak resi |
| 3 | **Super Admin** | `superadmin` | Semua fitur Operator + kelola user, laporan, pengaturan perusahaan, promo, berita |

---

## Prasyarat Sebelum Testing

- Pastikan database sudah di-migrate dan memiliki data awal (seeder).
- Pastikan ada minimal **1 akun Super Admin** dan **1 akun Operator** di tabel `users`.
- Pastikan tabel `services`, `outlets`, dan `locations` sudah terisi data.
- Aplikasi berjalan di `http://localhost/helioscargo/public/` atau sesuai konfigurasi.

---

## SKENARIO 1: Alur Pengiriman Paket Domestik (Jakarta → Surabaya)

> **Tujuan:** Menguji alur lengkap dari pembuatan data pelanggan, pengiriman paket, pembuatan manifest, update tracking, hingga pengecekan oleh publik.

### FASE A — Super Admin: Setup Awal

#### A1. Login sebagai Super Admin
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/login` | Halaman login tampil |
| 2 | Masukkan username & password Super Admin | - |
| 3 | Klik tombol **Login** | Redirect ke `/dashboard`, nama user tampil di sidebar |

#### A2. Tambah User Operator Baru
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Users** di sidebar | Halaman daftar user tampil |
| 2 | Klik tombol **Tambah User** | Modal form tambah user muncul |
| 3 | Isi data: Username=`operator_jkt`, Nama Lengkap=`Operator Jakarta`, Role=`admin`, Password=`Op3rator!`, Outlet=*(pilih outlet Jakarta)*, Status=Aktif | - |
| 4 | Klik **Simpan** | Notifikasi sukses, user baru muncul di tabel |

#### A3. Tambah Outlet
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Outlet** di sidebar | Halaman daftar outlet tampil |
| 2 | Klik **Tambah Outlet** | Modal form muncul |
| 3 | Isi data: Nama=`Hub Surabaya`, Tipe=`hub`, Alamat=`Jl. Raya Darmo 100`, Kota=`Surabaya`, Telp=`031-555-1234` | - |
| 4 | Klik **Simpan** | Notifikasi sukses, outlet baru muncul di daftar |

#### A4. Kelola Promo
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Promo** di sidebar | Halaman promo tampil |
| 2 | Klik **Tambah Promo** | Modal form muncul |
| 3 | Isi judul=`Diskon Akhir Tahun`, deskripsi, upload gambar, set tanggal aktif | - |
| 4 | Klik **Simpan** | Promo baru tampil di daftar |

#### A5. Kelola Berita
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **News** di sidebar | Halaman berita tampil |
| 2 | Klik **Tambah Berita** | Modal form muncul |
| 3 | Isi judul=`Layanan Baru ke Kalimantan`, konten, upload gambar | - |
| 4 | Klik **Simpan** | Berita baru tampil di daftar |

#### A6. Update Pengaturan Perusahaan
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Settings** di sidebar | Halaman settings tampil |
| 2 | Ubah nama perusahaan menjadi `HeliosCargo Indonesia`, update alamat, telepon | - |
| 3 | Klik **Simpan** | Notifikasi sukses, data tersimpan |

#### A7. Logout Super Admin
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Logout** | Session dihapus, redirect ke `/login` |

---

### FASE B — Operator: Proses Pengiriman

#### B1. Login sebagai Operator
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/login` | Halaman login tampil |
| 2 | Login dengan `operator_jkt` / `Op3rator!` | Redirect ke `/dashboard` |

#### B2. Tambah Pelanggan (Pengirim & Penerima)
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Pelanggan** | Halaman data pelanggan tampil |
| 2 | Klik **Tambah Pelanggan** | Modal form muncul |
| 3 | Isi data pengirim: Nama=`Andi Wijaya`, Telp=`0812-3456-7890`, Alamat=`Jl. Sudirman No. 10, Jakarta` | - |
| 4 | Klik **Simpan** | Pelanggan pengirim muncul di tabel |
| 5 | Ulangi langkah 2-4 untuk penerima: Nama=`Budi Santoso`, Telp=`0813-9876-5432`, Alamat=`Jl. Basuki Rahmat 50, Surabaya` | Pelanggan penerima muncul di tabel |

#### B3. Buat Shipment Baru
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Shipment** | Halaman daftar shipment tampil |
| 2 | Klik **Tambah Shipment** | Modal form besar muncul |
| 3 | Pilih Pengirim = `Andi Wijaya` | - |
| 4 | Pilih Penerima = `Budi Santoso` | - |
| 5 | Cari & pilih Lokasi Asal = `Jakarta` (gunakan Select2) | - |
| 6 | Cari & pilih Lokasi Tujuan = `Surabaya` (gunakan Select2) | - |
| 7 | Pilih Service = `Regular` | - |
| 8 | Isi: Nama Barang=`Laptop Asus`, Deskripsi=`Laptop 14 inch`, Qty=`1`, Berat=`2.5`, Panjang=`40`, Lebar=`30`, Tinggi=`10` | - |
| 9 | Tunggu ongkir otomatis terhitung | Field Ongkir terisi otomatis via AJAX |
| 10 | Centang **Fragile** | - |
| 11 | Pilih Pickup Outlet = *(outlet Jakarta)*, Delivery Outlet = `Hub Surabaya` | - |
| 12 | Isi Estimated Delivery, Payment Status = `paid` | - |
| 13 | Klik **Simpan Shipment** | Notifikasi sukses, AWB ter-generate otomatis, shipment muncul di tabel |

#### B4. Lihat Detail & Cetak Resi
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik tombol **Detail** pada shipment yang baru dibuat | Halaman detail shipment tampil lengkap |
| 2 | Catat nomor **AWB** yang ter-generate | AWB format unik tampil |
| 3 | Klik tombol **Cetak Resi** | File PDF resi ter-download/terbuka, berisi data pengirim, penerima, AWB, barcode |

#### B5. Edit Shipment
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Kembali ke daftar, klik **Edit** pada shipment | Halaman edit muncul dengan data terisi |
| 2 | Ubah Qty menjadi `2`, berat menjadi `5.0` | - |
| 3 | Klik **Update** | Notifikasi sukses, data di tabel terupdate |

#### B6. Buat Manifest
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Manifest** | Halaman manifest tampil |
| 2 | Klik **Buat Manifest** | Modal form muncul |
| 3 | Pilih Outlet Asal = *(outlet Jakarta)* | Daftar shipment dimuat via AJAX |
| 4 | Pilih Tujuan Hub = `Hub Surabaya` | - |
| 5 | Isi Driver = `Pak Joko`, Kendaraan = `B 1234 XYZ` | - |
| 6 | Centang shipment `Laptop Asus` dari daftar | Counter "Dipilih: 1 shipment" terupdate |
| 7 | Klik **Buat Manifest** | Notifikasi sukses, manifest baru muncul di tabel dengan status `draft` |

#### B7. Update Status Manifest
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Update Status** pada manifest | Modal update status muncul |
| 2 | Ubah status menjadi `In Transit` | - |
| 3 | Klik **Update** | Badge status berubah menjadi `In Transit` (biru) |

#### B8. Update Tracking Shipment
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Shipment Tracking** | Halaman tracking tampil |
| 2 | Cari shipment berdasarkan AWB | Shipment ditemukan |
| 3 | Update status ke `in_transit`, tambah lokasi/catatan | - |
| 4 | Klik **Update** | Status tracking terupdate |

#### B9. Scan AWB
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Scan** | Halaman scanner tampil |
| 2 | Masukkan/scan nomor AWB | - |
| 3 | Klik **Proses** | Data shipment terkait tampil/terupdate |

#### B10. Update Profil Operator
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Settings** | Halaman pengaturan tampil |
| 2 | Ubah nama lengkap menjadi `Operator Jakarta Pusat` | - |
| 3 | Klik **Simpan** | Notifikasi sukses, nama di sidebar/header terupdate |

#### B11. Logout Operator
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Logout** | Session dihapus, redirect ke `/login` |

---

### FASE C — Pengunjung Publik: Cek Paket

#### C1. Akses Landing Page
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/` (homepage) | Landing page tampil dengan info perusahaan, promo, berita |
| 2 | Verifikasi promo `Diskon Akhir Tahun` terlihat | Promo tampil di section yang sesuai |
| 3 | Verifikasi berita `Layanan Baru ke Kalimantan` terlihat | Berita tampil |

#### C2. Lacak Paket via Form
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Di halaman utama, masukkan AWB shipment dari Fase B ke form tracking | - |
| 2 | Klik **Lacak** | Halaman hasil tracking tampil |
| 3 | Verifikasi status terakhir = `in_transit` | Status dan riwayat tracking tampil lengkap |

#### C3. Lacak Paket via URL Langsung
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/tracking/{AWB}` langsung di browser | Halaman tracking tampil langsung tanpa perlu submit form |

#### C4. Cek Tarif / Ongkir
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Di halaman utama, cari section **Cek Tarif** | Form cek tarif tampil |
| 2 | Pilih lokasi asal = Jakarta, tujuan = Surabaya, berat = 3 kg | - |
| 3 | Klik **Cek Tarif** | Estimasi biaya kirim tampil untuk berbagai service |

#### C5. Kirim Pesan Kontak
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Scroll ke section **Kontak** di landing page | Form kontak tampil |
| 2 | Isi Nama=`Calon Pelanggan`, Email=`test@email.com`, Pesan=`Saya mau tanya soal pengiriman ke Kalimantan` | - |
| 3 | Klik **Kirim** | Notifikasi pesan berhasil dikirim |

#### C6. Coba Akses Halaman Terproteksi Tanpa Login
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/dashboard` tanpa login | Redirect ke `/login` |
| 2 | Buka `/shipment` tanpa login | Redirect ke `/login` |
| 3 | Buka `/users` tanpa login | Redirect ke `/login` |

---

### FASE D — Super Admin: Laporan & Verifikasi Akhir

#### D1. Login Kembali sebagai Super Admin
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Login dengan akun Super Admin | Redirect ke `/dashboard` |

#### D2. Cek Laporan
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Laporan** | Halaman laporan tampil |
| 2 | Verifikasi shipment yang dibuat di Fase B muncul di laporan | Data shipment terlihat |
| 3 | Klik **Export** | File laporan ter-download (Excel/CSV) |

#### D3. Hapus Data Test
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Shipment**, klik **Hapus** pada shipment test | Konfirmasi muncul, setelah OK shipment terhapus |
| 2 | Buka **Users**, hapus user `operator_jkt` | User terhapus dari daftar |
| 3 | Buka **Outlet**, hapus outlet `Hub Surabaya` | Outlet terhapus |

#### D4. Logout
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Logout** | Session dihapus, redirect ke `/login` |

---

**✅ SKENARIO 1 SELESAI** — Semua aktor (Super Admin, Operator, Publik) telah diuji dari awal sampai akhir.

---
---

## SKENARIO 2: Alur Pengiriman COD dengan Multiple Paket (Bandung → Medan)

> **Tujuan:** Menguji alur COD, pembuatan manifest dengan banyak paket, update tracking bertahap, serta fitur edit/delete dan validasi akses role.

### FASE A — Super Admin: Persiapan Infrastruktur

#### A1. Login Super Admin
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/login`, masukkan kredensial Super Admin | Redirect ke `/dashboard` |

#### A2. Buat Outlet Bandung & Hub Medan
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Outlet** → **Tambah Outlet** | Modal form muncul |
| 2 | Isi: Nama=`Agen Bandung`, Tipe=`agent`, Alamat=`Jl. Braga 45`, Kota=`Bandung`, Telp=`022-111-2222` | - |
| 3 | Klik **Simpan** | Outlet `Agen Bandung` muncul di daftar |
| 4 | Tambah lagi: Nama=`Hub Medan`, Tipe=`hub`, Alamat=`Jl. Gatot Subroto 88`, Kota=`Medan`, Telp=`061-333-4444` | - |
| 5 | Klik **Simpan** | Outlet `Hub Medan` muncul di daftar |

#### A3. Buat User Operator Bandung
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Users** → **Tambah User** | Modal form muncul |
| 2 | Isi: Username=`operator_bdg`, Nama=`Operator Bandung`, Role=`admin`, Password=`Bdg2026!`, Outlet=`Agen Bandung`, Status=Aktif | - |
| 3 | Klik **Simpan** | User baru tampil di daftar |

#### A4. Edit Outlet yang Sudah Ada
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Di daftar outlet, klik **Edit** pada `Agen Bandung` | Halaman edit outlet tampil |
| 2 | Ubah nomor telepon menjadi `022-111-3333` | - |
| 3 | Klik **Update** | Notifikasi sukses, data terupdate |

#### A5. Tambah Promo & Berita Baru
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Tambah promo: Judul=`Gratis Asuransi COD`, set tanggal | Promo tersimpan |
| 2 | Tambah berita: Judul=`Rute Baru Bandung-Medan Dibuka` | Berita tersimpan |

#### A6. Update Pengaturan Perusahaan
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Settings**, ubah email perusahaan menjadi `info@helioscargo.id` | - |
| 2 | Klik **Simpan** | Data tersimpan |

#### A7. Cek Dashboard
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik menu **Dashboard** | Halaman dashboard tampil |
| 2 | Verifikasi statistik (total shipment, pelanggan, dll.) tampil | Angka statistik sesuai data |

#### A8. Logout
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Logout** | Redirect ke `/login` |

---

### FASE B — Operator Bandung: Kirim 3 Paket COD

#### B1. Login Operator
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Login dengan `operator_bdg` / `Bdg2026!` | Redirect ke `/dashboard` |
| 2 | Verifikasi nama `Operator Bandung` tampil di sidebar | - |

#### B2. Tambah 2 Pelanggan
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Pelanggan** → **Tambah Pelanggan** | - |
| 2 | Pengirim: Nama=`Diana Putri`, Telp=`0821-1111-2222`, Alamat=`Jl. Dago 15, Bandung` | Tersimpan |
| 3 | Penerima: Nama=`Rizky Harahap`, Telp=`0852-3333-4444`, Alamat=`Jl. SM Raja 200, Medan` | Tersimpan |

#### B3. Buat 3 Shipment (COD)
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Shipment** → **Tambah Shipment** | Modal form muncul |
| 2 | **Paket 1:** Pengirim=`Diana Putri`, Penerima=`Rizky Harahap`, Barang=`Sepatu Nike`, Qty=2, Berat=1.5kg, Dimensi=35x25x15, Service=Regular, Pickup=`Agen Bandung`, Delivery=`Hub Medan`, Payment=`cod`, COD Amount=`850000` | - |
| 3 | Klik **Simpan** | AWB ter-generate, shipment #1 muncul |
| 4 | **Paket 2:** Barang=`Tas Ransel`, Qty=1, Berat=0.8kg, Dimensi=45x30x20, Payment=`cod`, COD Amount=`450000` | - |
| 5 | Klik **Simpan** | Shipment #2 muncul |
| 6 | **Paket 3:** Barang=`Jaket Kulit`, Qty=1, Berat=1.2kg, Dimensi=40x30x10, Fragile=No, Payment=`cod`, COD Amount=`1200000` | - |
| 7 | Klik **Simpan** | Shipment #3 muncul |
| 8 | Verifikasi ketiga shipment ada di tabel | Status semua `draft`/`booked` |

#### B4. Edit Salah Satu Shipment
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Edit** pada Paket 2 (`Tas Ransel`) | Halaman edit tampil |
| 2 | Ubah berat menjadi `1.0kg`, COD Amount menjadi `500000` | - |
| 3 | Klik **Update** | Data terupdate di daftar |

#### B5. Cetak Resi untuk 3 Paket
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Detail** Paket 1 → **Cetak Resi** | PDF resi ter-download |
| 2 | Ulangi untuk Paket 2 dan 3 | Masing-masing PDF unik sesuai AWB |
| 3 | Catat ketiga nomor AWB | AWB #1, AWB #2, AWB #3 |

#### B6. Buat Manifest dengan 3 Paket Sekaligus
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Manifest** → **Buat Manifest** | Modal form muncul |
| 2 | Outlet Asal = `Agen Bandung` | Daftar 3 shipment dimuat via AJAX |
| 3 | Tujuan Hub = `Hub Medan` | - |
| 4 | Driver = `Pak Rahmat`, Kendaraan = `D 5678 ABC` | - |
| 5 | Centang **Check All** untuk memilih 3 paket | Counter: "Dipilih: 3 shipment", total berat terhitung |
| 6 | Klik **Buat Manifest** | Manifest baru muncul, status `draft`, Total Paket = 3 |

#### B7. Update Status Manifest Bertahap
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Update Status** → pilih `In Transit` → **Update** | Badge berubah jadi `In Transit` |
| 2 | Klik **Detail** pada manifest | Halaman detail manifest tampil, daftar 3 shipment terlihat |
| 3 | Kembali, klik **Update Status** → pilih `Arrived` → **Update** | Badge berubah jadi `Arrived` |
| 4 | Update lagi ke `Processed` | Badge berubah jadi `Processed` |

#### B8. Update Tracking Bertahap
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Shipment Tracking** | Halaman tracking tampil |
| 2 | Update Paket 1: status `in_transit`, catatan `Paket dalam perjalanan ke Medan` | Tracking terupdate |
| 3 | Update Paket 1 lagi: status `delivered`, catatan `Diterima oleh Rizky` | Status berubah |
| 4 | Update Paket 2 & 3 ke `delivered` | Semua paket status `delivered` |

#### B9. Scan AWB
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Scan**, masukkan AWB Paket 3 | - |
| 2 | Klik **Proses** | Data paket tampil/terupdate |

#### B10. Hapus Shipment (Test Delete)
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Shipment**, klik **Hapus** pada Paket 3 | Konfirmasi muncul |
| 2 | Klik **OK** | Paket 3 terhapus dari daftar |
| 3 | Verifikasi hanya 2 shipment tersisa | Tabel menampilkan 2 baris |

#### B11. Cari Shipment
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Ketik `Sepatu` di kotak pencarian | Hanya Paket 1 yang tampil |
| 2 | Hapus pencarian | Semua shipment tampil kembali |

#### B12. Logout Operator
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Logout** | Redirect ke `/login` |

---

### FASE C — Pengunjung Publik: Verifikasi & Validasi

#### C1. Lacak Ketiga Paket
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/`, masukkan AWB Paket 1 di form tracking | - |
| 2 | Klik **Lacak** | Status `delivered`, riwayat tracking lengkap tampil |
| 3 | Lacak AWB Paket 2 | Status `delivered` |
| 4 | Lacak AWB Paket 3 (yang sudah dihapus) | Pesan "Data tidak ditemukan" atau sejenis |

#### C2. Lacak via URL
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka `/tracking/{AWB_Paket_1}` | Detail tracking tampil langsung |
| 2 | Buka `/tracking/AWB-TIDAK-ADA-12345` | Pesan error / tidak ditemukan |

#### C3. Cek Tarif Bandung-Medan
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka form cek tarif di landing page | Form tampil |
| 2 | Asal=Bandung, Tujuan=Medan, Berat=2kg | - |
| 3 | Klik **Cek Tarif** | Harga estimasi tampil |

#### C4. Cek Promo & Berita di Landing Page
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Scroll landing page, cari promo `Gratis Asuransi COD` | Promo tampil |
| 2 | Cari berita `Rute Baru Bandung-Medan Dibuka` | Berita tampil |

#### C5. Validasi Akses Role (Negatif Test)
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Login sebagai `operator_bdg` | Berhasil masuk |
| 2 | Akses `/users` (fitur Super Admin) | **Ditolak** — redirect atau pesan error forbidden |
| 3 | Akses `/laporan` | **Ditolak** |
| 4 | Akses `/promo` | **Ditolak** |
| 5 | Akses `/news` | **Ditolak** |
| 6 | Logout | Redirect ke `/login` |

---

### FASE D — Super Admin: Laporan & Cleanup

#### D1. Login Super Admin
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Login Super Admin | Redirect ke `/dashboard` |

#### D2. Cek & Export Laporan
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Laporan** | Data shipment dari Skenario 2 tampil |
| 2 | Verifikasi 2 shipment (Paket 1 & 2) ada, Paket 3 tidak ada (sudah dihapus) | Sesuai |
| 3 | Klik **Export** | File laporan ter-download |

#### D3. Edit User Operator
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Users**, klik **Edit** pada `operator_bdg` | Halaman edit user tampil |
| 2 | Ubah nama menjadi `Operator Bandung Utara` | - |
| 3 | Klik **Update** | Data terupdate |

#### D4. Edit Promo yang Ada
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Promo**, klik **Edit** pada `Gratis Asuransi COD` | Form edit muncul |
| 2 | Ubah judul menjadi `Gratis Asuransi COD — Diperpanjang!` | - |
| 3 | Klik **Update** | Promo terupdate |

#### D5. Hapus Berita
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **News**, klik **Hapus** pada berita test | Konfirmasi muncul |
| 2 | Klik **OK** | Berita terhapus |

#### D6. Cleanup Semua Data Test
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Hapus shipment yang tersisa | Terhapus |
| 2 | Hapus pelanggan `Diana Putri` & `Rizky Harahap` | Terhapus |
| 3 | Hapus user `operator_bdg` | Terhapus |
| 4 | Hapus outlet `Agen Bandung` & `Hub Medan` | Terhapus |
| 5 | Hapus promo test | Terhapus |

#### D7. Update Profil Super Admin
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Buka **Settings**, ubah password | - |
| 2 | Klik **Simpan** | Notifikasi sukses |

#### D8. Logout
| Langkah | Aksi | Hasil yang Diharapkan |
|---------|------|-----------------------|
| 1 | Klik **Logout** | Session dihapus, redirect ke `/login` |

---

**✅ SKENARIO 2 SELESAI** — Semua aktor telah diuji termasuk alur COD, multiple paket, validasi role, serta operasi CRUD lengkap.

---

## Ringkasan Cakupan Test

| Fitur | Skenario 1 | Skenario 2 |
|-------|:----------:|:----------:|
| Login / Logout | ✅ | ✅ |
| Dashboard | ✅ | ✅ |
| Kelola User (CRUD) | ✅ | ✅ |
| Kelola Outlet (CRUD) | ✅ | ✅ |
| Kelola Pelanggan (CRUD) | ✅ | ✅ |
| Buat Shipment | ✅ | ✅ |
| Edit Shipment | ✅ | ✅ |
| Hapus Shipment | ✅ | ✅ |
| Detail & Cetak Resi PDF | ✅ | ✅ |
| Hitung Ongkir Otomatis | ✅ | ✅ |
| Buat Manifest | ✅ | ✅ |
| Update Status Manifest | ✅ | ✅ (bertahap) |
| Detail Manifest | — | ✅ |
| Update Tracking | ✅ | ✅ (bertahap) |
| Scan AWB | ✅ | ✅ |
| Pencarian Data | — | ✅ |
| Lacak Paket (publik) | ✅ | ✅ |
| Lacak via URL | ✅ | ✅ |
| Cek Tarif (publik) | ✅ | ✅ |
| Kontak (publik) | ✅ | — |
| Kelola Promo | ✅ | ✅ |
| Kelola Berita | ✅ | ✅ |
| Pengaturan Perusahaan | ✅ | ✅ |
| Pengaturan Profil | ✅ | ✅ |
| Laporan & Export | ✅ | ✅ |
| Validasi Akses Role | ✅ (akses tanpa login) | ✅ (operator akses fitur superadmin) |
| Payment COD | — | ✅ |
| Multiple Paket di Manifest | — | ✅ |
| Negative Test (AWB tidak ada) | — | ✅ |

---

> **Catatan:** Jalankan Skenario 1 terlebih dahulu sebelum Skenario 2. Pastikan cleanup data dilakukan di akhir setiap skenario agar tidak mengganggu data production.