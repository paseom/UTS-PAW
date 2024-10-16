<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_nama'])) {
    header("Location: LogIn.php");
    exit();
}

// Koneksi ke database
require 'dbh.php'; // Pastikan ini sudah terhubung ke dbconnect.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah NAMA_MAKANAN ada di POST request
    if (isset($_POST['nama_makanan'])) {
        $nama_makanan = $_POST['nama_makanan'];

        try {
            // Query untuk menghapus resep berdasarkan NAMA_MAKANAN
            $sql = "DELETE FROM RESEP WHERE NAMA_MAKANAN = :nama_makanan";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nama_makanan', $nama_makanan, PDO::PARAM_STR);

            // Jalankan query
            if ($stmt->execute()) {
                // Berhasil menghapus, redirect kembali ke ListResep.php
                header("Location: List.php");
                exit();
            } else {
                echo "Gagal menghapus data.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Nama makanan tidak ditemukan.";
    }
} else {
    header("Location: List.php"); // Jika bukan POST, redirect ke List.php
    exit();
}

$pdo = null; // Tutup koneksi database
?>