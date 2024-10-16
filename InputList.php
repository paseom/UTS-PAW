<?php
require 'dbh.php'; // Menghubungkan ke database

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
        // Pindahkan file gambar ke direktori yang dituju
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_file);

        // Query untuk memasukkan data ke tabel RESEP
        $query_resep = "INSERT INTO RESEP (NAMA_MAKANAN, ASAL_NEGARA, BAHAN_UTAMA, LINK_TUTORIAL, GAMBAR_MAKANAN) 
                        VALUES (:nama_makanan, :asal_negara, :bahan_utama, :link_tutorial, :gambar_makanan)";
        
        $stmt = $pdo->prepare($query_resep);
        
        // Binding parameters
        $stmt->bindParam(":nama_makanan", $nama_makanan);
        $stmt->bindParam(":asal_negara", $asal_negara);
        $stmt->bindParam(":bahan_utama", $bahan_utama);
        $stmt->bindParam(":link_tutorial", $link_tutorial);
        $stmt->bindParam(":gambar_makanan", $gambar_makanan);
        
        // Execute the query
        $stmt->execute();

        // Redirect after successful insertion
        header("Location: List.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Integrity constraint violation
            // Redirect to the list page if a duplicate entry occurs
            echo"Data sudah ada!";
            header("Location: List.php");
            exit();
        } else {
            die("Query failed: " . $e->getMessage());
        }
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
        <input type="text" id="nama_makanan" name="nama_makanan" required><br>

        <label for="asal_negara">Asal Negara:</label>
        <input type="text" id="asal_negara" name="asal_negara" required><br>

        <label for="bahan_utama">Bahan Utama:</label>
        <input type="text" id="bahan_utama" name="bahan_utama" required><br>

        <label for="link_tutorial">Link Tutorial:</label>
        <input type="link" id="link_tutorial" name="link_tutorial" required><br>

        <label for="gambar">Gambar:</label>
        <input type="file" id="gambar" name="gambar" required><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>


