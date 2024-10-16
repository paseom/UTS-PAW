<?php
// Cek apakah form sudah dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        require_once "dbh.php";

        // Cek apakah email sudah terdaftar
        $query_check_email = "SELECT * FROM pengguna WHERE email = :email";
        $stmt_check_email = $pdo->prepare($query_check_email);
        $stmt_check_email->bindParam(":email", $email);
        $stmt_check_email->execute();

        if ($stmt_check_email->rowCount() > 0) {
            $user_email = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

            // Jika email, nama, dan password semuanya sama
            if ($user_email['EMAIL'] == $email && $user_email['NAMA'] == $nama && $user_email['PASSWORD'] == $password) {
                echo "<p>Akun Anda sudah terdaftar!</p>";
                echo '<Form action="LogIn.php" method="get">
                <input type="submit" value="Log In"> </Form>';
            } 
            // Jika hanya email yang sama
            elseif ($user_email['EMAIL'] == $email) {
                echo "<p>Email Anda sudah terdaftar!</p>";
                echo '<Form action="LogIn.php" method="get">
                    <input type="submit" value="Log In"> </Form>';
            }
        } else {
            // Jika email belum terdaftar, cek nama
            $query_check_nama = "SELECT * FROM pengguna WHERE nama = :nama";
            $stmt_check_nama = $pdo->prepare($query_check_nama);
            $stmt_check_nama->bindParam(":nama", $nama);
            $stmt_check_nama->execute();

            if ($stmt_check_nama->rowCount() > 0) {
                $user_nama = $stmt_check_nama->fetch(PDO::FETCH_ASSOC);

                // Jika nama sudah terdaftar
                echo "<p>Nama anda sudah terdaftar!</p>";
                echo '<Form action="LogIn.php" method="get">
                <input type="submit" value="Log In"> </Form>';
            } else {
                // Jika email dan nama belum terdaftar, lanjutkan pendaftaran
                $query_pengguna = "INSERT INTO pengguna (NAMA, EMAIL, PASSWORD) VALUES (:nama, :email, :password)";
                $stmt = $pdo->prepare($query_pengguna);

                $stmt->bindParam(":nama", $nama);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $password); // Pastikan password di-hash saat disimpan di database

                $stmt->execute();

                // Redirect ke halaman Log In setelah berhasil mendaftar
                header("Location: LogIn.php");
                exit();
            }
        }

        $pdo = null;
        $stmt = null;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
?>