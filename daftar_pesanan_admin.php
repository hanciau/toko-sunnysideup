<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: halaman_login.php');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    $updateStatusQuery = "UPDATE `order` SET status = '$new_status' WHERE order_id = $order_id";
    if ($conn->query($updateStatusQuery) === TRUE) {
        if ($new_status === 'Dibayar') {
            $getOrderItemsQuery = "SELECT * FROM order_item WHERE order_id = $order_id";
            $orderItemsResult = $conn->query($getOrderItemsQuery);

            while ($orderItemRow = $orderItemsResult->fetch_assoc()) {
                $product_id = $orderItemRow['product_id'];
                $quantity = $orderItemRow['quantity'];

                $updateStockQuery = "UPDATE product SET stock = stock - $quantity WHERE product_id = $product_id";
                $conn->query($updateStockQuery);
            }
        }

        header("Location: daftar_pesanan_admin.php");
    } else {
        echo "Error updating status: " . $conn->error;
    }
}

$sql = "SELECT o.*, c.real_name AS customer_name, 
               GROUP_CONCAT(oi.product_id) AS product_ids, 
               GROUP_CONCAT(oi.quantity) AS quantities,
               ca.label,
               ca.receiver_name,
               ca.phone_number,
               ca.address AS alamat_lengkap,
               ca.postal_code,
               ca.created_at AS address_created_at,
               ca.updated_at AS address_updated_at
        FROM `order` o
        JOIN customer c ON o.customer_id = c.customer_id
        JOIN order_item oi ON o.order_id = oi.order_id
        JOIN customer_address ca ON o.alamat = ca.address_id
        GROUP BY o.order_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container p-4">
        <h1 class="text-center mb-4">Daftar Pesanan</h1>

        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>No.</th>'; // Mengganti 'ID' dengan 'No.'
            echo '<th>Nama Customer</th>';
            echo '<th>ID Produk</th>';
            echo '<th>Tanggal Pesanan</th>';
            echo '<th>Jumlah Barang</th>';
            echo '<th>Alamat</th>';
            echo '<th>ongkos kirim</th>';
            echo '<th>Total Harga</th>';
            echo '<th>Waktu</th>';
            echo '<th>Status</th>';
            echo '<th>Aksi</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            $counter = 1; // Variabel counter
            
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $counter++ . '</td>'; // Menampilkan nomor yang bertambah sesuai dengan order
                echo '<td>' . $row['customer_name'] . '</td>';
                echo '<td>' . $row['product_ids'] . '</td>'; 
                echo '<td>' . $row['order_date'] . '</td>';
                echo '<td>' . $row['quantities'] . '</td>'; 
                echo '<td>' . $row['alamat_lengkap'] . '</td>';
                echo '<td>' . $row['ongkos_kirim'] . '</td>';
                echo '<td>' . $row['total_harga'] . '</td>';
                echo '<td>' . $row['waktu'] . '</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '<td>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="order_id" value="' . $row['order_id'] . '">';
                echo '<select name="new_status" class="form-select form-select-sm">';
                echo '<option value="">Tambah Status</option>';
                echo '<option value="Dibayar">Dibayar</option>';
                echo '<option value="Selesai">Selesai</option>';
                echo '</select>';
                echo '<button type="submit" name="change_status" class="btn btn-primary btn-sm">Ubah Status</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            
        } else {
            echo '<p class="text-center">Tidak ada pesanan.</p>';
        }

        $conn->close();
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
