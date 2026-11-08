<?php
// Upload gambar produk ke folder assets/. Return nama file, "" jika tidak ada upload,
// atau null jika gagal (pesan error ada di $error).
function upload_gambar(array $file, ?string &$error): ?string
{
    $error = "";

    if (($file["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return "";
    }
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error = "Upload gambar gagal (kode " . $file["error"] . ").";
        return null;
    }
    if ($file["size"] > 2 * 1024 * 1024) {
        $error = "Ukuran gambar maksimal 2 MB.";
        return null;
    }

    $info = @getimagesize($file["tmp_name"]);
    $ekstensi = [
        IMAGETYPE_JPEG => "jpg",
        IMAGETYPE_PNG  => "png",
        IMAGETYPE_GIF  => "gif",
        IMAGETYPE_WEBP => "webp",
    ];
    if ($info === false || !isset($ekstensi[$info[2]])) {
        $error = "File harus berupa gambar (JPG, PNG, GIF, atau WEBP).";
        return null;
    }

    $nama = bin2hex(random_bytes(8)) . "." . $ekstensi[$info[2]];
    $tujuan = __DIR__ . "/assets/" . $nama;

    if (!move_uploaded_file($file["tmp_name"], $tujuan)) {
        $error = "Gagal menyimpan gambar ke folder assets.";
        return null;
    }
    return $nama;
}
