<?php
ob_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mysqli = new mysqli("localhost", "root", "", "sunnysideup");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (empty($_SESSION['customer_id'])) {
    header("Location: katalog.php");
    exit();
}

$customerId = $_SESSION['customer_id'];

// Ambil data alamat dari tabel customer_address
$addressQuery = "SELECT * FROM customer_address WHERE customer_id = $customerId";
$addressResult = $mysqli->query($addressQuery);

if ($addressResult && $addressResult->num_rows > 0) {
    echo "<h2>Daftar Alamat</h2>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Label</th>
            <th>Nama Penerima</th>
            <th>Nomor Telephone</th>
            <th>Alamat Lengkap</th>
            <th>Kota</th>
            <th>Provinsi</th>
            <th>Code Pos</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th> <!-- Tambah kolom untuk tombol hapus -->
          </tr>";

    while ($addressRow = $addressResult->fetch_assoc()) {
        echo "<tr>
                <td>{$addressRow['label']}</td>
                <td>{$addressRow['receiver_name']}</td>
                <td>{$addressRow['phone_number']}</td>
                <td>{$addressRow['address']}</td>
                <td>{$addressRow['kota']}</td>
                <td>{$addressRow['provinsi']}</td>
                <td>{$addressRow['postal_code']}</td>
                <td>{$addressRow['created_at']}</td>
                <td>{$addressRow['updated_at']}</td>
                <td><a href='delete_address.php?action=delete&id={$addressRow['address_id']}'>Delete</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "";
}

$mysqli->close();

ob_end_flush();
?>
