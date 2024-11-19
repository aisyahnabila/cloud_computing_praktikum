<!DOCTYPE html>
<html lang="en">
<?php
include 'controller/viewitems.php'
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mieque</title>
    <link rel="stylesheet" href="assets/style/user.css">
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.css">

    <link rel="stylesheet" href="assets/bootstrap/custom-bootstrap.css">
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert alert-success">Transaksi berhasil diproses!</div>
    <?php endif; ?>

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Kategori -->
            <aside class="col-md-2 sidebar" style="position: fixed; top: 0; left: 0; height: 100%; z-index: 1000;">
                <!-- Logo dan Nama -->
                <div class="text-center mb-5">
                    <img src="assets/img/MIEQUE.png" alt="Logo" class="img-fluid rounded-circle mb-2">
                    <h4 class="mb-0">MIQUE</h4>
                </div>
                <hr>
                <!-- Kategori -->
                <div>
                    <h6 class="mb-4">Categories</h6>
                    <a href="#" class="category-btn active-category fw-bold">All Menu</a>
                    <?php
                    foreach ($category as $row) {
                    ?>
                        <a href="#" class="category-btn"><?php echo $row['nama'] ?></a>
                    <?php
                    }
                    ?>
                </div>
                <!-- Tambahkan kategori lain jika diperlukan -->
            </aside>


            <!-- Bagian Utama: Daftar Menu -->
            <main class="main-content col-md-7 p-4">
                <div></div>
                <h5>Pilih menu</h5>
                <div class="row">
                    <!-- Contoh Menu Card -->
                    <?php
                    foreach ($data as $row) {
                        $modalId = "editModal" . $row['id'];
                        $formId = "editItemForm" . $row['id'];
                    ?>
                        <div class="col-md-6 mb-3 menu-item" data-id="<?php echo $row['id']; ?>" data-kategori="<?php echo $row['kategori_nama']; ?>">
                            <div class="card border-0">
                                <div class="card-body">
                                    <img src="admin/uploads/<?php echo $row['url_foto']; ?>"
                                        class="img-fluid"
                                        alt="Menu Image"
                                        style="width: 100%; height: 200px; object-fit: cover;">
                                    <h6 class="mt-2"><?php echo $row['nama']; ?></h6>
                                    <p>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                    <button class="btn btn-red button-add mt-4 w-100">Add To Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>


                    <!-- Tambahkan lebih banyak menu sesuai kebutuhan -->
                </div>
            </main>

            <!-- Sidebar Rincian Pesanan -->
            <aside class="col-md-3 p-4 bg-white right-sidebar">
                <!-- Judul untuk List Menu -->
                <h5>Order List</h5>

                <!-- Daftar Menu yang Dipesan -->
                <ul class="list-group mb-4 order-list border-0"></ul>
                <hr>
                <!-- Total Pembayaran -->
                <div class="d-flex justify-content-between">
                    <strong class="text-muted">Total</strong>
                    <strong class="total-price">Rp 0</strong>
                </div>

                <!-- Opsi Pembayaran -->
                <div class="mt-4">
                    <div class="d-grid gap-2">
                        <button class="btn btn-blue rounded-5" data-bs-toggle="modal" data-bs-target="#exampleModal">Bayar Di Kasir</button>
                    </div>
                </div>
            </aside>


            <!-- MODAL -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Transaksi</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Form untuk Input Transaksi -->
                        <form action="controller/proses_transaksi.php" method="POST">
                            <div class="modal-body px-5">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Total Bayar</strong>
                                    <strong class="total-price">Rp 0</strong>
                                </div>
                                <!-- Input hidden untuk data pesanan dan total harga -->
                                <input type="hidden" name="order_items" id="order_items">
                                <input type="hidden" name="total_harga" id="total_harga">
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-red" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-blue">Proses Transaksi</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="assets/bootstrap/script.js"></script>
    <script>
        // Event Listener untuk tombol kategori
        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                // Dapatkan kategori yang diklik
                const selectedCategory = button.textContent.trim();

                // Toggle active category styling
                document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active-category'));
                button.classList.add('active-category');

                // Tampilkan/Filter item berdasarkan kategori
                document.querySelectorAll('.menu-item').forEach(item => {
                    const itemCategory = item.getAttribute('data-kategori');

                    // Jika kategori "All Menu" atau kategori sesuai dengan kategori item, tampilkan itemnya
                    if (selectedCategory === 'All Menu' || itemCategory === selectedCategory) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
    <script>
        // Daftar untuk menyimpan item yang dipesan
        const orderList = [];
        console.log(orderList);

        // Fungsi untuk memperbarui data pesanan dan total harga
        function updateTransactionData() {
            // Mengonversi orderList menjadi format JSON
            const orderItems = JSON.stringify(orderList.map(item => ({
                id: item.id,
                name: item.name,
                price: item.price,
                quantity: item.quantity
            })));

            // Update input hidden dengan data order_items
            document.getElementById('order_items').value = orderItems;

            // Log orderItems ke console
            console.log("Order Items:", orderItems);

            // Menghitung total harga
            const totalHarga = orderList.reduce((sum, item) => sum + item.price * item.quantity, 0);
            document.getElementById('total_harga').value = totalHarga;

            // Log total harga ke console
            console.log("Total Harga:", totalHarga);
        }


        // Fungsi untuk menambah item ke daftar pesanan
        function addToCart(id, name, price) {
            console.log("Adding to cart:", id); // Debugging
            const existingItem = orderList.find(item => item.id === id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                orderList.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1
                });
            }

            renderOrderList();
            calculateTotal(); // Panggil calculateTotal setelah perubahan
            updateTransactionData(); // Update hidden input dan console log
        }

        // Fungsi untuk mengurangi jumlah item atau menghapus jika jumlah = 1
        function removeFromCart(id) {
            console.log("Removing from cart:", id); // Debugging
            const itemIndex = orderList.findIndex(item => item.id === id);

            if (itemIndex !== -1) {
                if (orderList[itemIndex].quantity > 1) {
                    orderList[itemIndex].quantity -= 1;
                } else {
                    orderList.splice(itemIndex, 1);
                }
            }

            renderOrderList();
            calculateTotal(); // Panggil calculateTotal setelah perubahan
            updateTransactionData(); // Update hidden input dan console log
        }

        function renderOrderList() {
            const orderListElement = document.querySelector('.order-list');
            orderListElement.innerHTML = '';

            orderList.forEach(item => {
                const listItem = document.createElement('li');
                listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'border-0', 'align-items-center');

                const maxLength = 14; // Batasi panjang nama maksimal
                let itemName = item.name;
                if (itemName.length > maxLength) {
                    itemName = itemName.substring(0, maxLength) + '...';
                }

                listItem.innerHTML = `
                <div>
                    <span>${itemName}</span>
                    <span class="badge bg-secondary ms-2">${item.quantity}x</span>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-danger me-2" onclick="removeFromCart(${item.id})">-</button>
                    <span style="font-size:12px">Rp ${item.price * item.quantity}</span>
                </div>
            `;

                orderListElement.appendChild(listItem);
            });
        }

        // Fungsi untuk menghitung total harga
        function calculateTotal() {
            const total = orderList.reduce((sum, item) => sum + item.price * item.quantity, 0);

            // Formatkan total harga sebagai Rupiah dengan dua angka desimal
            const formattedTotal = total.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });

            // Update total di luar modal
            document.querySelector('.total-price').textContent = formattedTotal;

            // Update total di dalam modal
            const modalTotalPrice = document.querySelector('#exampleModal .total-price');
            if (modalTotalPrice) {
                modalTotalPrice.textContent = formattedTotal;
            }
        }


        // Event listener untuk tombol "Add To Cart"
        document.querySelectorAll('.button-add').forEach((button) => {
            button.addEventListener('click', () => {
                const menuItem = button.closest('.menu-item');
                const itemId = parseInt(menuItem.getAttribute('data-id')); // Pastikan itemId adalah angka
                const itemName = menuItem.querySelector('h6').textContent;

                // Mengambil harga dan menghapus format 'Rp' dan koma (untuk parsing)
                const itemPriceText = menuItem.querySelector('p').textContent;
                const itemPrice = parseInt(itemPriceText.replace('Rp ', '').replace('.', '').replace(',', ''));

                addToCart(itemId, itemName, itemPrice);
            });
        });
    </script>




</body>

</html>