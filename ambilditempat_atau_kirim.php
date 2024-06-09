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
            height: 100vh;
            margin: 0;
            overflow: auto;
            font-family: Arial, sans-serif;
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

        header, footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        .main-container {
            background-color: #fff;
            min-height: calc(70vh - 2 * 15px);
            padding: 20px;
            box-sizing: border-box;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto; 
            max-width: 550px; 
        }
    </style>
</head>
<?php
include 'header.php';
?>
<body>
    <div class="main-container p-0 mb-4 mt-4 rounded-5">
        <h2 class="text">Checkout:</h2><br>
        
        <div id="alamat_pengiriman2" style="display:block;" class="text mx-auto">
            <h3>Pilih Alamat Pengiriman:</h3>
            <label for="alamat_pengiriman_select">
                <?php
                $queryCustomerAddresses = "SELECT * FROM customer_address WHERE customer_id = $customer_id";
                $resultCustomerAddresses = mysqli_query($conn, $queryCustomerAddresses);

                if ($resultCustomerAddresses && mysqli_num_rows($resultCustomerAddresses) > 0) {
                    echo '<label for="alamat_pengiriman_select"></label>';
                    echo '<select name="alamat_pengiriman_select" id="alamat_pengiriman_select" onchange="updateSessionKotaId()">';
                    echo '<option value="" disabled selected hidden>Pilih Alamat</option>'; 

                    while ($rowAddress = mysqli_fetch_assoc($resultCustomerAddresses)) {
                        $addressId = $rowAddress['address_id'];
                        $kotaid = $rowAddress['kota_id'];
                        $fullAddress = "{$rowAddress['address']}, {$rowAddress['kota']}, {$rowAddress['provinsi']}, {$rowAddress['postal_code']}";

                        $selected = ($_SESSION['address_id'] == $addressId && $_SESSION['kota_Id'] == $kotaid) ? 'selected' : '';

                        echo "<option value='$kotaid' $selected data-address-id='$addressId'>$fullAddress</option>";
                    }

                    echo '</select><br>';
                } else {
                    echo 'No addresses available';
                }
                ?>
            </label>
            <script>
                function updateSessionKotaId() {
                    var select = document.getElementById("alamat_pengiriman_select");
                    var selectedOption = select.options[select.selectedIndex];
                    var selectedKotaId = selectedOption.value;
                    var selectedAddressId = selectedOption.getAttribute('data-address-id');

                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "update_session_kota_id.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log(xhr.responseText);
                        }
                    };

                    var params = "selected_kota_id=" + encodeURIComponent(selectedKotaId) +
                        "&selected_address_id=" + encodeURIComponent(selectedAddressId);
                    xhr.send(params);

                    updateSubmitButton();
                }

                function updateSubmitButton() {
                    var selectKurir = document.getElementById("kurir");
                    var submitButton = document.getElementById("submitBtn");

                    submitButton.disabled = (selectKurir.value === '');
                }
            </script>
        </div>

        <div class="text mx-auto">
            <form id="kurirForm" action="update_kurir.php" method="post">
                <label for="kurir">Pilih Kurir:</label>
                <select name="kurir" id="kurir" onchange="updateSubmitButton()">
                    <option value="" disabled selected hidden>Pilih Kurir</option>
                    <option value="jne">JNE</option>
                    <option value="tiki">TIKI</option>
                    <option value="pos">Pos Indonesia</option>
                </select><br><br>
                <button type="submit" id="submitBtn" disabled>Lanjut</button>
            </form>
        </div>
        <div style='display:none;' class="text-center mx-auto"><?php include 'display_selected_items.php'; ?></div>
    </div>

    <?php
    include 'footer.php';
    $store = "SELECT * FROM store_address";
$result = mysqli_query($conn, $store);
$storeAddressData = mysqli_fetch_assoc($result);
$selectedCourierCityId = $storeAddressData['city_id'];
$_SESSION['origin'] = $selectedCourierCityId;
mysqli_close($conn);
    ?>

    <script>
        function updateSessionKurir() {
            var select = document.getElementById("kurir");
            var selectedOption = select.options[select.selectedIndex];
            var selectedKurir = selectedOption.value;
            var addressSelect = document.getElementById("alamat_pengiriman_select");
            var selectedAddress = addressSelect.options[addressSelect.selectedIndex];

            if (selectedAddress.value === "") {
                alert("Please select an address before continuing.");
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_kurir.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);

                    loadGetCoastContent();
                }
            };

            var params = "kurir=" + encodeURIComponent(selectedKurir);
            xhr.send(params);
        }
    </script>
</body>
</html>
