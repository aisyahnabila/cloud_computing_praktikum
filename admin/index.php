<!DOCTYPE html>
<html lang="en">
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: ../login.php");
    exit;
}
include '../config/config.php';
include 'controller/viewitems.php';
echo "assad";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu List with DataTables</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../assets/bootstrap/custom-bootstrap.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keuangan.php">Keuangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="controller/logout.php">Logout</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <!-- Menu List Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Menu</h5>
                <a href="#" class="btn btn-blue btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Add New
                    Menu</a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="menuTable" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Gambar Menu</th>
                                <th scope="col">Nama Menu</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Kategori</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $row) {
                                $modalId = "editModal" . $row['id'];
                                $formId = "editItemForm" . $row['id'];  // Menambahkan ID unik untuk form
                            ?>
                                <tr>
                                    <td scope="row"><?php echo $no++; ?></td>
                                    <td><img src="uploads/<?php echo $row['url_foto'] ?>" alt="" width="150px" height="150px"></td>
                                    <td><?php echo $row['nama'] ?></td>
                                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $row['kategori_nama'] ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#<?php echo $modalId; ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="controller/deleteitem.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="itemId" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit khusus untuk item ini -->
                                <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="editModalLabel">Edit Menu Item</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="<?php echo $formId; ?>" action="controller/edititem.php" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">

                                                    <input type="hidden" name="itemId" value="<?php echo $row['id']; ?>">

                                                    <div class="mb-3">
                                                        <label for="editItemName<?php echo $row['id']; ?>" class="form-label">Nama Menu</label>
                                                        <input type="text" class="form-control" id="editItemName<?php echo $row['id']; ?>" name="itemName" value="<?php echo $row['nama']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="editItemPrice<?php echo $row['id']; ?>" class="form-label">Harga</label>
                                                        <input type="number" class="form-control" id="editItemPrice<?php echo $row['id']; ?>" name="itemPrice" value="<?php echo $row['harga']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="editItemCategory<?php echo $row['id']; ?>" class="form-label">Kategori</label>
                                                        <select class="form-select" id="editItemCategory<?php echo $row['id']; ?>" name="itemCategory" required>
                                                            <option value="" disabled>Pilih Kategori</option>
                                                            <?php
                                                            foreach ($category as $cat) {
                                                            ?>
                                                                <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $row['kategori_id'] ? 'selected' : ''; ?>>
                                                                    <?php echo $cat['nama']; ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="editItemPhoto<?php echo $row['id']; ?>" class="form-label">Upload Gambar</label>
                                                        <input class="form-control" type="file" id="editItemPhoto<?php echo $row['id']; ?>" name="itemPhoto" accept="image/*">
                                                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" form="<?php echo $formId; ?>">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </tbody>


                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Menu Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="controller/inputitem.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Form for Menu Item Input -->

                        <div class="mb-3">
                            <label for="itemName" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" id="itemName" name="itemName" placeholder="Masukkan nama menu" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemPrice" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="itemPrice" name="itemPrice" placeholder="Masukkan harga item" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="itemCategory" name="itemCategory" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php
                                // Mengambil kategori dari database
                                $sqlkategori = 'SELECT * FROM kategori';
                                $categories = $pdo->prepare($sqlkategori);
                                $categories->execute();
                                $category = $categories->fetchAll(PDO::FETCH_ASSOC);

                                // Menampilkan kategori dalam dropdown
                                foreach ($category as $row) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemPhoto" class="form-label">Upload Gambar</label>
                            <input class="form-control" type="file" id="itemPhoto" name="itemPhoto" accept="image/*" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#menuTable').DataTable();
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="../assets/bootstrap/script.js"></script>

</body>

</html>