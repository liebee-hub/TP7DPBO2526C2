<?php
// Titik awal aplikasi: index.php
// Tugasnya: koneksi database, load class, lalu memanggil view sesuai halaman

require_once 'config/db.php';
require_once 'class/Category.php';
require_once 'class/Tool.php';
require_once 'class/Loan.php';

// Buat koneksi database (dipakai oleh semua view)
$database = new Database();
$db = $database->connect();

// Tentukan halaman yang dibuka, defaultnya daftar alat
$page = isset($_GET['page']) ? $_GET['page'] : 'tools';

// Hanya halaman ini yang boleh dibuka
$allowedPages = ['tools', 'categories', 'loans'];
if (!in_array($page, $allowedPages)) {
    $page = 'tools';
}

// Aksi POST di dalam view memakai header() untuk redirect,
// jadi output ditahan dulu dengan ob_start()
ob_start();
include 'view/header.php';
include 'view/' . $page . '.php';
include 'view/footer.php';
ob_end_flush();
