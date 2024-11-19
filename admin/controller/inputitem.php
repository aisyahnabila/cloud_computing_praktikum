<?php
include '../../config/config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemCategory = $_POST['itemCategory'];

    // Menangani upload gambar
    $itemPhoto = $_FILES['itemPhoto'];
    $photoName = $itemPhoto['name'];
    $photoTmpName = $itemPhoto['tmp_name'];
    $photoSize = $itemPhoto['size'];
    $photoError = $itemPhoto['error'];
    $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));

    // Memeriksa ekstensi gambar
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($photoExt, $allowedExt)) {
        echo "Ekstensi file gambar tidak valid.";
        exit;
    }

    // Memeriksa apakah ada error saat upload
    if ($photoError !== 0) {
        echo "Terjadi kesalahan saat meng-upload gambar.";
        exit;
    }

    // Menyimpan gambar ke folder uploads
    $newPhotoName = uniqid('', true) . '.' . $photoExt;
    $uploadDir = '../uploads/';
    $uploadPath = $uploadDir . $newPhotoName;

    if (!move_uploaded_file($photoTmpName, $uploadPath)) {
        echo "Gagal meng-upload gambar.";
        exit;
    }

    // Menyimpan data menu ke dalam database
    try {
        $createdAt = date('Y-m-d H:i:s');  // Waktu saat data disimpan
        $sql = 'INSERT INTO menu (kategori_id, nama, harga, url_foto, created_at) 
                VALUES (:kategori_id, :nama, :harga, :url_foto, :created_at)';

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':kategori_id', $itemCategory);
        $stmt->bindParam(':nama', $itemName);
        $stmt->bindParam(':harga', $itemPrice);
        $stmt->bindParam(':url_foto', $newPhotoName);
        $stmt->bindParam(':created_at', $createdAt);

        $stmt->execute();

        header('Location: ../index.php');
    } catch (PDOException $e) {
        echo "Gagal menambahkan menu: " . $e->getMessage();
    }
}
