<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "sunnysideup");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    $delete_query = "DELETE FROM keranjang WHERE id = $item_id AND customer_id = $customer_id";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        header("Location: keranjang_customer.php");
        exit();
    } else {
        echo "Error deleting item: " . mysqli_error($conn);
    }
} else {
    echo "Item ID not provided.";
}

mysqli_close($conn);
?>
