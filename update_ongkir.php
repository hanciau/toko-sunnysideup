<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_service'])) {
    $selectedService = $_POST['selected_service'];
    $selectedServiceCost = $_POST['selected_service_cost'];

    $_SESSION['selected_shipping_service'] = $selectedService;
    $_SESSION['selected_shipping_service_cost'] = $selectedServiceCost;
    header("Location: order.php");
    exit();
} else {
    echo "Form not submitted!";
}
?>
