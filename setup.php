<?php
require_once __DIR__ . '/config.php';

try {
    // Buat akun demo jika belum ada. Ganti password setelah berhasil login.
    $email = 'admin@example.com';
    $check = db()->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute([$email]);
    if (!$check->fetch()) {
        $stmt = db()->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute(['Administrator', $email, password_hash('Admin12345!', PASSWORD_DEFAULT)]);
    }
    echo '<h2>Setup selesai!</h2><p>Akun demo: <b>admin@example.com</b></p><p>Password: <b>Admin12345!</b></p><p><a href="login.php">Buka halaman login</a></p><p>Untuk keamanan, hapus file setup.php setelah setup berhasil.</p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Setup gagal. Pastikan database tugas_web sudah dibuat dan database.sql sudah diimpor. Detail: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
