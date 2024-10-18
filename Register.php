<?php
$error_akun = 'Akun Anda sudah terdaftar!';
$error_email = 'Email Anda sudah terdaftar!';
$error_nama = 'Nama Anda sudah terdaftar!';
$error_message = ''; // Variabel untuk menyimpan pesan error

// Cek apakah form sudah dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        require_once "dbh.php"; // Menghubungkan ke database

        // Cek apakah email sudah terdaftar
        $query_check_email = "SELECT * FROM pengguna WHERE email = :email";
        $stmt_check_email = $pdo->prepare($query_check_email);
        $stmt_check_email->bindParam(":email", $email);
        $stmt_check_email->execute();

        if ($stmt_check_email->rowCount() > 0) {
            // Email sudah terdaftar
            $user_email = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

            // Jika email dan nama sama
            if ($user_email['EMAIL'] == $email && $user_email['NAMA'] == $nama) {
                $error_message = $error_akun;
            } 
            // Jika hanya email yang sama
            elseif ($user_email['email'] == $email) {
                $error_message = $error_email;
            }
        } else {
            // Jika email belum terdaftar, cek nama
            $query_check_nama = "SELECT * FROM pengguna WHERE nama = :nama";
            $stmt_check_nama = $pdo->prepare($query_check_nama);
            $stmt_check_nama->bindParam(":nama", $nama);
            $stmt_check_nama->execute();

            if ($stmt_check_nama->rowCount() > 0) {
                // Nama sudah terdaftar
                $error_message = $error_nama;
            } else {
                // Jika email dan nama belum terdaftar, lanjutkan pendaftaran
                $query_pengguna = "INSERT INTO pengguna (nama, email, password) VALUES (:nama, :email, :password)";
                $stmt = $pdo->prepare($query_pengguna);

                $stmt->bindParam(":nama", $nama);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $password);

                $stmt->execute();

                // Redirect ke halaman Log In setelah berhasil mendaftar
                header("Location: Index.php");
                exit();
            }
        }

        // Close connection
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
    <link rel="stylesheet" href="Register.css">
    <title>Register</title>
</head>
<body>
    <h3>Register</h3>
    <form action="Register.php" method="post">
        <label for="nama">Nama: </label>
        <input type="text" name="nama" value="<?php echo isset($nama) ? htmlspecialchars($nama) : ''; ?>" required>

        <label for="email">Email: </label>
        <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

        <label for="pass">Password: </label>
        <input type="password" name="password" required>

        <?php
        // Menampilkan error jika ada
        if (!empty($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>

        <input type="submit" value="Daftar">
    </form>

    <p>Sudah memiliki akun? <a href="Index.php">Log In</a></p>
</body>
</html>
