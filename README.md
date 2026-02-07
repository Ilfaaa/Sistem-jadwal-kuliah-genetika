<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Cara Penggunaan

Laravel 8, PHP 7

Pada algoritma genetika, terdapat individu, kromosom dan gen.

Individu disini yaitu jadwal perkuliahan utuh.
Kromosom disini yaitu jadwal perkelasnya yang ada disebuah jadwal.
Gen disini yaitu unit yang memebentuk kelas, seperti gen kuliah, gen ruang dan gen waktu, namun disini gen kuliah dan gen waktu dipecah lagi menjadi subgen-subgen, pada gen kuliah terdapat subgen matkul, dosen dan kelas; pada gen waktu terdapat subgen hari dan jam; 
Nantinya saat proses generate jadwal, gen ruang dan waktu akan diganti-ganti sedemikian rupa berdasarkan proses algoritma genetika agar menghasilkan individu/jadwal yang bebas bentrok, baik itu bentrok 1 dosen mengajar dilebih satu kelas di waktu yang sama, maupun bentrok ruangan yang digunakan oleh lebih dari satu kelas diwaktu yang sama.

- Admin : melakukan tambah, ubah atau hapus data kuliah, matkul, dosen, prodi, ruang, waktu, hari, jam, dan juga melakukan manage users yang menggunakan aplikasi, dan menerima request dari operator yang menggunakan aplikasi. Yang terpenting admin yang dapat melakukan generate jadwal perkuliahan. USERNAME = admin, EMAIL = admin@gmail.com, PASSWORD = 12345678
- Operator : juga melakukan tambah, ubah dan hapus data yang ada, namun hanya berupa request, nantinya request akan diterima admin, dan admin memutuskan menerapkan request atau tidak. Operator tidak dapat melakukan generate jadwal perkuliahan. USERNAME = operatorinformatika, EMAIL = operatorinformatika@gmail.com, PASSWORD = 12345678

