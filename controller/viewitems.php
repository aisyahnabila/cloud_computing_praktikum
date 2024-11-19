<?php
include 'config/config.php';

$sqlitems = 'SELECT menu.*, kategori.nama as kategori_nama 
   FROM menu 
   LEFT JOIN kategori ON menu.kategori_id = kategori.id';

$sqlkategori = 'SELECT * FROM kategori';

$items = $pdo->prepare($sqlitems);
$items->execute();
$data = $items->fetchAll(PDO::FETCH_ASSOC);

$categories = $pdo->prepare($sqlkategori);
$categories->execute();
$category = $categories->fetchAll(PDO::FETCH_ASSOC);
