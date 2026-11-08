<?php
require_once __DIR__ . "/upload.php";

$pesan = "";
$nama = "";
$harga = "";
$stok = "";
$kategori = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama_produk"] ?? "");
    $harga = trim($_POST["harga"] ?? "");
    $stok = trim($_POST["stok"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");

    if ($nama === "" || $harga === "" || $stok === "" || $kategori === "") {
        $pesan = "Semua kolom harus diisi.";
    } elseif (!is_numeric($harga) || (float)$harga < 0) {
        $pesan = "Harga harus berupa angka yang valid.";
    } elseif (
        filter_var($stok, FILTER_VALIDATE_INT) === false
        || (int)$stok < 0
    ) {
        $pesan = "Stok harus berupa bilangan bulat minimal 0.";
    } else {
        $gambar = upload_gambar($_FILES["gambar"] ?? ["error" => UPLOAD_ERR_NO_FILE], $errUpload);

        if ($gambar === null) {
            $pesan = $errUpload;
        } else {
            $harga = (float)$harga;
            $stok = (int)$stok;

            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO tbproduk (nama_produk, harga, stok, gambar, kategori)
                 VALUES (?, ?, ?, ?, ?)"
            );

            if (!$stmt) {
                $pesan = "Query gagal: " . mysqli_error($koneksi);
            } else {
                mysqli_stmt_bind_param($stmt, "sdiss", $nama, $harga, $stok, $gambar, $kategori);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    header("Location: index.php?page=produk");
                    exit;
                } else {
                    $pesan = "Data gagal disimpan: " . mysqli_stmt_error($stmt);
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}
?>

<h2 class="h4 mb-3">Tambah Produk</h2>
<hr>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <?php if ($pesan !== ""): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=tambah_produk" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control"
                       value="<?= htmlspecialchars($nama) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" min="0" step="any"
                       value="<?= htmlspecialchars($harga) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" step="1"
                       value="<?= htmlspecialchars($stok) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control"
                       value="<?= htmlspecialchars($kategori) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar (opsional, maks 2 MB)</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
            <a href="index.php?page=produk" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>
