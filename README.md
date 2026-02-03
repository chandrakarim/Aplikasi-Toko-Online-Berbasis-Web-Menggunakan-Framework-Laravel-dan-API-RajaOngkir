# 📦 Aplikasi Toko Online Berbasis Web  Menggunakan Framework Laravel dan API RajaOngkir

---

## 📌 Deskripsi Sistem

Aplikasi Toko Online ini merupakan sistem informasi penjualan berbasis web yang dibangun menggunakan **Framework Laravel** dan terintegrasi dengan **API RajaOngkir** sebagai layanan penghitungan ongkos kirim.  
Sistem ini dirancang untuk membantu proses penjualan produk secara online mulai dari pemesanan, pembayaran, hingga pengiriman barang.

---

## 🎯 Tujuan Pembuatan Sistem

1. Memudahkan pelanggan dalam melakukan pembelian produk secara online.
2. Membantu admin dalam mengelola produk, transaksi, dan pelanggan.
3. Menghitung ongkos kirim secara otomatis menggunakan API RajaOngkir.
4. Mengelola batas waktu pembayaran secara otomatis.

---

## ⚙️ Fitur Utama Sistem

1. Sistem penjualan berdasarkan **harga produk dan kategori produk**.
2. Sistem **pemesanan dan konfirmasi pesanan**.
3. Informasi pembayaran **tunai (COD)** dan **transfer antar bank**.
4. **Batas waktu pembayaran 12 jam**, pesanan otomatis dibatalkan jika belum dibayar.
5. Integrasi **API RajaOngkir** sebagai jasa pengiriman produk.
6. Sistem memiliki tiga jenis pengguna: **Pengunjung, Pelanggan, dan Admin**.

---

## 👥 Jenis Pengguna dan Hak Akses

### 1️⃣ Pengunjung
- Membuka aplikasi
- Melihat daftar produk
- Melihat detail produk
- Melakukan pendaftaran akun

---

### 2️⃣ Pelanggan
- Melakukan login ke sistem
- Melakukan pemesanan produk
- Memasukkan alamat pengiriman
- Mengonfirmasi keranjang produk
- Melakukan checkout
- Melakukan pembayaran
- Melihat status pesanan
- Mengonfirmasi pesanan yang diterima
- Melakukan logout

---

### 3️⃣ Admin
- Melihat notifikasi pada dashboard
- Melihat data pelanggan
- Mengelola data produk
- Mengelola data kategori produk
- Mengelola data transaksi
- Mengelola data alamat
- Mengelola data nomor rekening pembayaran

---

## 🛠️ Teknologi yang Digunakan

- PHP
- Laravel Framework
- MySQL
- Bootstrap
- JavaScript & jQuery
- API RajaOngkir
- Composer
- Web Server (XAMPP / Laragon)

---

## 🚀 Cara Menjalankan Aplikasi (Step by Step)

### 1️⃣ Clone atau Salin Project
```bash
git clone https://github.com/username/toko-online-laravel.git
cd toko-online-laravel
```
### 2️⃣ Install Dependency Laravel
```bash
composer install
```
### 3️⃣ Konfigurasi File Environment

Salin file .env.example menjadi .env:
```bash
cp .env.example .env
```

Atur koneksi database di file .env:
```bash
DB_DATABASE=toko_online
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generate Application Key
```bash
php artisan key:generate
```

### 5️⃣ Migrasi dan Seeder Database
```bash
php artisan migrate --seed
```
### 6️⃣ Jalankan Server Laravel
```bash
php artisan serve
```

Akses aplikasi melalui browser:
```bash
http://127.0.0.1:8000
```

### 🚚 Integrasi API RajaOngkir (Step by Step)
### 1️⃣ Mendaftar API RajaOngkir
```bash
Buka website https://rajaongkir.com
```
- Daftarkan akun

- Dapatkan API Key

### 2️⃣ Simpan API Key ke File .env
```bash
RAJAONGKIR_API_KEY=apikey_anda_disini
RAJAONGKIR_BASE_URL=https://api.rajaongkir.com/starter
```

### 3. Proses Penghitungan Ongkos Kirim

#### Sistem mengambil data :

- Provinsi

- Kota tujuan

- Berat produk

- Kurir pengiriman

- Data dikirim ke API RajaOngkir

- API mengembalikan biaya ongkir

- Biaya ongkir ditampilkan pada halaman checkout

### 4. Implementasi pada Checkout

- Pelanggan memilih alamat dan kurir

- Sistem menghitung ongkir otomatis

- Total pembayaran = Harga produk + Ongkir

### ⏱️ Sistem Pembatalan Otomatis Pesanan

- Sistem memberikan batas waktu pembayaran selama 12 jam

- Jika pembayaran tidak dilakukan:

- Status pesanan berubah menjadi Dibatalkan

- Proses dijalankan menggunakan Scheduler / Cron Job Laravel


### 📝 Penutup

Aplikasi Toko Online ini diharapkan dapat membantu proses penjualan dan pengelolaan transaksi secara online dengan lebih efektif dan efisien.
Integrasi API RajaOngkir memungkinkan sistem menghitung ongkos kirim secara otomatis dan akurat.