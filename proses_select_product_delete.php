<style>
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
<?php
// Database configuration
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sunnysideup";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Periksa apakah admin telah masuk sebelum memproses input produk
session_start();

if (!isset($_SESSION['admin_id'])) {
    // Jika admin belum masuk, alihkan ke halaman login
    header("Location: login.php");
    exit();
}

// Mengambil data produk yang dipilih untuk dihapus
if (isset($_POST['products_to_delete'])) {
    $products_to_delete = $_POST['products_to_delete'];

    foreach ($products_to_delete as $product_id) {
        // Proses penghapusan kategori produk dari tabel product_category
        $delete_category_query = "DELETE FROM product_category WHERE product_id = $product_id";

        // Menjalankan query dan mengecek kesalahan
        if ($conn->query($delete_category_query) !== TRUE) {
            echo "Error: " . $delete_category_query . "<br>" . $conn->error;
            exit();
        }

        // Proses penghapusan produk dari tabel product
        $delete_product_query = "DELETE FROM product WHERE product_id = $product_id";

        // Menjalankan query dan mengecek kesalahan
        if ($conn->query($delete_product_query) !== TRUE) {
            echo "Error: " . $delete_product_query . "<br>" . $conn->error;
            exit();
        }
    }

    echo '<div style="text-align: center; margin: 50px;">';
    echo '<h2 style="color: green;">Produk berhasil dihapus!</h2>';
    echo '<p>Apa yang ingin Anda lakukan selanjutnya?</p>';
    echo '<div class="container">';
    echo '<a href="admin_halaman_utama.php" class="box">';
    echo '<h2>Halaman Admin</h2>';
    echo '</a>';
    echo '<a href="select_product_delet.php" class="box">';
    echo '<h2>Hapus Produk Lagi</h2>';
    echo '</a>';
    echo '</div>';
    echo '</div>';
} else {
    // Jika tidak ada produk yang dipilih, beri pesan kesalahan
    echo "Tidak ada produk yang dipilih untuk dihapus.";
}

// Tutup koneksi database
$conn->close();
?>
