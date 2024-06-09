<?php
session_start();

function redir($url) {
    header("Location: $url");
    exit();
}

if (empty($_SESSION['customer_id'])) {
    redir("halaman_awal.php");
}

$mysqli = new mysqli("localhost", "root", "", "sunnysideup");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Address</title>
</head>
<style>
/* General form styles */
.address-form {
  width: 500px;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.address-form label {
  display: block;
  margin-bottom: 10px;
  font-weight: 600;
}

.address-form select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.address-form button {
  background-color: #000;
  color: #fff;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.address-form input {
  width: 96%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}


</style>

<body>
    <div class="address-form" id="addressFormContainer">
    <center><h1>Create Address</h1></center>
        <form action="save_address.php" method="post">
            <label for="newAddressLabel">Pilih Label Alamat:</label>
            <select id="newAddressLabel" name="newAddressLabel">
                <option value="Home">Home</option>
                <option value="Work">Work</option>
                <!-- Add other options as needed -->
            </select>

            <label for="newReceiverName">Nama Penerima:</label>
            <input type="text" id="newReceiverName" name="newReceiverName" required>

            <label for="newPhoneNumber">Nomor Telepon:</label>
            <input type="tel" id="newPhoneNumber" name="newPhoneNumber" required pattern="[0-9]+" title="Please enter only numbers">

            <label for="newAddress">Alamat Lengkap:</label>
            <input type="text" id="newAddress" name="newAddress" required>

            <label for="province">Pilih Provinsi:</label>
            <select id="province_id" name="province_id" onchange="populateCities()">
            </select>

            <label for="city">Pilih Kota:</label>
            <select id="city" name="city">
            </select>

            <label for="newPostalCode">Kode Pos:</label>
            <input type="text" id="newPostalCode" name="newPostalCode" required pattern="[0-9]+" title="Please enter only numbers">
            <center>
            <button type="submit">Simpan Alamat</button>
            </center>
        </form>
        <hr>
    </div>

    <script>
        function populateProvinces() {
            var provinceSelect = document.getElementById('province_id');

            fetch('get_provinces.php')
                .then(response => response.json())
                .then(data => {
                    provinceSelect.innerHTML = '<option value="" selected disabled>Pilih Provinsi</option>';
                    data.forEach(province => {
                        var option = new Option(province.province_name, province.province_id);
                        provinceSelect.add(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching provinces:', error.message);
                    provinceSelect.innerHTML = '<option value="" selected disabled>Error fetching provinces</option>';
                });
        }

        function populateCities() {
            var provinceSelect = document.getElementById('province_id');
            var citySelect = document.getElementById('city');

            // Clear existing city options
            citySelect.innerHTML = '<option value="" selected disabled>Loading...</option>';

            // Check if a province is selected
            if (provinceSelect.value !== '') {
                var provinceId = provinceSelect.value;

                fetch(`get_cities.php?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="" selected disabled>Pilih Kota</option>';
                        data.forEach(city => {
                            var option = new Option(city.city_name, city.city_id);
                            citySelect.add(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error.message);
                        citySelect.innerHTML = '<option value="" selected disabled>Error fetching cities</option>';
                    });
            } else {
                // If no province is selected, disable the city select
                citySelect.innerHTML = '<option value="" selected disabled>Pilih Provinsi terlebih dahulu</option>';
            }
        }

        // Populate provinces on page load
        populateProvinces();
    </script>
</body>

</html>
