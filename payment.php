<?php

use Midtrans\Config;
use Midtrans\Snap;

require_once __DIR__ . '../Midtrans-php-master/Midtrans.php';

Config::$serverKey = 'SB-Mid-server-U4RX-axn8OvVVWcYYh1llceX';
Config::$clientKey = 'SB-Mid-client-XVtEl0EtbblM7aUA';

printExampleWarningMessage();

Config::$isSanitized = Config::$is3ds = true;


$servername = "localhost"; 
$db_username = "root";     
$db_password = "";         
$db_name = "sunnysideup";   

$conn = new mysqli($servername, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$order_id = $_GET['order_id'];

Config::$serverKey = 'SB-Mid-server-U4RX-axn8OvVVWcYYh1llceX';

$query = "SELECT * FROM `order` WHERE order_id='$order_id'";
$sql = $conn->query($query);

if (!$sql) {
    die('Order not found.');
}

$orderData = mysqli_fetch_array($sql);

if (!$orderData) {
    die('Order not found.');
}

$customer_id = $orderData['customer_id'];
$total_amount = $orderData['total_biaya'];

$queryCustomer = "SELECT * FROM `customer` WHERE customer_id='$customer_id'";
$sqlCustomer = $conn->query($queryCustomer);

if (!$sqlCustomer) {
    die('Customer not found.');
}

$customerData = mysqli_fetch_array($sqlCustomer);
$nama = $customerData['real_name'];
$email = $customerData['email'];

$queryItem = "SELECT oi.product_id, oi.harga_seluruh, oi.quantity, p.name as item_name FROM `order_item` oi JOIN `product` p ON oi.product_id = p.product_id WHERE oi.order_id='$order_id'";
$sqlItem = $conn->query($queryItem);

if (!$sqlItem) {
    die('Error fetching item details.');
}

$item_details = array();

while ($itemData = mysqli_fetch_array($sqlItem)) {
    $item_details[] = array(
        'id' => $itemData['product_id'],
        'price' => $itemData['harga_seluruh'],
        'quantity' => $itemData['quantity'],
        'name' => $itemData['item_name']
    );
}

$customer_details = array(
    'first_name'    => $nama,
    'last_name'     => "",
    'email'         => $email,
    'phone'         => "",
    'billing_address'  => "", 
    'shipping_address' => "",
);

$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => $total_amount,
);

$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
);

$snap_token = '';

try {
    $snap_token = Snap::getSnapToken($transaction);
} catch (\Exception $e) {
    echo $e->getMessage();
}

function printExampleWarningMessage() {
    if (strpos(Config::$serverKey, 'your ') !== false) {
        echo "<code>";
        echo "<h4>Please set your server key from the sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<server key>\';');
        die();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAYMENT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
    <br>
    <br>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <p>Registrasi Berhasil, Selesaikan Pembayaran Sekarang</p>
                <p>total seluruh pembayaran : Rp.<?php echo number_format($total_amount, 0, ',', '.'); ?></p>
                <p style='color: red;'>! Anda hanya dapat memilih sekali, harap langsung selesaikan pembayaran.</p> <p>Terimakasih</p>
                <button id="pay-button" class="btn btn-primary">PILIH METODE PEMBAYARAN</button>
                
                <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey;?>"></script>
                <script type="text/javascript">
                    document.getElementById('pay-button').onclick = function(){
                        snap.pay('<?php echo $snap_token?>');
                    };
                </script>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
