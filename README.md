# TP7DPBO2526C2

## Janji
Saya Refan Maher Aysel dengan NIM 2509129 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

## Tema
**Sistem Peminjaman Alat**, website PHP Native untuk mengelola kategori alat, data alat beserta stoknya, dan mencatat peminjaman serta pengembalian alat. Stok berkurang otomatis saat alat dipinjam dan bertambah saat dikembalikan.

## Desain Database

Database `db_peminjaman_alat` terdiri dari 3 tabel. File SQL ada di `database/db_peminjaman_alat.sql`.

| Tabel | Kolom | Keterangan |
|---|---|---|
| `categories` | `id` (PK), `name`, `description` | Kategori alat |
| `tools` | `id` (PK), `name`, `category_id` (**FK** ke `categories.id`), `stock` | Data alat dan stok tersedia |
| `loans` | `id` (PK), `tool_id` (**FK** ke `tools.id`), `borrower_name`, `loan_date`, `return_date`, `status` | Riwayat peminjaman |

Relasi: satu kategori punya banyak alat, satu alat punya banyak peminjaman.

## Desain Program

Program memakai **OOP dengan PDO** dan struktur folder sesuai modul:

| File | Peran |
|---|---|
| `index.php` | Entry point. Memuat config dan class, membuat koneksi, lalu memanggil `header`, view sesuai halaman, dan `footer`. |
| `config/db.php` (`Database`) | Membuat koneksi database memakai PDO. |
| `class/Category.php` (`Category`) | Mengontrol tabel `categories`: `getAll`, `getById`, `create`, `update`, `delete`. |
| `class/Tool.php` (`Tool`) | Mengontrol tabel `tools`: CRUD, `updateStock`, dan join ke kategori. |
| `class/Loan.php` (`Loan`) | Mengontrol tabel `loans`: CRUD, `borrowTool` (pinjam), `returnTool` (kembali), dan join ke alat. |
| `view/header.php`, `view/footer.php` | Potongan HTML yang dipakai semua halaman (menu dan penutup). |
| `view/categories.php`, `view/tools.php`, `view/loans.php` | Halaman CRUD tiap entitas: memproses form POST, memanggil class, dan menampilkan tabel. |
| `style.css` | Styling. |

Semua query memakai **prepared statement** (`prepare()` lalu `execute()`). Tidak ada `query()` langsung.

## Penjelasan Alur

1. **Browser** mengirim request ke `index.php` (contoh `index.php?page=loans`). Tanpa parameter, halaman default adalah daftar alat.
2. **`index.php`** memuat `config/db.php` dan semua class, membuat koneksi PDO, lalu meng-include `view/header.php`, view sesuai halaman, dan `view/footer.php`. Halaman yang dibuka dibatasi hanya `tools`, `categories`, dan `loans`.
3. **View** menampilkan form dan tabel. Saat tombol ditekan (Tambah, Edit, Hapus, Pinjam, Kembalikan), form dikirim lewat POST ke halaman yang sama.
4. **View memanggil method class**, misalnya `$loan->borrowTool($tool_id, $borrower_name)`.
5. **Class menjalankan query** lewat prepared statement ke database, lalu mengembalikan hasilnya berupa array PHP.
6. **Redirect**: setelah aksi selesai, view mengarahkan kembali ke halaman daftar dengan pesan status, dan tabel menampilkan data terbaru.

**Alur Pinjam** (`borrowTool`):
1. Ambil data alat, cek stok.
2. Jika stok > 0, insert ke `loans` dengan status `Dipinjam` dan `loan_date` hari ini.
3. Kurangi stok alat sebanyak 1. Jika stok 0, peminjaman ditolak.

**Alur Kembali** (`returnTool`):
1. Cek status peminjaman masih `Dipinjam`.
2. Ubah status menjadi `Dikembalikan` dan isi `return_date`.
3. Tambah stok alat sebanyak 1. Jika sudah dikembalikan, aksi ditolak sehingga stok tidak bertambah dua kali.

**Alur Hapus**:
- Hapus peminjaman yang masih `Dipinjam` akan mengembalikan stok alat terlebih dahulu.
- Kategori yang masih dipakai alat, dan alat yang sudah punya riwayat peminjaman, tidak bisa dihapus karena ditolak foreign key. Pesannya ditampilkan di halaman.

## Dokumentasi

Video program yang dijalankan 
table alat : https://drive.google.com/file/d/183YKqSA1IGb-weW1ae39FpRETmbObxbD/view?usp=sharing

table kategori : https://drive.google.com/file/d/1mMNIO6LX25UFSp5u7n1BcZ96apehbRxf/view?usp=sharing

table peminjaman : https://drive.google.com/file/d/1C7oq5t3KWZFlT4V0FWreOZwEou8EUXo7/view?usp=sharing
