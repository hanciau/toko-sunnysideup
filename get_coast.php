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
            align-items: center;
            justify-content: center;
            overflow: auto;
            font-family: Arial, sans-serif;
        }

        h2, h3 {
            color: #333;
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

        header, footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        .main-container {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            min-height: calc(70vh - 2 * 15px);
            padding: 20px;
            box-sizing: border-box;
            max-width: 100%;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="main-container p-0 mb-4 mt-4 rounded-5">
        <div>
            <?php
            $origin = '151';
            $destination = isset($_SESSION['destinasion']) ? $_SESSION['destinasion'] : (isset($_GET['destinasion']) ? $_GET['destinasion'] : '');
            $weight = isset($_SESSION['total_weight']) ? $_SESSION['total_weight'] : (isset($_GET['total_weight']) ? $_GET['total_weight'] : '');
            $courier = isset($_SESSION['kurir']) ? $_SESSION['kurir'] : (isset($_GET['kurir']) ? $_GET['kurir'] : '');

            
                    $api_key = "4776d4c5796fdd1cefe45a4578ea05e5"; 
                    $url = "https://api.rajaongkir.com/starter/cost";

                    $data = [
                        'origin' => $origin,
                        'destination' => $destination,
                        'weight' => $weight,
                        'courier' => $courier,
                    ];

                    $headers = [
                        "content-type: application/x-www-form-urlencoded",
                        "key: $api_key",
                    ];

                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => http_build_query($data),
                        CURLOPT_HTTPHEADER => $headers,
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo json_encode(array('error' => "Error fetching shipping services: $err"));
            } else {
                $result = json_decode($response, true);

                if ($result['rajaongkir']['status']['code'] == 200) {
                    $services = $result['rajaongkir']['results'][0]['costs'];
                    
                    echo '<div style="border: 1px solid #ddd; padding: 15px; border-radius: 10px; margin-top: 20px; text-align: center;">';
                    echo '<h3>Pilih Jenis Paket:</h3>';
                    echo '<form action="update_ongkir.php" method="post">';
                    echo '<div style="margin-bottom: 15px; display: inline-block;">';
                    
                    echo '<label for="selected_service">Jenis Paket:</label>';
                    echo '<select name="selected_service" id="selected_service" style="width: 80%;">';
                    foreach ($services as $service) {
                        $serviceName = $service['service'];
                        $serviceDescription = $service['description'];
                        $serviceCost = $service['cost'][0]['value'];
                        $etd = $service['cost'][0]['etd'];
                    
                        echo "<option value='$serviceName' data-cost='$serviceCost'>$serviceDescription - Rp $serviceCost (Est. $etd days)</option>";
                    }
                    
                    echo '</select>';
                    echo '</div>';
                    echo '<input type="hidden" name="selected_service_cost" id="selected_service_cost" value="">';
                    echo '<input type="submit" name="submit_service" value="Pilih">';
                    echo '</form>';
                    echo '</div>';
                } else {
                    echo json_encode(array('error' => "Error in API response: {$result['rajaongkir']['status']['description']}"));
                }
                
            }
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                var select = document.querySelector('select[name="selected_service"]');
                var hiddenInput = document.getElementById('selected_service_cost');

                function updateSessionAndSubmit() {
                    var selectedOption = select.options[select.selectedIndex];
                    var selectedService = selectedOption.value;
                    var selectedCost = selectedOption.getAttribute('data-cost');

                    hiddenInput.value = selectedCost;

                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "update_ongkir.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log(xhr.responseText);
                        }
                    };

                    var params = "selected_service=" + encodeURIComponent(selectedService) +
                        "&selected_service_cost=" + encodeURIComponent(selectedCost);
                    xhr.send(params);
                }

                select.addEventListener('change', function () {
                    updateSessionAndSubmit();
                });

                updateSessionAndSubmit();
            });

            </script>
        </div>
    </div>
<?php
include 'footer.php';
?>
</body>
</html>