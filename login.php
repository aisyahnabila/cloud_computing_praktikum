<!DOCTYPE html>
<html lang="en">
<?php
session_start(); // Memulai session

// Cek apakah session 'user_id' sudah ada
if (isset($_SESSION['user_id'])) {
    // Jika sudah login, langsung redirect ke halaman admin
    header("Location: admin/index.php");
    exit;
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="assets/bootstrap/custom-bootstrap.css">
</head>


<body class="bg-light d-flex justify-content-center align-items-center vh-100 mb-0">

    <div class="card border-0 rounded-5 p-3 shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <h2 class="card-title fw-bold text-center mb-4">Admin Login</h2>
            <form action="controller/login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="usernames" id="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-blue">Login</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (for components) -->
    <script src="assets/bootstrap/script.js"></script>
</body>

</html>