<?php
// File konfigurasi koneksi database (pakai PDO)
class Database
{
    private $host = "localhost";
    private $db_name = "db_peminjaman_alat";
    private $username = "root";
    private $password = "";
    public $conn;

    // Fungsi untuk membuat koneksi ke database
    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name",
                $this->username,
                $this->password
            );
            // Supaya error database muncul sebagai exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Supaya hasil fetch berupa array asosiatif
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }

        return $this->conn;
    }
}
