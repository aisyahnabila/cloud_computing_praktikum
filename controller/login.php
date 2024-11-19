<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['usernames'];
    $password = $_POST['password'];

    try {
        // Query untuk mendapatkan data user berdasarkan username
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Ambil data user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if ($user && $user['password'] === $password) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: ../admin/index.php");
            exit;
        } else {
            echo "Username atau password salah.";
        }
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
