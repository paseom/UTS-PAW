<?php
session_start(); // Memulai sesi

// Periksa apakah user sudah login
if (isset($_SESSION['user_nama'])) {
    // Jika sudah login, arahkan ke halaman Home
    header("Location: Home.php");
    exit;
} else {
    // Jika belum login, arahkan ke halaman Login
    header("Location: LogIn.php");
    exit;
}
?>
