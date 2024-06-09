<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['customer_id'])) {
    header("Location: katalog.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $addressId = $_GET['id'];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['customer_id'])) {
    header("Location: katalog.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $addressId = $_GET['id'];

    $mysqli = new mysqli("localhost", "root", "", "sunnysideup");
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $deleteAddressQuery = "DELETE FROM customer_address WHERE address_id = ?";
    
    $stmtDeleteAddress = $mysqli->prepare($deleteAddressQuery);

    $stmtDeleteAddress->bind_param("i", $addressId);

    if ($stmtDeleteAddress->execute()) {
        header("Location: profile.php");
    } else {
        echo "Error deleting address: " . $stmtDeleteAddress->error;
    }
    $stmtDeleteAddress->close();
    
    $mysqli->close();
} else {
}
}
?>
