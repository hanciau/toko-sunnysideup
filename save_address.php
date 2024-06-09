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

$addressLabel = $_POST['newAddressLabel'];
$receiverName = $_POST['newReceiverName'];
$phoneNumber = $_POST['newPhoneNumber'];
$address = $_POST['newAddress'];
$province_id = $_POST['province_id'];
$city_id = $_POST['city'];
$postalCode = $_POST['newPostalCode'];
$customer_id = $_SESSION['customer_id']; // Assuming this is stored in the session

// Use prepared statement to prevent SQL injection
$sqlAddress = "INSERT INTO customer_address (customer_id, label, receiver_name, phone_number, address, kota, provinsi, postal_code, kota_id, provinsi_id)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sqlAddress);
if ($stmt) {
    // Call RajaOngkir API to get city and province names
    $apiKey = '4776d4c5796fdd1cefe45a4578ea05e5'; // Replace with your Raja Ongkir API key
    $cityName = getCityName($apiKey, $city_id);
    $provinceName = getProvinceName($apiKey, $province_id);

    $stmt->bind_param("isssssssii", $customer_id, $addressLabel, $receiverName, $phoneNumber, $address, $cityName, $provinceName, $postalCode, $city_id, $province_id);

    if ($stmt->execute()) {
        echo "Address saved successfully";
    } else {
        echo "Error inserting address: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $mysqli->error;
}

$mysqli->close();

// Function to get city name from RajaOngkir API
function getCityName($apiKey, $city_id) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/city?id=$city_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: $apiKey"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "Error fetching city";
    } else {
        $data = json_decode($response, true);
        return isset($data['rajaongkir']['results']['city_name']) ? $data['rajaongkir']['results']['city_name'] : "City not found";
    }
}

// Function to get province name from RajaOngkir API
function getProvinceName($apiKey, $province_id) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/province?id=$province_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 70,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: $apiKey"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "Error fetching province";
    } else {
        $data = json_decode($response, true);
        return isset($data['rajaongkir']['results']['province']) ? $data['rajaongkir']['results']['province'] : "Province not found";
    }
}
echo $provinceName;
?>
