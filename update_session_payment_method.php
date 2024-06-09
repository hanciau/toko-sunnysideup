<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["payment_method"])) {
        $_SESSION["payment_method"] = $_POST["payment_method"];
        echo "Session updated successfully";
    } else {
        echo "Invalid request";
    }
} else {
    echo "Invalid request method";
}
?>
