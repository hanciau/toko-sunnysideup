<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "sunnysideup");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['customer_id'])) {
    header("Location: halaman_awal.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$query_keranjang = "SELECT k.id, k.customer_id, k.product_id, k.quantity, k.harga_seluruh, p.name, p.stock, p.image_url, p.price FROM keranjang k JOIN product p ON k.product_id = p.product_id WHERE k.customer_id = $customer_id";
$result_keranjang = mysqli_query($conn, $query_keranjang);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Keranjang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
        <link href="/path/to/bootstrap-icons.css" rel="stylesheet">
    <style>
        header, footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        .main-container {
            background-color: #fff; 
            min-height: calc(100vh - 2 * 15px); 
            padding: 20px;
            box-sizing: border-box;
        }
        body {
            font-family: sans-serif;
            font-size: 16px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
        }
 
        th, td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }

        .btn-tambah {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-kurang {
            background-color: #ff0000;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-order {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        html,
        body {
            height: 100%;
            width: 100%;
            place-items: center;
            overflow: auto;
        }
    </style>
</head>
<body>
<?php
include 'header.php';
?>
<div class="main-container">
    <div class="container p-4 mb-4 mt-4 rounded-5">
        <h2>Isi Keranjang</h2>
        <form action="go_order.php" method="post" id="orderForm">
            <table>
                <tr>
                    <th style="text-align: center; vertical-align: middle;">
                        <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes()">
                    </th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result_keranjang)) : ?>
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">
                            <input type="checkbox" name="selected_items[]" value="<?= $row['id'] ?>">
                        </td>
                        <td>
                            <div style="display: flex; align-items: flex-start;">
                                <img src="data:image/jpeg;base64,<?= base64_encode($row['image_url']) ?>" alt="Product Image" style="margin-right: 10px; max-width: 100px; max-height: 100px;" />
                                <h3><?= $row['name'] ?></h3>
                            </div>
                        </td>
                        <td>
                            <p>Rp<?= number_format($row['price'], 2) ?></p>
                        </td>
                        <td>            
                            <?php
                            if ($row['quantity'] > $row['stock']) {
                                echo '<p style="color: red;">' . $row['quantity'] . ' (Exceeds Stock)</p>';
                            } else {
                                echo '<p>' . $row['quantity'] . '</p>';
                            }
                            ?>
                            </td>
                        <td>Rp<?= number_format($row['harga_seluruh'], 2) ?></td>
                        <td>
                            <a type="button" class="btn btn-danger" href='delete_item_keranjang.php?id=<?= $row['id'] ?>' onclick="return confirmDelete()">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <button type="submit" class="btn btn-primary" name="process_order">Process Order</button>
        </form>
        <script>
            function toggleAllCheckboxes() {
                var checkboxes = document.getElementsByName('selected_items[]');
                var selectAllCheckbox = document.getElementById('selectAll');

                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = selectAllCheckbox.checked;
                }
            }
            function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
    }
        </script>
    </div>
</div>
    <?php mysqli_close($conn); ?>
    <?php
    include 'footer.php';
?>
</body>
</html>
