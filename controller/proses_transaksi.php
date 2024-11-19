<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderItems = json_decode($_POST['order_items'], true);

    if ($orderItems) {
        try {
            $pdo->beginTransaction();

            $totalHarga = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $orderItems));

            $stmt = $pdo->prepare("SELECT MAX(id) AS max_id FROM transaksi");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $lastId = $result['max_id'] ?? 0;
            $kodeTransaksi = 'PYM' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            $stmt = $pdo->prepare("INSERT INTO transaksi (kode_transaksi, total_amount, created_at) VALUES (:kode_transaksi, :total_amount, NOW())");
            $stmt->bindParam(':kode_transaksi', $kodeTransaksi);
            $stmt->bindParam(':total_amount', $totalHarga);
            $stmt->execute();
            //   Memasukan ke Tabel detail_transaksi
            $transaksiId = $pdo->lastInsertId();

            foreach ($orderItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO detail_transaksi (transaksi_id, menu_id, jumlah, harga, created_at) VALUES (:transaksi_id, :menu_id, :jumlah, :harga, NOW())");
                $stmt->bindParam(':transaksi_id', $transaksiId);
                $stmt->bindParam(':menu_id', $item['id']);
                $stmt->bindParam(':jumlah', $item['quantity']);
                $stmt->bindParam(':harga', $item['price']);
                $stmt->execute();
            }

            $pdo->commit();
            echo "<script>
            alert('Pembayaran telah berhasil');
            window.location.href = '../index.php';
          </script>";
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>
            alert('Gagal memproses transaksi: " . addslashes($e->getMessage()) . "');
            window.location.href = '../index.php';
          </script>";
        }
    } else {
        echo "Data transaksi tidak lengkap.";
    }
}
