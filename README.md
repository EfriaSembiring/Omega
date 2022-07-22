## Admin credentials
**username:** admin
**password:** admin

## Estimator credentials
**username:** estimator
**password:** estimator


  
## Menu

- Dashboard
- Daftar petugas (admin, staff gudang/barang, estimator)
- Daftar barang
- Daftar gudang
- Daftar supplier
- Barang masuk
- Barang keluar
- Barang kebutuhan proyek
- Laporan
- Profile (ganti pp & password)

## Fitur
Aplikasi ini dilengkapi dengan fitur notifikasi yang akan memudahkan staff admin dalam mengetahui update status stok bahan dan fitur pada estimator yang akan memudahkan untuk melakukan upload data estimasi bahan proyek yang dibutuhkan yang nantinya akan disesuaikan datanya dengan stok barang tersedia di gudang.
  
```
cp .env.example .env <-- edit db config
composer update
php artisan key:generate
php artisan migrate:fresh --seed
