<?php
// Class untuk mengontrol tabel categories
class Category
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // READ: ambil semua kategori
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ: ambil satu kategori berdasarkan id
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // CREATE: tambah kategori baru
    public function create($name, $description)
    {
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }

    // UPDATE: ubah data kategori
    public function update($id, $name, $description)
    {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    // DELETE: hapus kategori
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
