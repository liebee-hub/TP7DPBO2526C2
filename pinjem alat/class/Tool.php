<?php
// Class untuk mengontrol tabel tools (alat)
class Tool
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // READ: ambil semua alat beserta nama kategorinya (JOIN)
    public function getAll()
    {
        $stmt = $this->db->prepare(
            "SELECT tools.*, categories.name AS category_name
             FROM tools
             JOIN categories ON tools.category_id = categories.id
             ORDER BY tools.id ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ: ambil satu alat berdasarkan id
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tools WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // CREATE: tambah alat baru
    public function create($name, $category_id, $stock)
    {
        $stmt = $this->db->prepare("INSERT INTO tools (name, category_id, stock) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $category_id, $stock]);
    }

    // UPDATE: ubah data alat
    public function update($id, $name, $category_id, $stock)
    {
        $stmt = $this->db->prepare("UPDATE tools SET name = ?, category_id = ?, stock = ? WHERE id = ?");
        return $stmt->execute([$name, $category_id, $stock, $id]);
    }

    // UPDATE: ubah stok saja (dipakai saat pinjam / kembali)
    public function updateStock($id, $newStock)
    {
        $stmt = $this->db->prepare("UPDATE tools SET stock = ? WHERE id = ?");
        return $stmt->execute([$newStock, $id]);
    }

    // DELETE: hapus alat
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tools WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
