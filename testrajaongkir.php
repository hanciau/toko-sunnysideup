<?php
// Masukkan kunci API RajaOngkir
$apiKey = '4776d4c5796fdd1cefe45a4578ea05e5';

// Tentukan URL endpoint untuk mendapatkan daftar kota
$url = 'https://api.rajaongkir.com/starter/city';

// Konfigurasi permintaan HTTP
$options = array(
    'http' => array(
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n" . 
                    "key: $apiKey\r\n",
        'method' => 'GET'
    )
);

// Buat konteks HTTP
$context = stream_context_create($options);

// Kirim permintaan HTTP
$response = file_get_contents($url, false, $context);

// Periksa apakah respons berhasil
if ($response === FALSE) {
    echo "Gagal melakukan permintaan HTTP.";
} else {
    // Ubah respons JSON menjadi array asosiatif
    $result = json_decode($response, true);

    // Periksa apakah respons memiliki data kota
    if ($result['rajaongkir']['status']['code'] == 200) {
        // Lakukan iterasi melalui data kota
        foreach ($result['rajaongkir']['results'] as $city) {
            // Cetak ID kota dan nama kota
            echo "ID Kota: " . $city['city_id'] . ", Nama Kota: " . $city['city_name'] . "<br>";
        }
    } else {
        echo "Gagal mendapatkan daftar kota. Kode status: " . $result['rajaongkir']['status']['code'];
    }
}
?>
