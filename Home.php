<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_nama'])) {
    header("Location: LogIn.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Home.css">
    <title>Home</title>
</head>
<body>
    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_nama']); ?>!</h1>
    <form action="List.php">
        <input type="submit" value="Lihat List Makanan">
    </form>
    <br>
    <form action="LogOut.php">
        <input type="submit" value="Log Out">
    </form>
</body>
</html>
