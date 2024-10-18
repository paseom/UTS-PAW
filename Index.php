<?php
session_start(); // Memulai sesi

// Cek apakah form sudah dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    try {
        require_once "dbh.php"; // Menghubungkan ke database

        // Query untuk memeriksa apakah pengguna ada dalam database
        $query_login = "SELECT * FROM pengguna WHERE NAMA = :nama";
        $stmt = $pdo->prepare($query_login);
        
        // Mengikat parameter
        $stmt->bindParam(":nama", $nama);

        // Eksekusi query
        $stmt->execute();

        // Cek apakah pengguna ditemukan
        if ($stmt->rowCount() > 0) {
            // Mengambil data pengguna
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Cek password
            if ($password === $user['PASSWORD']) {
                // Simpan data pengguna ke dalam sesi
                $_SESSION['user_nama'] = $user['NAMA']; // Simpan nama pengguna
                // Redirect ke halaman yang diinginkan setelah berhasil login
                header("Location: Home.php"); // Ganti dengan halaman yang ingin dituju
                exit();
            } else {
                // Jika password salah
                $error = "Nama pengguna atau password salah.";
            }
        } else {
            // Jika nama pengguna tidak ditemukan
            $error = "Anda belum punya akun.";
        }

        $pdo = null;
        $stmt = null;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Index.css">
    <title>Log In</title>
</head>
<body>
    <h3>Log In</h3>
    <form action="Index.php" method="post"> <!-- Ganti action ke LogIn.php -->
        <label for="nama">Nama: </label>
        <input type="text" name="nama" required>

        <label for="pass">Password: </label>
        <input type="password" name="password" required>

        <input type="submit" value="Masuk">
    </form>

    <p>Belum memiliki akun? <a href="Register.php">Daftar</a></p>

    <?php
    // Menampilkan error jika ada
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>
</body>
</html>