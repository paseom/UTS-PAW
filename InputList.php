<?php
$error_message = 'Resep sudah ada, gunakan nama makanan lain!';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_makanan = $_POST['nama_makanan'];
    $asal_negara = $_POST['asal_negara'];
    $bahan_utama = $_POST['bahan_utama'];
    $link_tutorial = $_POST['link_tutorial'];
    $gambar_makanan = $_FILES['gambar']['name'];

    $upload_dir = 'gambar/';
    $upload_file = $upload_dir . basename($gambar_makanan);

    try {
        require_once "dbh.php";

        // Cek apakah email sudah terdaftar
        $query_check_makanan = "SELECT * FROM resep WHERE nama_makanan = :nama_makanan";
        $stmt_check_makanan = $pdo->prepare($query_check_makanan);
        $stmt_check_makanan->bindParam(":nama_makanan", $nama_makanan);
        $stmt_check_makanan->execute();

        if ($stmt_check_makanan->rowCount() > 0) {
            $user_resep = $stmt_check_makanan->fetch(PDO::FETCH_ASSOC);
            if ($user_resep['NAMA_MAKANAN'] == $nama_makanan) {
                echo "<script>alert('$error_message');</script>";
            }
        } else {
            // Insert data ke tabel
            $query_resep = "INSERT INTO RESEP (NAMA_MAKANAN, ASAL_NEGARA, BAHAN_UTAMA, LINK_TUTORIAL, GAMBAR_MAKANAN) 
                            VALUES (:nama_makanan, :asal_negara, :bahan_utama, :link_tutorial, :gambar_makanan)";
            $stmt = $pdo->prepare($query_resep);

            move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_file);

            $stmt->bindParam(":nama_makanan", $nama_makanan);
            $stmt->bindParam(":asal_negara", $asal_negara);
            $stmt->bindParam(":bahan_utama", $bahan_utama);
            $stmt->bindParam(":link_tutorial", $link_tutorial);
            $stmt->bindParam(":gambar_makanan", $gambar_makanan);
            $stmt->execute();
            
            // Redirect setelah berhasil
            header("Location: List.php");
            exit();
        }

        $pdo = null;
        $stmt = null;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="InputList.css">
    <title>Input List</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <label for="nama_makanan">Nama Makanan:</label> 
        <input type="text" id="nama_makanan" name="nama_makanan" required>

        <label for="asal_negara">Asal Negara:</label>
        <input type="text" id="asal_negara" name="asal_negara" required>

        <label for="bahan_utama">Bahan Utama:</label>
        <input type="text" id="bahan_utama" name="bahan_utama" required>

        <label for="link_tutorial">Link Tutorial:</label>
        <input type="link" id="link_tutorial" name="link_tutorial" required>

        <label for="gambar">Gambar:</label>
        <input type="file" id="gambar" name="gambar" required><br>

        <button type="submit">Submit</button>
    </form>
    <form action="List.php">
        <button type="submit">Kembali ke List</button>
    </form>
</body>
</html>
