<?php
require_once __DIR__ . '/Tool.php';

// Class untuk mengontrol tabel loans (peminjaman)
class Loan
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // READ: ambil semua peminjaman beserta nama alatnya (JOIN)
    public function getAll()
    {
        $stmt = $this->db->prepare(
            "SELECT loans.*, tools.name AS tool_name
             FROM loans
             JOIN tools ON loans.tool_id = tools.id
             ORDER BY loans.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ: ambil satu peminjaman berdasarkan id
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM loans WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // CREATE: pinjam alat (cek stok dulu, lalu kurangi stok)
    public function borrowTool($tool_id, $borrower_name)
    {
        $tool = new Tool($this->db);
        $toolData = $tool->getById($tool_id);

        // Kalau alat tidak ada atau stok habis, gagal
        if (!$toolData || $toolData['stock'] <= 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO loans (tool_id, borrower_name, loan_date, status) VALUES (?, ?, CURDATE(), 'Dipinjam')"
        );
        $stmt->execute([$tool_id, $borrower_name]);

        $tool->updateStock($tool_id, $toolData['stock'] - 1);
        return true;
    }

    // UPDATE: ubah nama peminjam dan tanggal pinjam
    public function update($id, $borrower_name, $loan_date)
    {
        $stmt = $this->db->prepare("UPDATE loans SET borrower_name = ?, loan_date = ? WHERE id = ?");
        return $stmt->execute([$borrower_name, $loan_date, $id]);
    }

    // UPDATE: kembalikan alat (status jadi Dikembalikan, stok bertambah)
    public function returnTool($id)
    {
        $loan = $this->getById($id);

        // Kalau data tidak ada atau sudah dikembalikan, gagal
        if (!$loan || $loan['status'] != 'Dipinjam') {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE loans SET status = 'Dikembalikan', return_date = CURDATE() WHERE id = ?"
        );
        $stmt->execute([$id]);

        $tool = new Tool($this->db);
        $toolData = $tool->getById($loan['tool_id']);
        $tool->updateStock($loan['tool_id'], $toolData['stock'] + 1);
        return true;
    }

    // DELETE: hapus data peminjaman
    public function delete($id)
    {
        $loan = $this->getById($id);
        if (!$loan) {
            return false;
        }

        // Kalau alat masih dipinjam, stok dikembalikan dulu
        if ($loan['status'] == 'Dipinjam') {
            $tool = new Tool($this->db);
            $toolData = $tool->getById($loan['tool_id']);
            $tool->updateStock($loan['tool_id'], $toolData['stock'] + 1);
        }

        $stmt = $this->db->prepare("DELETE FROM loans WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
