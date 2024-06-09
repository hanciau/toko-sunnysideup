<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_delivery_method'])) {
    $_SESSION['selected_delivery_method'] = $_POST['selected_delivery_method'];
    echo "Session updated successfully";
} else {
    echo "Invalid request";
}
?>
