<?php
require_once __DIR__ . "/upload.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    echo '<div class="alert alert-warning">ID produk tidak valid.</div>';
    return;
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama_produk, harga, stok, gambar, kategori FROM tbproduk WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data) {
    echo '<div class="alert alert-warning">Produk tidak ditemukan.</div>';
    return;
}

$nama = $data["nama_produk"];
$harga = $data["harga"];
$stok = $data["stok"];
$kategori = $data["kategori"];
$gambarLama = (string)$data["gambar"];
$pesan = "";

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
        $gambarBaru = upload_gambar($_FILES["gambar"] ?? ["error" => UPLOAD_ERR_NO_FILE], $errUpload);

        if ($gambarBaru === null) {
            $pesan = $errUpload;
        } else {
            $gambarSimpan = $gambarBaru !== "" ? $gambarBaru : $gambarLama;
            $hargaNum = (float)$harga;
            $stokNum = (int)$stok;

            $stmt = mysqli_prepare(
                $koneksi,
                "UPDATE tbproduk
                 SET nama_produk = ?, harga = ?, stok = ?, gambar = ?, kategori = ?
                 WHERE id = ?"
            );
            mysqli_stmt_bind_param(
                $stmt, "sdissi",
                $nama, $hargaNum, $stokNum, $gambarSimpan, $kategori, $id
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // hapus gambar lama kalau diganti
                if ($gambarBaru !== "" && $gambarLama !== "") {
                    $lama = __DIR__ . "/assets/" . basename($gambarLama);
                    if (is_file($lama)) {
                        @unlink($lama);
                    }
                }
                header("Location: index.php?page=produk");
                exit;
            } else {
                $pesan = "Gagal memperbarui produk: " . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<h2 class="h4 mb-3">Edit Produk</h2>
<hr>
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <?php if ($pesan !== ""): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control"
                       value="<?= htmlspecialchars((string)$nama) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" min="0" step="any"
                       value="<?= htmlspecialchars((string)$harga) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" step="1"
                       value="<?= htmlspecialchars((string)$stok) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control"
                       value="<?= htmlspecialchars((string)$kategori) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <?php if ($gambarLama !== "" && is_file(__DIR__ . "/assets/" . basename($gambarLama))): ?>
                    <div class="mb-2">
                        <img src="assets/<?= htmlspecialchars(basename($gambarLama)) ?>"
                             alt="" style="max-height:80px" class="img-thumbnail">
                    </div>
                <?php elseif ($gambarLama !== ""): ?>
                    <div class="form-text mb-2">File saat ini: <?= htmlspecialchars($gambarLama) ?> (tidak ada di folder assets)</div>
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*">
                <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Update
            </button>
            <a href="index.php?page=produk" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>
