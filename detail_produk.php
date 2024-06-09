<?php
session_start();
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
<html lang="en">

<head>
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            padding: 30px;
        }

        .product-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 20px;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }

        .product-image {
            border-radius: 10px 10px 10px 10px;
            width: 350px;
            height: 500px;
            object-fit: cover;
        }

        .product-detail-pemesanan {
            min-height: 500px;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .harga {
            font-size: 20px;
            color: #ee4d2d;
            margin-bottom: 20px;
        }

        .btn-beli {
            background-color: #ee4d2d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-beli:hover {
            background-color: #c54027;
        }

        .description {
            padding: 20px;
            background-color: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="checkbox"] {
            margin-right: 5px;
            cursor: pointer;
        }

        header,
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
            margin: 0;
        }

        .main-container {
            background-color: #fff;
            min-height: calc(100vh - 2 * 15px);
            padding: 20px;
            box-sizing: border-box;
            margin: 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>

<?php
include 'header.php';
?>

<body>
    <div class="main-container p-0 mb-4 mt-4 rounded-5">
        <div class="container">
            <h1>Detail Produk</h1>
            <div>
                <?php
                if (isset($_GET['id'])) {
                    $product_id = $_GET['id'];

                    $sql = "SELECT * FROM product WHERE product_id = $product_id";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $nama_produk = $row['name'];
                        $deskripsi = $row['description'];
                        $harga = $row['price'];
                        $gambar = $row['image_url'];
                        $stock = $row['stock'];
                        echo '<div class="product-container">';
                        echo '<div class="product-card">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($gambar) . '" class="product-image" />';
                        echo '</div>';
                        echo '<div class="product-detail-pemesanan">';
                        echo '<h2>' . $nama_produk . '</h2><br>';
                        echo "Stok Tersedia : " . $stock . "<br><br>";
                        echo '<div class="harga">Rp.' . number_format($harga, 2) . '</div>';

                        echo '<form action="tambah_ke_keranjang.php" method="post">';
                        echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                        echo '<input type="hidden" name="product_name" value="' . $nama_produk . '">';
                        echo '<input type="hidden" name="product_price" value="' . $harga . '">';
                        echo '<input type="hidden" name="product_image" value="' . base64_encode($gambar) . '">';
                        echo '<label for="quantity">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" required><br><br>';
                        echo '<button type="submit" class="btn-beli">Masukkan ke Keranjang</button>';
                        echo '</form>';

                        echo '</div>';
                        echo '</div>';
                        echo '<div class="description">';
                        echo '<p>' . $deskripsi . '</p>';
                        echo '</div>';
                    } else {
                        echo "Produk tidak ditemukan.";
                    }
                } else {
                    echo "ID produk tidak ditemukan.";
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
    <?php
    include 'footer.php';
    ?>
</body>

</html>
