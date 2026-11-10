<?php
// View untuk halaman Alat (CRUD tabel tools)
$tool = new Tool($db);
$category = new Category($db);

// ---------- Proses aksi dari form ----------
if (isset($_POST['add'])) {
    $tool->create($_POST['name'], $_POST['category_id'], $_POST['stock']);
    header("Location: index.php?page=tools&msg=" . urlencode("Alat berhasil ditambah"));
    exit;
}

if (isset($_POST['update'])) {
    $tool->update($_POST['id'], $_POST['name'], $_POST['category_id'], $_POST['stock']);
    header("Location: index.php?page=tools&msg=" . urlencode("Alat berhasil diubah"));
    exit;
}

if (isset($_POST['delete'])) {
    try {
        $tool->delete($_POST['id']);
        $msg = "Alat berhasil dihapus";
    } catch (PDOException $e) {
        // Gagal kalau alat masih punya riwayat peminjaman (foreign key)
        $msg = "Alat tidak bisa dihapus karena sudah punya data peminjaman";
    }
    header("Location: index.php?page=tools&msg=" . urlencode($msg));
    exit;
}

// ---------- Ambil data untuk ditampilkan ----------
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $tool->getById($_GET['edit']);
}
$tools = $tool->getAll();
$categories = $category->getAll();
?>

<h2>Daftar Alat</h2>

<?php if (isset($_GET['msg'])) { ?>
    <p class="message"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php } ?>

<!-- Form tambah / ubah -->
<form method="POST" action="index.php?page=tools" class="form">
    <h3><?= $editData ? "Ubah Alat" : "Tambah Alat" ?></h3>

    <?php if ($editData) { ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <?php } ?>

    <label>Nama Alat</label>
    <input type="text" name="name" required value="<?= $editData ? htmlspecialchars($editData['name']) : '' ?>">

    <label>Kategori</label>
    <select name="category_id" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($categories as $c) { ?>
            <option value="<?= $c['id'] ?>"
                <?= ($editData && $editData['category_id'] == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php } ?>
    </select>

    <label>Stok</label>
    <input type="number" name="stock" min="0" required value="<?= $editData ? $editData['stock'] : 0 ?>">

    <?php if ($editData) { ?>
        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="index.php?page=tools">Batal</a>
    <?php } else { ?>
        <button type="submit" name="add">Tambah</button>
    <?php } ?>
</form>

<!-- Tabel data -->
<table>
    <tr>
        <th>ID</th>
        <th>Nama Alat</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($tools as $row) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= $row['stock'] ?></td>
            <td>
                <a href="index.php?page=tools&edit=<?= $row['id'] ?>">Edit</a>
                <form method="POST" action="index.php?page=tools" class="inline"
                      onsubmit="return confirm('Yakin hapus?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete">Hapus</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
