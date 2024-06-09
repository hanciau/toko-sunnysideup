<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "sunnysideup");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_order'])) {
    if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
        $selectedItems = $_POST['selected_items'];

        $quantities = [];

        $customer_id = $_SESSION['customer_id'];

        $queryQuantities = "SELECT id, quantity, harga_seluruh FROM keranjang WHERE customer_id = $customer_id AND id IN (" . implode(',', $selectedItems) . ")";
        $resultQuantities = mysqli_query($conn, $queryQuantities);

        if ($resultQuantities && mysqli_num_rows($resultQuantities) > 0) {
            while ($rowQuantity = mysqli_fetch_assoc($resultQuantities)) {
                $quantities[$rowQuantity['id']] = $rowQuantity['quantity'];
                $hargaseluruhperitem[$rowQuantity['id']] = $rowQuantity['harga_seluruh'];
            }
        }

        foreach ($quantities as $itemID => $quantity) {
            $queryStock = "SELECT stock FROM product WHERE product_id IN (SELECT product_id FROM keranjang WHERE id = $itemID)";
            $resultStock = mysqli_query($conn, $queryStock);

            if ($resultStock && mysqli_num_rows($resultStock) > 0) {
                $rowStock = mysqli_fetch_assoc($resultStock);
                $stock = $rowStock['stock'];

                if ($quantity > $stock) {
                    header("Location: keranjang_customer.php?error=quantity_exceeds_stock");
                    exit();
                }
            }
        }

        $_SESSION['selected_items'] = $selectedItems;
        $_SESSION['quantities'] = $quantities;
        $_SESSION['hargaseluruhperitem'] = $hargaseluruhperitem;

        header("Location: ambilditempat_atau_kirim.php");
        exit();
    }
}
?>
