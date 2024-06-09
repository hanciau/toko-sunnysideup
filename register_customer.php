<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Customer</title>
</head>
<body>
    <h1>Pendaftaran Customer</h1>
    <form action="process_register_customer.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <input type="submit" value="Daftar sebagai Customer">
    </form>
</body>
</html>

