<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_barang = $_POST["name"];
        $deskripsi = $_POST["description"];
        $harga = $_POST["price"];
        $stok = $_POST["stock"];
        $admin_id = $_SESSION['admin_id'];
        $berat = $_POST["berat"];

    } else {
        echo "Invalid method.";
    }
} else {
    echo "You must be logged in as an admin to add products.";
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sunnysideup";

function createConnection() {
    global $servername, $db_username, $db_password, $db_name;
    return new mysqli($servername, $db_username, $db_password, $db_name);
}

$conn = createConnection();

if ($conn->connect_error) {
    die("Connection to the database failed: " . $conn->connect_error);
}

$image_tmp = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image_tmp = file_get_contents($_FILES['image']['tmp_name']);
}

$insert_product_sql = "INSERT INTO product (name, description, price, berat, stock, admin_id, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($insert_product_sql);

$conn->query("SET SESSION wait_timeout = 600");
$conn->query("SET SESSION interactive_timeout = 600");

$attempts = 3;
while ($attempts > 0) {
    $stmt->bind_param("ssdiiss", $nama_barang, $deskripsi, $harga, $berat, $stok, $admin_id, $image_tmp);

    if ($stmt->execute()) {
        $product_id = $conn->insert_id;

        $_SESSION['product_id'] = $product_id;

        header("Location: select_category.php");
        exit();
    } else {
        $attempts--;
        if ($attempts > 0) {
            $conn->close();
            usleep(500000);
            $conn = createConnection();
            continue;
        }
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
