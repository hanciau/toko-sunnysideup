<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['kurir'])) {
        $selectedCourier = $_POST['kurir'];

        $_SESSION['kurir'] = $selectedCourier;

        $response = [
            'success' => true,
            'message' => 'Courier updated successfully.'
        ];
        echo json_encode($response);
        
        header("Location: get_coast.php");
        exit();
    } else {
        $response = [
            'success' => false,
            'message' => 'Courier not provided in the request.'
        ];
        echo json_encode($response);
        exit();
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request method.'
    ];
    echo json_encode($response);
    exit();
}
?>
