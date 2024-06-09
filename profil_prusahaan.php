<?php
session_start();

// Connect to the database (replace with your actual database connection code)
$conn = mysqli_connect("localhost", "root", "", "sunnysideup");

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - Shopee Style</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="/path/to/bootstrap-icons.css">
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            place-items: center;
            overflow: auto;
        }

        header,
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        .main-container {
            background-color: #fff; /* Set the background color of the container */
            border: 1px solid #ccc; /* Add border for the box */
            border-radius: 10px; /* Optional: Add border-radius for rounded corners */
            overflow: hidden; /* Optional: Hide overflow content */
            padding: 20px; /* Adjust the padding as needed */
            box-sizing: border-box;
            margin: auto; /* Optional: Center the box on the page */
            max-width: 80%; 
        }

        .main-container img {
            max-width: 50%; /* Make sure images don't overflow the container */
            height: auto; /* Maintain image aspect ratio */
            border-radius: 5px; /* Optional: Add border-radius for rounded image corners */
            margin: auto;
        }

        .main-container p {
            text-align: center; /* Optional: Justify text within the container */
        }

        .contact-info {
            padding-left: 20px; /* Optional: Add padding for better spacing */
        }
    </style>
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class="main-container rounded-5">
<div class="row">

            <center>
            <h2>Tentang Kami</h2><br>
            <img src="Picture1.png">
       
            <p>Rumah kado (Sunnysideup.house): <br>
                <b><i>Beautiful gift for your beautiful moment <br></i></b>
                Menjual berbagai jenis kado mulai dari bouquet, hampers, serta kado untuk pernikahan. Menjual kado
                dengan harga murah namun kualitas bagus. Bouquet dan hamper yang dijual mulai dari harga Rp 25.000.<br>
                <b><i>Exclusive Hantaran Box by Sunnysideup.gallery<br></i></b>
                Penyewaan box hantaran. Tersedia box 3 jenis box hantaran dengan biaya sewa box mulai dari Rp 25.000 per box.<br>
                <b>Alamat:</b> <br>
                <a href="https://maps.app.goo.gl/cHe3JLdCyXKR7sjb6">Dolok Sinumbah, Hutabayu Raja, Simalungun Regency, North Sumatra 21184 Perdagangan, Dolok Sinumbah, Kec. Huta Bayu Raja, Kabupaten Simalungun, Sumatera Utara 21184</a><br>
               <b>Pemilik</b><br>
                dr. Fitri Nirwana Sinaga, MKM
            </p>
            </center>
        </div>
    </div>
    <?php
    include 'footer.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>
