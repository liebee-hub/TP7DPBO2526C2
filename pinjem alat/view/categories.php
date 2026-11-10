<?php
// View untuk halaman Kategori (CRUD tabel categories)
$category = new Category($db);

// ---------- Proses aksi dari form ----------
if (isset($_POST['add'])) {
    $category->create($_POST['name'], $_POST['description']);
    header("Location: index.php?page=categories&msg=" . urlencode("Kategori berhasil ditambah"));
    exit;
}

if (isset($_POST['update'])) {
    $category->update($_POST['id'], $_POST['name'], $_POST['description']);
    header("Location: index.php?page=categories&msg=" . urlencode("Kategori berhasil diubah"));
    exit;
}

if (isset($_POST['delete'])) {
    try {
        $category->delete($_POST['id']);
        $msg = "Kategori berhasil dihapus";
    } catch (PDOException $e) {
        // Gagal kalau kategori masih dipakai oleh alat (foreign key)
        $msg = "Kategori tidak bisa dihapus karena masih dipakai oleh alat";
    }
    header("Location: index.php?page=categories&msg=" . urlencode($msg));
    exit;
}

// ---------- Ambil data untuk ditampilkan ----------
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $category->getById($_GET['edit']);
}
$categories = $category->getAll();
?>

<h2>Daftar Kategori</h2>

<?php if (isset($_GET['msg'])) { ?>
    <p class="message"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php } ?>

<!-- Form tambah / ubah -->
<form method="POST" action="index.php?page=categories" class="form">
    <h3><?= $editData ? "Ubah Kategori" : "Tambah Kategori" ?></h3>

    <?php if ($editData) { ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <?php } ?>

    <label>Nama</label>
    <input type="text" name="name" required value="<?= $editData ? htmlspecialchars($editData['name']) : '' ?>">

    <label>Deskripsi</label>
    <input type="text" name="description" value="<?= $editData ? htmlspecialchars($editData['description']) : '' ?>">

    <?php if ($editData) { ?>
        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="index.php?page=categories">Batal</a>
    <?php } else { ?>
        <button type="submit" name="add">Tambah</button>
    <?php } ?>
</form>

<!-- Tabel data -->
<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($categories as $row) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>
                <a href="index.php?page=categories&edit=<?= $row['id'] ?>">Edit</a>
                <form method="POST" action="index.php?page=categories" class="inline"
                      onsubmit="return confirm('Yakin hapus?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete">Hapus</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
