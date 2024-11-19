<?php
include '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Periksa apakah `itemId` ada di POST
    if (isset($_POST['itemId'])) {
        $itemId = $_POST['itemId'];

        try {
            // Persiapkan query SQL untuk menghapus item
            $sql = 'DELETE FROM menu WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

            // Eksekusi query
            if ($stmt->execute()) {
                echo "Item berhasil dihapus!";
                header('Location: ../index.php');  // Redirect kembali ke halaman utama setelah penghapusan
                exit;
            } else {
                echo "Gagal menghapus item.";
            }
        } catch (PDOException $e) {
            echo "Kesalahan: " . $e->getMessage();
        }
    } else {
        echo "ID item tidak ditemukan.";
    }
} else {
    echo "Metode permintaan tidak valid.";
}
