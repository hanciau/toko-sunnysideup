<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "sunnysideup";

    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection to the database failed: " . $conn->connect_error);
    }

    $customer_sql = "SELECT * FROM customer WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($customer_sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $customer_result = $stmt->get_result();
    $stmt->close();

    $admin_sql = "SELECT * FROM pengelola WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($admin_sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $admin_result = $stmt->get_result();
    $stmt->close();

    if ($customer_result->num_rows > 0) {
        $_SESSION['customer_id'] = $customer_result->fetch_assoc()['customer_id'];
        header('Location: katalog.php');
        exit();
    } elseif ($admin_result->num_rows > 0) {
        $admin_data = $admin_result->fetch_assoc();
        $_SESSION['admin_id'] = $admin_data['admin_id'];
        header("Location: admin_halaman_utama.php");
        exit();
    } else {
        $error_message = "Email or password is incorrect. Please try again.";
    }

    $conn->close();
} else {
    echo "Invalid method.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Error Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .error-message {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="error-message">
        <?php echo $error_message; ?>
        <a href="halaman_awal.php">Back to Login Page</a>
    </div>
</body>
</html>
