<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "sunnysideup");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$customer_id = $_SESSION['customer_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            display: grid;
            height: 100vh;
            margin: 0;
            place-items: center;
            background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
            overflow: auto;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .profile-image {
            border-radius: 50%;
            margin-left: 10px;
            width: 40px;
            height: 40px;
        }

        .cart-icon {
            width: 30px;
            height: 30px;
            margin-bottom: -5px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container p-0 mb-4 mt-4 rounded-5 shadow bg-white">
        <h2>Checkout:</h2>

        <?php include 'display_selected_items.php'; 
$overallTotalPrice = isset($_SESSION['overall_total_price']) ? (int)$_SESSION['overall_total_price'] : (isset($_GET['overall_total_price']) ? (int)$_GET['overall_total_price'] : 0);
$ongkir = isset($_SESSION['selected_shipping_service_cost']) ? (int)$_SESSION['selected_shipping_service_cost'] : (isset($_GET['selected_shipping_service_cost']) ? (int)$_GET['selected_shipping_service_cost'] : 0);
$totalHargaKeseluruhan = $overallTotalPrice + $ongkir;
        ?>
        <table>
        <tr>
        <td>Tolat Harga Seluruh Item</td>
        <td>:</td>
        <td><?php echo $overallTotalPrice; ?></td>
        </tr>
        <tr>
        <td>Biaya Pengiriman</td>
        <td>:</td>
        <td><?php echo $ongkir; ?></td>
        </tr>
        <tr>
        <td>Total Harga Keseluruhan</td>
        <td>:</td>
        <td><?php echo $totalHargaKeseluruhan; ?></td>
        </tr>
        </table>
        <br>
        <form action="checkout.php" method="post" id="checkoutForm">
            <?php foreach ($selectedItems as $itemId): ?>
                <input type="hidden" name="selected_items[]" value="<?php echo $itemId; ?>">
            <?php endforeach; ?>
            <input type="submit" name="process_order" value="Pesan Sekarang">
        </form>
    </div>
    <script>
        function updatePaymentMethodSession() {
            var paymentMethodSelect = document.getElementById("metode_pembayaran");
            var selectedPaymentMethod = paymentMethodSelect.value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_session_payment_method.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                }
            };
            var params = "selected_payment_method=" + encodeURIComponent(selectedPaymentMethod);
            xhr.send(params);
        }
    </script>
</body>
</html>
