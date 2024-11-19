<?php
include '../../config/config.php';

// Variabel untuk menampung pesan error
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $itemId = $_POST['itemId']; // ID dari item yang akan diupdate
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemCategory = $_POST['itemCategory'];

    // Variabel untuk nama foto yang akan disimpan di database
    $newPhotoName = null;

    // Menangani upload gambar jika ada
    if (isset($_FILES['itemPhoto']) && $_FILES['itemPhoto']['error'] === 0) {
        $itemPhoto = $_FILES['itemPhoto'];
        $photoName = $itemPhoto['name'];
        $photoTmpName = $itemPhoto['tmp_name'];
        $photoSize = $itemPhoto['size'];
        $photoError = $itemPhoto['error'];
        $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));

        // Memeriksa ekstensi gambar
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($photoExt, $allowedExt)) {
            $errorMessage = "Ekstensi file gambar tidak valid.";
        }

        // Memeriksa apakah ada error saat upload
        if ($photoError !== 0) {
            $errorMessage = "Terjadi kesalahan saat meng-upload gambar.";
        }

        // Menyimpan gambar ke folder uploads
        if (!$errorMessage) {
            $newPhotoName = uniqid('', true) . '.' . $photoExt;
            $uploadDir = '../uploads/';
            $uploadPath = $uploadDir . $newPhotoName;

            if (!move_uploaded_file($photoTmpName, $uploadPath)) {
                $errorMessage = "Gagal meng-upload gambar.";
            }
        }
    }

    // Jika tidak ada error, perbarui data menu
    if (!$errorMessage) {
        try {
            // Query update dengan atau tanpa gambar baru
            if ($newPhotoName) {
                $sql = 'UPDATE menu SET kategori_id = :kategori_id, nama = :nama, harga = :harga, url_foto = :url_foto WHERE id = :id';
            } else {
                $sql = 'UPDATE menu SET kategori_id = :kategori_id, nama = :nama, harga = :harga WHERE id = :id';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':kategori_id', $itemCategory);
            $stmt->bindParam(':nama', $itemName);
            $stmt->bindParam(':harga', $itemPrice);
            $stmt->bindParam(':id', $itemId);

            if ($newPhotoName) {
                $stmt->bindParam(':url_foto', $newPhotoName);
            }

            $stmt->execute();

            header('Location: ../index.php'); // Redirect jika sukses
            exit;
        } catch (PDOException $e) {
            $errorMessage = "Gagal memperbarui menu: " . $e->getMessage();
        }
    }
}
?>

<!-- Menampilkan error jika ada -->
<?php if ($errorMessage): ?>
    <div class="alert alert-danger">
        <?php echo $errorMessage; ?>
    </div>
<?php endif; ?>
