<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Register.css">
    <title>Register</title>
</head>
<body>
    <h3>Register</h3>
    <form action="Form.php" method="post">
        <label for="nama">Nama: </label>
        <input type="text" name="nama" required><br>

        <label for="email">Email: </label>
        <input type="email" name="email" required><br>

        <label for="pass">Password: </label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Daftar">
    </form>

    <p>Sudah memiliki akun? <a href="LogIn.php">Log In</a></p>
</body>
</html>

