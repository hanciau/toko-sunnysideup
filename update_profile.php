<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sunnysideup";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (empty($_SESSION['customer_id'])) {
    echo "Customer ID not found in session.";
    exit();
}

$editName = $_POST['editName'];
$editEmail = $_POST['editEmail'];
$editTelephone = $_POST['editTelephone'];
$customerId = $_SESSION['customer_id'];

if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $avatarData = file_get_contents($_FILES['image']['tmp_name']);
} else {
    $avatarData = null;
}

$updateProfileQuery = "UPDATE customer SET real_name=?, email=?, telephone=?, image=? WHERE customer_id=?";
$stmtUpdateProfile = $conn->prepare($updateProfileQuery);
$stmtUpdateProfile->bind_param("sssss", $editName, $editEmail, $editTelephone, $avatarData, $customerId);

if ($stmtUpdateProfile->execute()) {
    echo "Profile updated successfully. ";
} else {
    echo "Error updating profile: " . $stmtUpdateProfile->error;
}

$stmtUpdateProfile->close();

header("Location: profile.php");
exit();

$conn->close();
?>
