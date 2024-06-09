<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $addressId = isset($_POST['addressId']) ? $_POST['addressId'] : '';
    $kotaId = isset($_POST['kotaId']) ? $_POST['kotaId'] : '';

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
