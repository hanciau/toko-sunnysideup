<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sunnysideup");
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Shopee Style</title>
    <style>
        /* Gaya CSS untuk halaman ini saja */
        .profil-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            text-align: center;
            margin-top: 20px; /* Add some margin at the top */
        }

        .profile-image {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #777;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        label {
            flex: 1;
            margin-bottom: 10px;
        }

        input, select {
            flex: 3;
            padding: 10px;
            margin-bottom: 20px;
        }

        button {
            background-color: #ee4d2d;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        button:hover {
            background-color: #c33822;
        }

        #map {
            height: 400px;
            margin-top: 20px;
        }

        .address-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .utama-profile-image {
            border-radius: 50%;
            width: 100px; /* Sesuaikan dengan lebar gambar */
            height: 100px; /* Sesuaikan dengan tinggi gambar */
            object-fit: cover;
            margin-bottom: 20px;
        }

        header, footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        .main-container {
            background-color: #fff; /* Set the background color of the container */
            min-height: calc(100vh - 2 * 15px); /* Calculate the remaining height excluding header and footer */
            padding: 20px; /* Adjust the padding as needed */
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<?php

$conn = new mysqli("localhost", "root", "", "sunnysideup");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (empty($_SESSION['customer_id'])) {
    redir("katalog.php");
}


$result = $conn->query("SELECT * FROM customer WHERE customer_id = {$_SESSION['customer_id']}");

if ($result && $result->num_rows > 0) 
    $user = $result->fetch_assoc();

    $addressQuery = "SELECT * FROM customer_address WHERE customer_id = {$_SESSION['customer_id']}";
    $addressResult = $conn->query($addressQuery);

?>
            <center>
<div class="profil-container rounded-5">
    <div>
        <div class="row">
        <div>

                <?php
                // Tampilkan gambar profil dari kolom 'image' pada tabel 'customer'
                $gambar = $user['image'];
                
                // Periksa apakah ada gambar
                if (!empty($gambar)) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($gambar) . '" class="utama-profile-image" alt="Profile Image" />';
                }
                ?>

                <h3>Profile :</h3>
                <hr>
                <br>
                <div style="margin-top:-20px;">
                    <table class="table table-striped" style="text-align: left;">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?php echo $user['real_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                        <tr>
                            <td>Telephone</td>
                            <td>:</td>
                            <td><?php echo $user['telephone']; ?></td>
                        </tr>

                        <?php
                        include('table_address.php');
                        ?>
                    </table>
                    <button id="editProfileBtn">Edit Profile</button>
                    <button id="addAddressBtn">Tambah Alamat</button>

                    <!-- Container untuk formulir alamat -->
                    <div id="addressContainer"></div>
                </div>
           
            </div>
        </div>
    </div>
</div>
</center>
<?php
    // Retrieve orders for the current customer
    $orderQuery = "SELECT * FROM `order` WHERE customer_id = $customerId";
    $orderResult = $conn->query($orderQuery);
    ?>
    <div class="profil-container rounded-5">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <h3>Lihat Pesanan :</h3>
                    <hr>
                    <br>
                    <div class="col-md-6 content-menu" style="margin-top:-20px;">
                        <table class="table table-striped" style="text-align: left;">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Tanggal Order</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($order = $orderResult->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$order['order_id']}</td>";
                                    echo "<td>{$order['order_date']}</td>";
                                    echo "<td>{$order['total_biaya']}</td>";
                                    echo "<td>{$order['status']}</td>";
                                    echo "<td>";

                                    if ($order['status'] == 'Pending') {
                                        echo "<button class='btn btn-primary' onclick='bayarPesanan({$order['order_id']})'>Bayar</button>";
                                    }
    
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    function bayarPesanan(orderId) {
        // Perbaikan pada URL
        window.location.href = 'http://localhost/pemrogrammanweb/sunnysideup/payment.php?order_id=' + orderId;
    }
</script>

    <?php
    include 'footer.php';
    ?>
<script>
    document.getElementById('editProfileBtn').addEventListener('click', function () {
        window.location.href = 'edit_profil.php';
    });

    document.getElementById('addAddressBtn').addEventListener('click', function () {
        window.location.href = 'tambah_alamat.php';
    });

</script>


</body>
</html>
