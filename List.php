<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_nama'])) {
    header("Location: LogIn.php");
    exit();
}

// Koneksi ke database
require 'dbh.php'; // Pastikan ini sudah terhubung ke dbconnect.php

// Query untuk mengambil data dari RESEP
$sql = "SELECT NAMA_MAKANAN, ASAL_NEGARA, BAHAN_UTAMA, LINK_TUTORIAL, GAMBAR_MAKANAN FROM RESEP";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Resep</title>
    <link rel="stylesheet" href="List.css">
</head>
<body>
    <h1>LIST RESEP</h1>

    <div class="button-container">
        <form action="Home.php">
            <input type="submit" value="Kembali ke Home" class="left-button">
        </form>
        <form action="LogOut.php">
            <input type="submit" value="Log Out" class="right-button">
        </form>
    </div>

    <?php
    // Kode PHP untuk mengambil data dari database
    if ($result && $result->rowCount() > 0) { // Cek jika ada data
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='food-item'>";
            $image_path = 'gambar/' . htmlspecialchars($row["GAMBAR_MAKANAN"]);
            echo "<img src='" . $image_path . "' alt='" . htmlspecialchars($row["NAMA_MAKANAN"]) . "'>";
            echo "<div class='food-info'>";
            echo "<h2>" . htmlspecialchars($row["NAMA_MAKANAN"]) . "</h2>";
            echo "<p>Asal Negara: " . htmlspecialchars($row["ASAL_NEGARA"]) . "</p>";
            echo "<p>Bahan Utama: " . htmlspecialchars($row["BAHAN_UTAMA"]) . "</p>";
            echo "<p>Link Tutorial: <a href='" . htmlspecialchars($row["LINK_TUTORIAL"]) . "'>Video Tutorial</a></p>";
            echo "</div>";
            // Kontainer untuk tombol hapus
            echo "<div class='delete-container'>";
            echo "<form method='POST' action='DeleteList.php' onsubmit='return confirm(\"Apakah anda yakin ingin menghapus resep ini?\");'>";
            echo "<input type='hidden' name='nama_makanan' value='" . htmlspecialchars($row["NAMA_MAKANAN"]) . "'>";
            echo "<input type='submit' value='Hapus' class='delete-button'>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        // Jika tidak ada data
        echo "<p class='no-data'>Belum ada data di database.</p>";
    }

    // Tombol untuk menambah resep
    echo "<div class='add-recipe-container'>";
    echo "<form action='InputList.php'>";
    echo "<input type='submit' value='Tambah Resep' class='add-recipe-button'>";
    echo "</form>";
    echo "</div>";

    $pdo = null; // Tutup koneksi database
    ?>
</body>
</html>
