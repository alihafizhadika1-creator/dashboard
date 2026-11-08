<?php
require_once "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php?page=produk");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php?page=produk");
    exit;
}

// ambil nama gambar dulu supaya filenya ikut terhapus
$stmt = mysqli_prepare($koneksi, "SELECT gambar FROM tbproduk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($koneksi, "DELETE FROM tbproduk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($ok && $row && !empty($row["gambar"])) {
    $file = __DIR__ . "/assets/" . basename($row["gambar"]);
    if (is_file($file)) {
        @unlink($file);
    }
}

header("Location: index.php?page=produk");
exit;
