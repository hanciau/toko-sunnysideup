<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        #avatar-container {
            border-radius: 50%;
            width: 100px; 
            height: 100px; 
            object-fit: cover;
            margin-bottom: 20px;
    
        }
        #avatar-container img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            background-color: #ee4d2d;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        button:hover {
            background-color: #c33822;
        }

        #addressContainer {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php
session_start();

function redirectToCatalog() {
    header("Location: katalog.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "sunnysideup");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (empty($_SESSION['customer_id'])) {
    redirectToCatalog();
}

$result = $mysqli->query("SELECT * FROM customer WHERE customer_id = {$_SESSION['customer_id']}");

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editProfile'])) {
        $editName = $_POST['editName'];
        $editEmail = $_POST['editEmail'];
        $editTelephone = $_POST['editTelephone'];

        $updateProfileQuery = "UPDATE customer SET real_name='$editName', email='$editEmail', telephone='$editTelephone' WHERE customer_id={$_SESSION['customer_id']}";
        if ($mysqli->query($updateProfileQuery) === TRUE) {
            echo "Profile updated successfully";
        } else {
            echo "Error updating profile: " . $mysqli->error;
        }
    }
} else {
    echo "Error fetching user data.";
}

$mysqli->close();
?>

<h1>Edit Your Profile</h1>
<form action="update_profile.php" method="post" enctype="multipart/form-data">
    <center>
    <div id="avatar-container">
    <img id="image-preview" src="#" alt="Preview Gambar" style="display: none;">
    </div>
</center>
    <label for="avatar">Choose Avatar: <br> (jpg only, rekomendasi 4X6)</label>
    <input type="file" id="image" name="image">

    <label for="editName">Name:</label>
    <input type="text" id="editName" name="editName" value="<?php echo $user['real_name']; ?>" required>

    <label for="editEmail">Email:</label>
    <input type="email" id="editEmail" name="editEmail" value="<?php echo $user['email']; ?>" required>

    <label for="editTelephone">Telephone:</label>
    <input type="tel" id="editTelephone" name="editTelephone" value="<?php echo $user['telephone']; ?>" required pattern="[0-9]+" title="Please enter only numbers">
    <br>

    <button type="submit" name="editProfile">Update Profile</button>
</form>
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
