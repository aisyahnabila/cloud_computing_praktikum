<?php
// Konfigurasi untuk koneksi ke database
$host = 'localhost';        // Nama host atau alamat server database
$dbname = 'restoran';       // Nama database
$username = 'root';         // Username untuk koneksi database
$password = '';             // Password untuk koneksi database (kosongkan jika tidak ada)

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Menyetel mode error PDO ke Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Menyusun pesan sukses

    // echo "Koneksi ke database berhasil!";
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error
    echo "Koneksi gagal: " . $e->getMessage();
}
