<?php

// AMBIL MENU
$sqlitems = 'SELECT menu.*, kategori.nama as kategori_nama 
   FROM menu 
   LEFT JOIN kategori ON menu.kategori_id = kategori.id';



$items = $pdo->prepare($sqlitems);
$items->execute();

$data = $items->fetchAll(PDO::FETCH_ASSOC);

// AMBIL KATEGORI
$sqlkategori = 'SELECT * FROM kategori';

$categories = $pdo->prepare($sqlkategori);
$categories->execute();

$category = $categories->fetchAll(PDO::FETCH_ASSOC);


// AMBIL TRANSAKSI

$sqltransaksi = '
    SELECT 
        transaksi.id AS transaksi_id,
        transaksi.kode_transaksi,
        transaksi.total_amount,
        transaksi.created_at AS transaksi_date,
        GROUP_CONCAT(detail_transaksi.jumlah ORDER BY detail_transaksi.menu_id) AS jumlah_items,
        GROUP_CONCAT(detail_transaksi.harga ORDER BY detail_transaksi.menu_id) AS harga_items,
        GROUP_CONCAT(menu.nama ORDER BY detail_transaksi.menu_id) AS nama_items
    FROM transaksi
    LEFT JOIN detail_transaksi 
        ON transaksi.id = detail_transaksi.transaksi_id
    LEFT JOIN menu 
        ON detail_transaksi.menu_id = menu.id
    GROUP BY transaksi.id
';

$transactions = $pdo->prepare($sqltransaksi);
$transactions->execute();
$transaksi = $transactions->fetchAll(PDO::FETCH_ASSOC);
