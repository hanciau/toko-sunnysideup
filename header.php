<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        nav div {
            display: flex;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: #333;
            padding: 10px 15px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #eee;
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
<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
    <div class="container">
        <a href='profil_prusahaan.php' class="text-decoration-none text-dark ml-2">
            <img src="sunnylogo.png" width="126" height="82" alt="Sunny Logo">
        </a>
        <ul class="navbar-nav">
            <li class="nav-item d-flex align-items-center">
                <?php
                if (!empty($_SESSION['customer_id'])) {
                    $customerId = $_SESSION['customer_id'];
                    $customerQuery = "SELECT real_name, image FROM customer WHERE customer_id = $customerId";
                    $customerResult = $conn->query($customerQuery);

                    if ($customerResult && $customerResult->num_rows > 0) {
                        $customerData = $customerResult->fetch_assoc();
                        $customerName = $customerData['real_name'];
                        $customerImage = $customerData['image'];

                        echo "<a href='profile.php?id=$customerId' class='text-decoration-none text-dark d-flex align-items-center'>";
                        echo "<span>$customerName</span>";
                        echo "<img src='data:image/jpeg;base64," . base64_encode($customerImage) . "' class='profile-image' alt='Profile Image' />";
                        echo "</a>";
                        echo "<a href='logout.php' class='text-decoration-none text-dark '>Logout</a>";
                    }
                } else {
                    echo "<div class='ml-auto'>";
                    echo "<a href='halaman_awal.php' class='text-decoration-none text-dark'>Login/Sign Up</a>";
                    echo "</div>";
                }
                ?>
            </li>
            <li class="nav-item d-flex align-items-center ml-auto">
                <a href='katalog.php' class="text-decoration-none text-dark ml-2">
                    Product
                </a>
                <a href='https://api.whatsapp.com/send?phone=6282165392323&text=Halo,saya ingin custom produk.' class="text-decoration-none text-dark">
                    custom<br>Produk
                </a>
                <a href='keranjang_customer.php' class="text-decoration-none text-dark ml-2">
                    <img src='pngegg.png' width='30' height='30' alt='Shopping Cart Icon' style='margin-bottom: -5px;' />
                </a>
            </li>
        </ul>
    </div>
</nav>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
