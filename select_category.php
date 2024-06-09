<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['product_id'])) {
    $product_id = $_SESSION['product_id'];
} else {
    echo "Product ID tidak tersedia dalam sesi.";
    exit();
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sunnysideup";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pilih Kategori</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>

        html, body {
            display: grid;
            height: 100%;
            width: 100%;
            place-items: center;
            background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
            overflow: auto;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

</style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">Pilih Kategori</h1>
                    </div>
                    <div class="card-body">
                        <form action="proses_select_category.php" method="post">
                            <p>Silakan pilih kategori yang sesuai untuk produk dengan ID: <?php echo $product_id; ?></p>

                            <?php
                            $query = "SELECT * FROM category";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $category_id = $row['category_id'];
                                    $category_name = $row['category_name'];
                                    echo '<label><input type="checkbox" name="category_id[]" value="' . $category_id . '">' . $category_name . '</label><br>';
                                }
                            } else {
                                echo "Tidak ada data kategori dalam database.";
                            }
                            ?>

                            <input type="submit" class="btn btn-primary mt-3" value="Simpan Kategori">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                $query_produk = "SELECT * FROM product WHERE product_id = $product_id";
                $result_produk = $conn->query($query_produk);

                if ($result_produk->num_rows > 0) {
                    $row_produk = $result_produk->fetch_assoc();
                    $gambar_blob = $row_produk['image_url'];
                    $nama_produk = $row_produk['name'];
                    $deskripsi = $row_produk['description'];
                    $harga = $row_produk['price'];

                    echo '<div class="card shadow">';
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($gambar_blob) . '" style="width: 349px; height: 420px;" class="card-img-top equal-image" />';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $nama_produk . '</h5>';

                    echo '<p class="card-text">' . $deskripsi . '</p>';
                    echo '</div>';
                    echo '<div class="d-none deskripsi"><p>' . $deskripsi . '</p></div>';
                    echo '<div class="card-footer d-md-flex">';
                    echo '<span class="ms-auto text-danger fw-bold d-block text-center harga">Rp. ' . $harga . '</span>';
                    echo '</div>';
                } else {
                    echo "Tidak ada produk yang tersedia.";
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
