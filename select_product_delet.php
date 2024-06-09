<?php
// Check if the admin is logged in before processing product input
session_start();

if (!isset($_SESSION['admin_id'])) {
    // If the admin is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Replace the following information with your database connection details
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sunnysideup";

// Create a connection to the database
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check the database connection
if ($conn->connect_error) {
    die("Connection to the database failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Contoh Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
    <link href="/path/to/bootstrap-icons.css" rel="stylesheet">

    <style>
        .form-group {
            display: flex;
        }

        .custom-image {
            width: 230px;
            height: 250px;
            object-fit: cover;
        }

        html,
        body {
            display: grid;
            height: 100%;
            width: 100%;
            place-items: center;
            background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
            overflow: auto;
        }

        .equal-image {
            max-width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
            margin: 0 auto;
            margin-top: 20px;
        }

        .category {
            cursor: pointer;
            background-color: #f7f7f7;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
            text-align: center;
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .product-title {
            font-weight: bold;
            font-size: 18px;
        }

        .product-price {
            color: #e63c2e;
            font-size: 20px;
        }

        .category-item {
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 5px; /* Adjust as needed */
        }

        .category-item input[type="checkbox"] {
            margin-right: 5px; /* Adjust as needed */
        }

        .category-item span {
            flex: 1; /* Fill the remaining space inside the label */
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        input[type="checkbox"] {
            margin-right: 5px;
            cursor: pointer;
            /* Add a cursor pointer to indicate that this box is clickable */
        }
    </style>

</head>

<body class="bg-secondary">
    <div class="container p-0 mb-4 mt-4 rounded-5 shadow bg-white">
        <div class="row">
            <h1 class="text-center">Hapus Produk</h1>
        </div>
        <div class="form-group d-flex">
            <div class="search-container">
                <input type="text" class="form-control search-bar" id="productName" name="productName" placeholder="Cari produk...">
                <button type="button" class="btn btn-primary search-button" id="searchButton" onclick="startSearch()"><i class="bi bi-search"></i> Cari</button>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-lg dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Kategori
            </button>
            <div class="dropdown-menu" aria-labelledby="categoryDropdown" style="width: 10px;">
                <?php
                // Fetch category data from the database
                $query = "SELECT * FROM category";
                $result = $conn->query($query);

                echo '<div><center>';
                echo '<div class="row">';
                while ($row = $result->fetch_assoc()) {
                    echo '<div><center>';
                    echo '<div class="col-md-7">'; // Each category gets half-width column
                    echo '<div  class="checkbox-container"  onclick="event.stopPropagation();">';
                    echo '<input type="checkbox" name="searchCategory[]" value="' . $row['category_id'] . '" id="category' . $row['category_id'] . '">';
                    echo '<label for="category' . $row['category_id'] . '">' . $row['category_name'] . '</label>';
                    echo '</div>';
                    echo '</div>';
                    echo '</center></div>';
                }

                echo '</div> <br>';
                echo '<button class="btn btn-primary" id="applyCategoryButton">Apply</button>';
                echo '</center></div>';

                $conn->close();
                ?>
            </div>
        </div>

        <form action="proses_select_product_delete.php" method="post">
            <div class="row row-cols-md-3 row-cols-1 gx-5 p-5">

                <?php
                $servername = "localhost";
                $db_username = "root";
                $db_password = "";
                $db_name = "sunnysideup";

                $conn = new mysqli($servername, $db_username, $db_password, $db_name);

                if ($conn->connect_error) {
                    die("Connection to the database failed: " . $conn->connect_error);
                }

                // Default query to display all products and link products to categories
                $sql = "SELECT product.product_id, product.name, product.description, product.price, product.image_url, 
                            GROUP_CONCAT(category.category_name) AS category_names
                        FROM product
                        LEFT JOIN product_category ON product.product_id = product_category.product_id
                        LEFT JOIN category ON product_category.category_id = category.category_id";

                // Initialize empty search parameters
                $searchCategory = [];
                $searchProductName = "";

                // Check if there's a search request
                if (isset($_GET['searchCategory'])) {
                    $searchCategory = explode(',', $_GET['searchCategory']);
                }
                if (isset($_GET['productName'])) {
                    $searchProductName = $_GET['productName'];
                }

                // Build the WHERE clause for the SQL query based on search parameters
                $whereClause = "";
                if (!empty($searchCategory)) {
                    $searchCategoryString = implode(',', $searchCategory);
                    $whereClause .= "category.category_id IN ({$searchCategoryString}) AND ";
                }
                if (!empty($searchProductName)) {
                    $whereClause .= "product.name LIKE '%$searchProductName%' AND ";
                }

                // Remove trailing "AND" if it exists
                if (!empty($whereClause)) {
                    $whereClause = rtrim($whereClause, "AND ");
                    $sql .= " WHERE " . $whereClause;
                }

                $sql .= " GROUP BY product.product_id"; // Group by product ID to get unique products

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['product_id'];
                        $nama_produk = $row['name'];
                        $deskripsi = $row['description'];
                        $harga = $row['price'];
                        $gambar = $row['image_url'];
                        $kategori = $row['category_names']; // Concatenated category names
                
                        echo '<div class="col-md-3 mb-3">';
                        echo '<div class="product-card">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($gambar) . '" class="product-image" />';
                        echo '<div class="product-title">' . $nama_produk . '</div>';
                        echo '<div class="product-price">Rp. ' . $harga . '</div>';
                        echo '<p class="card-text">' . $deskripsi . '</p>';
                        echo '<p class="card-text"><strong>Categories: </strong>' . $kategori . '</p>';
                
                        // Check if product exists in order_item or keranjan
                        $order_item_check_query = "SELECT * FROM order_item WHERE product_id = $product_id";
                        $order_item_result = $conn->query($order_item_check_query);
                
                        $keranjang_check_query = "SELECT * FROM keranjang WHERE product_id = $product_id";
                        $keranjang_result = $conn->query($keranjang_check_query);
                
                        // Check if the query execution is successful
                        if ($order_item_result === false || $keranjang_result === false) {
                            die("Error in query execution: " . $conn->error);
                        }
                
                        // Check if the product exists in order_item or keranjang
                        if ($order_item_result->num_rows > 0 || $keranjang_result->num_rows > 0) {
                            echo '<p style="color: red;">Produk tidak dapat dihapus karena sudah ada dalam pesanan atau keranjang.</p>';
                        } else {
                            echo '<input type="checkbox" name="products_to_delete[]" value="' . $product_id . '"> Hapus';
                        }
                
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "Tidak ada produk yang sesuai dengan pencarian.";
                }
                

                $conn->close();
                ?>
            </div>
            <button type="submit" class="btn btn-danger">Hapus Produk Terpilih</button>
        </form>
    </div>
        <script>
    document.addEventListener("DOMContentLoaded", function () {
        const productName = document.getElementById("productName");
        const categoryDropdown = new bootstrap.Dropdown(document.getElementById("categoryDropdown"), {
            offset: [0, 2], // Sesuaikan offset sesuai kebutuhan
        });

        const categoryCheckboxes = document.querySelectorAll('input[name="searchCategory[]"]');
        const applyCategoryButton = document.getElementById("applyCategoryButton");
        let categoryChanged = false;

        categoryCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                categoryChanged = true;
            });
        });

        productName.addEventListener("input", function () {
            categoryChanged = true;
        });

        applyCategoryButton.addEventListener("click", function () {
            startSearch();
        });

        // Add this event listener for the "Cari" button
        const searchButton = document.getElementById("searchButton");
        searchButton.addEventListener("click", function () {
            startSearch();
        });

        // Add an event listener for the "Enter" key
        document.getElementById("productName").addEventListener("keyup", function (event) {
            if (event.key === "Enter") {
                startSearch();
            }
        });

        // Tambahkan event listener pada item dropdown
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function (event) {
                event.stopPropagation(); // Hindari menutup dropdown saat item diklik
            });
        });

        function startSearch() {
            const selectedCategories = Array.from(categoryCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const enteredProductName = productName.value.trim(); // Trim leading and trailing spaces

            let url = "katalog.php?";
            if (selectedCategories.length > 0) {
                url += `searchCategory=${selectedCategories.join(",")}&`;
            }
            if (enteredProductName) {
                url += `productName=${enteredProductName}`;
            }

            window.location.href = url;
        }
    });

    document.addEventListener("click", function (event) {
        // Tutup dropdown jika pengguna mengklik di luar dropdown
        if (!event.target.closest('.dropdown')) {
            const categoryDropdown = new bootstrap.Dropdown(document.getElementById("categoryDropdown"));
            categoryDropdown.hide();
        }
    });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
    </body>

    </html>
