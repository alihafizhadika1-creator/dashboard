<?php

$page = $_GET["page"] ?? "dashboard";

$halaman = [
    "dashboard" => "page/dashboard.php",
    "produk" => "page/produk.php",
    "tambah_produk" => "tambah.php",
    "edit_produk" => "edit.php",
    "orders" => "page/orders.php",
    "customers" => "page/customers.php",
];

if (isset($halaman[$page])) {
    $file = __DIR__ . "/" . $halaman[$page];

    if (file_exists($file)) {
        require $file;
    } else {
        echo '<div class="alert alert-danger">
            File tidak ditemukan: '
            . htmlspecialchars($halaman[$page]) .
            '</div>';
    }
} else {
    echo '<div class="alert alert-warning">
        Halaman tidak ditemukan.
    </div>';
}
?>