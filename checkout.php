<?php
session_start();
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sunnysideup";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_order'])) {
    $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 0;
    $selectedItems = isset($_SESSION['selected_items']) ? $_SESSION['selected_items'] : [];
    $quantities = isset($_SESSION['quantities']) ? $_SESSION['quantities'] : [];
    $hargaseluruhperitem = isset($_SESSION['hargaseluruhperitem']) ? $_SESSION['hargaseluruhperitem'] : [];
    $overallTotalPrice = isset($_SESSION['overall_total_price']) ? $_SESSION['overall_total_price'] : 0;
    $address_id = isset($_SESSION['address_id']) ? $_SESSION['address_id'] : 0;
    $kurir = isset($_SESSION['kurir']) ? $_SESSION['kurir'] : 0;
    if (!empty($selectedItems)) {
        $conn->begin_transaction();

        $order_date = date('Y-m-d H:i:s');
        $status = 'Pending';
        $metode_pengiriman = isset($_SESSION['selected_shipping_service']) ? $_SESSION['selected_shipping_service'] : 'Kirim'; // Assuming default method is "Kirim"
        $alamat = $address_id; 
        $ongkos_kirim = isset($_SESSION['selected_shipping_service_cost']) ? $_SESSION['selected_shipping_service_cost'] : 0; // Set the desired value
        $total_biaya = $overallTotalPrice + $ongkos_kirim;

        $insertOrderQuery = "INSERT INTO `order` (customer_id, order_date, total_harga, status, metode_pengiriman, alamat, ongkos_kirim, total_biaya, perusahaan_pengiriman) VALUES ('$customer_id', '$order_date', '$overallTotalPrice', '$status', '$metode_pengiriman', '$alamat', '$ongkos_kirim', '$total_biaya', '$kurir')";

        if ($conn->query($insertOrderQuery) === TRUE) {
            $orderId = $conn->insert_id;

            foreach ($selectedItems as $cartItemId) {
                $getProductQuery = "SELECT keranjang.product_id FROM keranjang WHERE id = '$cartItemId'";

                $productResult = $conn->query($getProductQuery);

                if ($productResult && $productRow = $productResult->fetch_assoc()) {
                    $productId = $productRow['product_id'];
                    $quantity = isset($quantities[$cartItemId]) ? $quantities[$cartItemId] : 0;
                    $harga_seluruh = isset($hargaseluruhperitem[$cartItemId]) ? $hargaseluruhperitem[$cartItemId] : 0;

                    $insertOrderItemQuery = "INSERT INTO order_item (order_id, product_id, quantity, harga_seluruh) VALUES ('$orderId', '$productId', '$quantity', '$harga_seluruh')";

                    if ($conn->query($insertOrderItemQuery) !== TRUE) {
                        $conn->rollback();
                        echo "Error inserting into order_item table: " . $conn->error;
                        exit();
                    }
                } else {
                    $conn->rollback();
                    echo "Error: Cart item with ID $cartItemId does not exist.";
                    exit();
                }
            }

            $conn->commit();

            unset($_SESSION['selected_items']);

            header("Location: payment.php?order_id=$orderId");
            exit();
        } else {
            echo "Error inserting into order table: " . $conn->error;
            $conn->rollback();
        }
    }
}
?>
