<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedKotaId = $_POST["selected_kota_id"];
    $selectedAddressId = $_POST["selected_address_id"];

    $_SESSION["destinasion"] = $selectedKotaId;
    $_SESSION["address_id"] = $selectedAddressId;

    echo "Session variables updated successfully";
} else {
    http_response_code(400);
    echo "Invalid request method";
}
?>
