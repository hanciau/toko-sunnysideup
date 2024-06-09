<?php
session_start();

// Pastikan user sudah login dan memiliki item di keranjang
if (empty($_SESSION['customer_id'])) {
    // Redirect atau tampilkan pesan kesalahan jika tidak login
    exit("Error: Customer not logged in.");
}

$customerId = $_SESSION['customer_id'];

// Lakukan koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "sunnysideup");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Ambil data dari tabel keranjang untuk customer tertentu
$queryKeranjang = "SELECT * FROM keranjang WHERE customer_id = $customerId";
$resultKeranjang = $mysqli->query($queryKeranjang);

// Pastikan ada hasil
if ($resultKeranjang && $resultKeranjang->num_rows > 0) {
    // Tanggal order saat ini
    $orderDate = date("Y-m-d H:i:s");

    // Status order (contoh: "Pending")
    $status = "Pending";

    // Metode pengiriman
    $metodePengiriman = $_POST['metode_pengiriman']; // Metode pengiriman dipilih dari formulir pemesanan

    // Query untuk membuat order baru
    $queryOrder = "INSERT INTO `order` (customer_id, order_date, waktu, status, metode_pengiriman) VALUES ($customerId, '$orderDate', NOW(), '$status', '$metodePengiriman')";

    // Jalankan query
    $resultOrder = $mysqli->query($queryOrder);

    // Dapatkan ID order yang baru saja dibuat
    $orderId = $mysqli->insert_id;

    // Iterasi setiap item di keranjang
    while ($row = $resultKeranjang->fetch_assoc()) {
        $productId = $row['product_id'];
        $quantity = $row['quantity'];
        $hargaSeluruh = $row['harga_seluruh'];

        // Query untuk menambahkan item ke dalam order_item
        $queryOrderItem = "INSERT INTO order_item (order_id, product_id, quantity, harga_seluruh) VALUES ($orderId, $productId, $quantity, $hargaSeluruh)";

        // Jalankan query
        $resultOrderItem = $mysqli->query($queryOrderItem);

        // Hapus item dari keranjang jika pemindahan berhasil
        if ($resultOrderItem) {
            $keranjangId = $row['id'];
            $mysqli->query("DELETE FROM keranjang WHERE id = $keranjangId");
        } else {
            // Handle jika terjadi kesalahan saat memindahkan data
            echo "Error moving data to order_item table: " . $mysqli->error;
        }
    }

    // Tutup hasil
    $resultKeranjang->close();
}

// Tutup koneksi
$mysqli->close();

// Redirect ke halaman order setelah pemindahan
header("Location: order.php");
exit();
?>
