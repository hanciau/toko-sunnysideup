<?php
session_start();

function redir($url) {
    header("Location: $url");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sunnysideup");
if (empty($_SESSION['customer_id'])) {
    redir("halaman_awal.php");
}
if ($conn->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $harga = $_POST['product_price'];
    $harga_seluruh = intval($quantity) * floatval($harga);
    
    $checkStockQuery = "SELECT stock FROM product WHERE product_id = $product_id";
    $checkStockResult = $conn->query($checkStockQuery);

    if ($checkStockResult->num_rows > 0) {
        $row = $checkStockResult->fetch_assoc();
        $availableStock = $row['stock'];
        if ($quantity > $availableStock) {
        header("Location: detail_produk.php?id=$product_id;");
        exit();
        }

        $checkCartQuery = "SELECT * FROM keranjang WHERE customer_id = $customer_id AND product_id = $product_id";
        $checkCartResult = $conn->query($checkCartQuery);

        if ($checkCartResult->num_rows > 0) {
            $row = $checkCartResult->fetch_assoc();
            $updateCartQuery = "UPDATE keranjang SET quantity = quantity + $quantity, harga_seluruh = harga_seluruh + $harga_seluruh WHERE customer_id = $customer_id AND product_id = $product_id";
            $conn->query($updateCartQuery);
        } else {
            $insertCartQuery = "INSERT INTO keranjang (customer_id, product_id, quantity, harga_seluruh)
            VALUES ('$customer_id', '$product_id', '$quantity', '$harga_seluruh')";
            $conn->query($insertCartQuery);
        }

        header("Location: keranjang_customer.php");
        exit(); 
    } else {
        echo "Error: Product with ID $product_id not found.";
        exit();
    }
} else {
    echo "Metode request tidak valid.";
}
$conn->close();
?>
