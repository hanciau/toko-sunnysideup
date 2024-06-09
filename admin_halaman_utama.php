<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: halaman_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #333;
      color: #fff;
      padding: 10px;
      text-align: center;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 70vh;
    }

    .box {
      width: 200px;
      height: 200px;
      border: 1px solid #ddd;
      margin: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .box:hover {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>

  <header>
    <h1>Admin Dashboard</h1>
  </header>

  <div class="container">
  <a href="logout.php" class="box">
      <h2>keluar</h2>
    </a>
    <a href="memasukkan_produk.php" class="box">
      <h2>Tambah Produk</h2>
    </a>
    <a href="daftar_pesanan_admin.php" class="box">
      <h2>Orderan</h2>
    </a>
    <a href="select_product_delet.php" class="box">
      <h2>Hapus Produk</h2>
    </a>
  </div>

</body>
</html>
