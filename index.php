<?php
ob_start();

require_once "koneksi.php";

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ZaRamart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            background: #fff;
            min-height: calc(100vh - 56px);
            border-right: 1px solid #dee2e6;
        }

        .sidebar .nav-link {
            color: #343a40;
            padding: 10px 12px;
            border-radius: 5px;
        }

        .sidebar .nav-link:hover {
            background-color: #f1f3f5;
        }

        .sidebar .nav-link.active {
            background-color: #e9f2ff;
            color: #0d6efd;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
        }

        main {
            min-width: 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">ZaRamart</a>
        <span class="navbar-text text-light"> Admin</span>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-12 col-md-3 col-lg-2 sidebar p-3">
            <?php require "menu.php"; ?>
        </aside>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4">
            <?php require "content.php"; ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>