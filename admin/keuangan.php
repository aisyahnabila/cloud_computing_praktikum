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
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Keuangan</title>
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
                        <a class="nav-link " href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="keuangan.php">Keuangan</a>
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
                <h5 class="mb-0">Keuangan</h5>
            </div>
            <div class="card-body p-4">
                <!-- Menampilkan total pendapatan -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <?php
                        // Menghitung total pendapatan
                        $totalPendapatan = 0;
                        foreach ($transaksi as $row) {
                            $totalPendapatan += $row['total_amount'];
                        }
                        ?>
                        <h6><strong>Total Pendapatan: Rp <?php echo number_format($totalPendapatan, 2); ?></strong></h6>
                    </div>
                    <a class="btn btn-danger" onclick="confirm('Apakah anda ingin menghapus seluruh keuangan?')" href="controller/reset_keuangan.php?reset=true">Reset Keuangan</a>
                </div>


                <div class="table-responsive">
                    <table id="menuTable" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode Transaksi</th>
                                <th scope="col">Total</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($transaksi as $row) {
                                $modalId = "viewForm" . $row['transaksi_id'];  // ID unik untuk modal
                            ?>
                                <tr>
                                    <td scope="row"><?php echo $no++; ?></td>
                                    <td><?php echo $row['kode_transaksi'] ?></td>
                                    <td>Rp <?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#<?php echo $modalId; ?>">
                                            <i class="bi bi-eye"></i> Detail Transaksi
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal untuk melihat detail transaksi (di luar tabel) -->
    <?php
    foreach ($transaksi as $row) {
        $modalId = "viewForm" . $row['transaksi_id'];  // ID unik untuk modal
    ?>
        <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Detail Barang yang Dibeli - Transaksi <?php echo $row['kode_transaksi']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mengambil detail barang dari transaksi_id
                                $sqlDetail = '
                                SELECT 
                                    menu.nama AS nama_item, 
                                    detail_transaksi.jumlah, 
                                    detail_transaksi.harga
                                FROM detail_transaksi
                                LEFT JOIN menu ON detail_transaksi.menu_id = menu.id
                                WHERE detail_transaksi.transaksi_id = :transaksi_id
                            ';
                                $stmt = $pdo->prepare($sqlDetail);
                                $stmt->execute(['transaksi_id' => $row['transaksi_id']]);
                                $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($details as $detail) {
                                ?>
                                    <tr>
                                        <td><?php echo $detail['nama_item']; ?></td>
                                        <td><?php echo $detail['jumlah']; ?></td>
                                        <td>Rp <?php echo number_format($detail['harga'], 2); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

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