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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id']) && isset($_POST['new_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['new_quantity'];

    $updateQuantityQuery = "UPDATE keranjang SET quantity = $new_quantity WHERE id = $item_id";
    
    if ($conn->query($updateQuantityQuery) === TRUE) {
    } else {
        echo "Error updating quantity: " . $conn->error;
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
?>
