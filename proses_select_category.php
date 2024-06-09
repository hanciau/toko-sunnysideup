<?php
session_start();

// Periksa apakah ID produk yang baru saja disimpan ada dalam sesi
if (isset($_SESSION['product_id'])) {
    $product_id = $_SESSION['product_id'];
} else {
    echo "ID produk tidak ditemukan.";
    exit(); // Keluar dari skrip jika ID produk tidak ditemukan
}

// Gantilah informasi berikut sesuai dengan koneksi database Anda
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sunnysideup";

// Membuat koneksi ke database
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Mengecek koneksi database
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$success_message = ''; // Pesan berhasil disimpan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil kategori yang dipilih dari formulir
    $kategori_id = $_POST["category_id"];

    // Loop melalui kategori yang dipilih dan masukkan ke dalam tabel category_product
    foreach ($kategori_id as $category_id) {
        $insert_category_sql = "INSERT INTO product_category (product_id, category_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_category_sql);
        $stmt->bind_param("ii", $product_id, $category_id);
        $stmt->execute();
        $stmt->close();
    }

    $success_message = "Kategori berhasil dipilih untuk produk dengan ID: " . $product_id;

    unset($_SESSION['product_id']);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menambahkan Produk Baru</title>
    <!-- Tambahkan link CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Menambahkan Produk Baru</h1>
            </div>
            <div class="card-body">
                <a href="memasukkan_produk.php" class="btn btn-primary">Tambah Produk</a>
                <a href="admin_halaman_utama.php" class="btn btn-primary">Halaman utama</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
            <?php if (!empty($success_message)) { ?>
                <div class="card-footer">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Tambahkan script JS Bootstrap jika diperlukan -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
