<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_nama'])) {
    header("Location: LogIn.php");
    exit();
}

// Koneksi ke database
require 'dbh.php';

// Cek apakah ada parameter nama makanan yang dikirim
if (!isset($_GET['nama_makanan'])) {
    header("Location: List.php");
    exit();
}

$nama_makanan = $_GET['nama_makanan'];

// Proses form ketika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_makanan_baru = $_POST['nama_makanan'];
    $asal_negara = $_POST['asal_negara'];
    $bahan_utama = $_POST['bahan_utama'];
    $link_tutorial = $_POST['link_tutorial'];

    // Ambil gambar lama dari database
    $sql = "SELECT GAMBAR_MAKANAN FROM RESEP WHERE NAMA_MAKANAN = :nama_makanan";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nama_makanan', $nama_makanan);
    $stmt->execute();
    $resep = $stmt->fetch(PDO::FETCH_ASSOC);
    $gambar_lama = $resep['GAMBAR_MAKANAN'];

    // Cek apakah ada file gambar yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar_baru = $_FILES['gambar'];
        $upload_dir = 'gambar/'; // Pastikan folder 'gambar' ada dan dapat diakses
        $upload_file = $upload_dir . basename($gambar_baru['name']);
        
        // Pindahkan file yang diupload ke folder yang diinginkan
        if (move_uploaded_file($gambar_baru['tmp_name'], $upload_file)) {
            // Update database dengan path gambar yang baru
            $sql_update = "UPDATE RESEP SET NAMA_MAKANAN = :nama_makanan_baru, ASAL_NEGARA = :asal_negara, BAHAN_UTAMA = :bahan_utama, LINK_TUTORIAL = :link_tutorial, GAMBAR_MAKANAN = :gambar_makanan WHERE NAMA_MAKANAN = :nama_makanan";
            $stmt = $pdo->prepare($sql_update);
            $stmt->bindParam(':gambar_makanan', basename($gambar_baru['name']));
        } else {
            echo "Gagal mengupload gambar.";
            exit();
        }
    } else {
        // Jika tidak ada gambar yang diupload, gunakan gambar lama
        $sql_update = "UPDATE RESEP SET NAMA_MAKANAN = :nama_makanan_baru, ASAL_NEGARA = :asal_negara, BAHAN_UTAMA = :bahan_utama, LINK_TUTORIAL = :link_tutorial, GAMBAR_MAKANAN = :gambar_makanan WHERE NAMA_MAKANAN = :nama_makanan";
        $stmt = $pdo->prepare($sql_update);
        $stmt->bindParam(':gambar_makanan', $gambar_lama); // Tetap menggunakan gambar lama
    }

    // Bind parameter lain
    $stmt->bindParam(':nama_makanan_baru', $nama_makanan_baru);
    $stmt->bindParam(':asal_negara', $asal_negara);
    $stmt->bindParam(':bahan_utama', $bahan_utama);
    $stmt->bindParam(':link_tutorial', $link_tutorial);
    $stmt->bindParam(':nama_makanan', $nama_makanan);
    
    $stmt->execute();

    header("Location: List.php"); // Redirect ke halaman list resep setelah update
    exit();
}

// Ambil data resep yang akan diedit
$sql = "SELECT * FROM RESEP WHERE NAMA_MAKANAN = :nama_makanan";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nama_makanan', $nama_makanan);
$stmt->execute();
$resep = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek apakah resep ditemukan
if (!$resep) {
    header("Location: List.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resep</title>
    <link rel="stylesheet" href="EditList.css">
</head>
<body>
    <h1>Edit Resep</h1>
    <form action="EditList.php?nama_makanan=<?= htmlspecialchars($nama_makanan) ?>" method="post" enctype="multipart/form-data">
        <label for="nama_makanan">Nama Makanan: </label>
        <input type="text" name="nama_makanan" value="<?= htmlspecialchars($resep['NAMA_MAKANAN']) ?>" required><br>

        <label for="asal_negara">Asal Negara: </label>
        <input type="text" name="asal_negara" value="<?= htmlspecialchars($resep['ASAL_NEGARA']) ?>" required><br>

        <label for="bahan_utama">Bahan Utama: </label>
        <input type="text" name="bahan_utama" value="<?= htmlspecialchars($resep['BAHAN_UTAMA']) ?>" required><br>

        <label for="link_tutorial">Link Tutorial: </label>
        <input type="url" name="link_tutorial" value="<?= htmlspecialchars($resep['LINK_TUTORIAL']) ?>" required><br>

        <label for="gambar">Ubah Gambar: </label>
        <input type="file" name="gambar" accept="image/*"><br>

        <input type="submit" value="Simpan Perubahan">
    </form>
    <form action="List.php">
        <input type="submit" value="Kembali ke List Resep">
    </form>
</body>
</html>
