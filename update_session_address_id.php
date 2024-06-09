<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedAddressId = $_POST["selected_address_id"];

    $_SESSION["addressId"] = $selectedAddressId;

    echo "Session variable updated successfully";
} else {
    http_response_code(400);
    echo "Invalid request method";
}
?>
