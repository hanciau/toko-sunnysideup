<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Replace with your database credentials
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "sunnysideup";

    // Create a connection to the database
    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection to the database failed: " . $conn->connect_error);
    }

    // Insert user data into the 'customer' table
    $sql = "INSERT INTO customer (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! Redirecting to login page...";
        header("Refresh: 3; URL=login.php"); // Redirect to the login page after successful registration
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid method.";
}
?>
