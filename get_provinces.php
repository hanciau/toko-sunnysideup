<?php
$apiKey = '4776d4c5796fdd1cefe45a4578ea05e5';
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
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

header('Content-Type: application/json'); 
if ($err) {
    echo json_encode(array('error' => 'Error fetching provinces'));
} else {
    $data = json_decode($response, true);
    $provinces = array();

    if (isset($data['rajaongkir']['results'])) {
        foreach ($data['rajaongkir']['results'] as $province) {
            $provinces[] = array(
                'province_id' => $province['province_id'],
                'province_name' => $province['province']
            );
        }
    }

    echo json_encode($provinces);
}
?>