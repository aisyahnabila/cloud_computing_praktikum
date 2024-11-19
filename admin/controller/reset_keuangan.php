<?php
// Include file koneksi database Anda
require_once '../../config/config.php'; // Gantilah 'koneksi.php' dengan nama file koneksi Anda

// Cek apakah ada parameter 'reset' pada URL
if (isset($_GET['reset'])) {
    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Query untuk menghapus data dari tabel detail_transaksi
        $sql1 = "DELETE FROM detail_transaksi";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute(); // Eksekusi query

        // Query untuk menghapus data dari tabel transaksi
        $sql2 = "DELETE FROM transaksi";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute(); // Eksekusi query

        // Commit transaksi jika kedua query berhasil
        $pdo->commit();

        // Redirect atau tampilkan pesan sukses
        header("Location: ../keuangan.php?status=success");
        exit();

    } catch (PDOException $e) {
        // Rollback jika terjadi kesalahan
        $pdo->rollBack();
        echo "Gagal menghapus data: " . $e->getMessage();
    }
}
?>
