<?php
$conn = mysqli_connect("localhost", "root", "", "sunnysideup");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['selected_items']) && is_array($_SESSION['selected_items'])) {
    $selectedItems = $_SESSION['selected_items'];

    echo "<h2>Selected Items:</h2>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Image</th>
          </tr>";
    $totalWeight = 0;
    $overallTotalPrice = 0;

    $query = "SELECT k.product_id, p.name, p.price, k.quantity, p.berat, p.image_url FROM keranjang k JOIN product p ON k.product_id = p.product_id WHERE k.id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $itemId);

    foreach ($selectedItems as $itemId) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $itemWeight = intval($row['berat']) * intval($row['quantity']);
            // or
            $itemWeight = (int)$row['berat'] * (int)$row['quantity'];

            $totalWeight += $itemWeight;

            $totalPrice = $row['quantity'] * $row['price'];

            $overallTotalPrice += $totalPrice;

            $imageData = base64_encode($row['image_url']);
            $imageSrc = "data:image/jpeg;base64,{$imageData}";

            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$totalPrice}</td>
                    <td><img src='{$imageSrc}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>
                  </tr>";
        } else {
            echo "<tr><td colspan='5'>Product not found</td></tr>";
        }
    }
    $_SESSION['total_weight'] = $totalWeight;
    $_SESSION['overall_total_price'] = $overallTotalPrice;

    $customerId = $_SESSION['customer_id']; 
    $uploadUrl = 'https://your-server.com/upload-order.php';

    $postData = array(
        'customer_id' => $customerId,
        'total_weight' => $totalWeight,
        'overall_total_price' => $overallTotalPrice,
    );

    $curl = curl_init($uploadUrl);

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    echo "</table>";

} else {
    echo "<p>No items selected</p>";
}

mysqli_close($conn);
?>
