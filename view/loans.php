<?php
// View untuk halaman Peminjaman (CRUD tabel loans)
$loan = new Loan($db);
$tool = new Tool($db);

// ---------- Proses aksi dari form ----------
if (isset($_POST['borrow'])) {
    $success = $loan->borrowTool($_POST['tool_id'], $_POST['borrower_name']);
    $msg = $success ? "Peminjaman berhasil dicatat" : "Gagal: stok alat habis atau alat tidak ditemukan";
    header("Location: index.php?page=loans&msg=" . urlencode($msg));
    exit;
}

if (isset($_POST['update'])) {
    $loan->update($_POST['id'], $_POST['borrower_name'], $_POST['loan_date']);
    header("Location: index.php?page=loans&msg=" . urlencode("Data peminjaman berhasil diubah"));
    exit;
}

if (isset($_POST['return'])) {
    $success = $loan->returnTool($_POST['id']);
    $msg = $success ? "Alat berhasil dikembalikan" : "Gagal: alat sudah dikembalikan";
    header("Location: index.php?page=loans&msg=" . urlencode($msg));
    exit;
}

if (isset($_POST['delete'])) {
    $loan->delete($_POST['id']);
    header("Location: index.php?page=loans&msg=" . urlencode("Data peminjaman berhasil dihapus"));
    exit;
}

// ---------- Ambil data untuk ditampilkan ----------
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $loan->getById($_GET['edit']);
}
$loans = $loan->getAll();
$tools = $tool->getAll();
?>

<h2>Daftar Peminjaman</h2>

<?php if (isset($_GET['msg'])) { ?>
    <p class="message"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php } ?>

<!-- Form pinjam / ubah -->
<form method="POST" action="index.php?page=loans" class="form">
    <?php if ($editData) { ?>
        <h3>Ubah Data Peminjaman</h3>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">

        <label>Nama Peminjam</label>
        <input type="text" name="borrower_name" required value="<?= htmlspecialchars($editData['borrower_name']) ?>">

        <label>Tanggal Pinjam</label>
        <input type="date" name="loan_date" required value="<?= $editData['loan_date'] ?>">

        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="index.php?page=loans">Batal</a>
    <?php } else { ?>
        <h3>Pinjam Alat</h3>

        <label>Nama Peminjam</label>
        <input type="text" name="borrower_name" required>

        <label>Alat</label>
        <select name="tool_id" required>
            <option value="">-- Pilih Alat --</option>
            <?php foreach ($tools as $t) { ?>
                <option value="<?= $t['id'] ?>">
                    <?= htmlspecialchars($t['name']) ?> (stok: <?= $t['stock'] ?>)
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="borrow">Pinjam</button>
    <?php } ?>
</form>

<!-- Tabel data -->
<table>
    <tr>
        <th>ID</th>
        <th>Alat</th>
        <th>Peminjam</th>
        <th>Tgl Pinjam</th>
        <th>Tgl Kembali</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($loans as $row) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['tool_name']) ?></td>
            <td><?= htmlspecialchars($row['borrower_name']) ?></td>
            <td><?= $row['loan_date'] ?></td>
            <td><?= $row['return_date'] ? $row['return_date'] : '-' ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="index.php?page=loans&edit=<?= $row['id'] ?>">Edit</a>

                <?php if ($row['status'] == 'Dipinjam') { ?>
                    <form method="POST" action="index.php?page=loans" class="inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="return">Kembalikan</button>
                    </form>
                <?php } ?>

                <form method="POST" action="index.php?page=loans" class="inline"
                      onsubmit="return confirm('Yakin hapus?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete">Hapus</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
