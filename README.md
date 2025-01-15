# Web Absensi Guru dengan Geolokasi 🌍📋

Aplikasi **Web Absensi Guru** ini dibangun menggunakan **Laravel 11** dan berfungsi untuk mencatat kehadiran guru berdasarkan data lokasi geografis mereka. Aplikasi ini dirancang untuk memastikan kehadiran guru dapat divalidasi secara akurat berdasarkan lokasi yang terdaftar, dengan antarmuka yang user-friendly dan mudah digunakan.

---

## 🎯 **Fitur Utama**

1. **Autentikasi Guru**  
   - Login dan registrasi untuk setiap guru menggunakan email dan kata sandi.  
   
2. **Absensi Berbasis Geolokasi**  
   - Memastikan kehadiran hanya dapat dilakukan jika guru berada di lokasi yang telah ditentukan.  
   - Integrasi dengan API Geolocation untuk memvalidasi posisi pengguna.  

3. **Dashboard Admin**  
   - Kelola data guru, lokasi terdaftar, dan laporan kehadiran secara real-time.  
   - Visualisasi data kehadiran dengan grafik dan statistik.  

4. **Laporan Kehadiran**  
   - Cetak laporan bulanan kehadiran guru dalam format PDF.  
   - Filter data berdasarkan nama, tanggal, atau lokasi.  

5. **Notifikasi Otomatis**  
   - Kirim pengingat melalui email jika guru terlambat atau tidak hadir.

---

## 🛠️ **Teknologi yang Digunakan**

- **Backend**: Laravel 11, PHP 8.2  
- **Frontend**: Blade Templating, Bootstrap 5  
- **Database**: MySQL  
- **API**: Google Maps Geolocation API  
- **Libraries**: Laravel Sanctum, Spatie Permissions  
- **Deployment**: Docker, Nginx  

---

## 🚀 **Cara Menginstal dan Menjalankan Aplikasi**

1. **Clone Repository**  
   ```bash
   git clone https://github.com/username/web-absensi-guru.git
   cd web-absensi-guru
