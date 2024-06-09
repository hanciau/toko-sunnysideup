<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $nik = $_POST["nik"];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "sunnysideup";

    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Koneksi ke database gagal: "  . $conn->connect_error);
    }

    $sql = "INSERT INTO pengelola (username, password, emailadmin, nik) VALUES ('$username', '$password', '$email', '$nik')";

    if ($conn->query($sql) === TRUE) {
        echo "Pendaftaran berhasil!";
        header(location: "halaman_awal.php");
    } else {
        echo "Gagal melakukan pendaftaran: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Metode yang tidak valid.";
}
?>
