<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: halaman_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        .card-text {
            font-size: 0.875rem; 
            overflow-y: auto; 
        }
        html, body {
            display: grid;
            height: 100%;
            width: 100%;
            place-items: center;
            background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
            overflow: auto;
        }
        ::selection {
            background: #1a75ff;
            color: #fff;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            border: 2px solid #007BFF;
            border-radius: 15px;
            padding: 20px;
            max-width: 400px;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden;
        }
        .image-container {
            flex: 1;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .preview {
            text-align: center;
        }
        #image-preview {
        width: 356px;
        height: 442px; 
         }
        .form-group {
            margin: 0; 
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-7" style="color: white;">Masukkan Produk</h1>
        <form action="insert_product.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" style="color: white;">Nama Barang:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description" style="color: white;">Deskripsi:</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="price" style="color: white;">Harga:</label>
                <input type="number" name="price" id="price" class="form-control" step="1000" required>
            </div>
            <div class="form-group">
                <label for="berat" style="color: white;">Berat:</label>
                <input type="number" name="berat" id="berat" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="stock" style="color: white;">Stok:</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>
            <div>
                <label for="image" style="color: white;" >Gambar:</label>
                <input type="file" style="color: white;" name="image" id="image" accept="image/*" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <br>
        </form>
    </div>
    <div class="container">
        <div class="preview">
            <img id="image-preview" src="#" alt="Preview Gambar" style="display: none;">
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('image').addEventListener('change', function() {
            var imagePreview = document.getElementById('image-preview');
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    </script>
</body>
</html>
