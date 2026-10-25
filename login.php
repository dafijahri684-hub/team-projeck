<?php
require_once __DIR__ . '/config.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Masukkan email dan password yang valid.';
    } else {
        try {
            $stmt = db()->prepare('SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: dashboard.php');
                exit;
            }
            $error = 'Email atau password salah.';
        } catch (Throwable $e) {
            $error = 'Database belum siap. Jalankan database.sql lalu buka setup.php.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | DataDesk</title><link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
<main class="login-shell">
  <section class="login-intro">
    <div class="brand brand-light"><span class="brand-mark">D</span><span>DataDesk</span></div>
    <p class="eyebrow">SIMPLE ADMIN PANEL</p>
    <h1>Kelola data dengan lebih mudah.</h1>
    <p class="intro-copy">Contoh aplikasi frontend dan backend PHP dengan login, dashboard, dan form input data.</p>
    <div class="intro-foot">Tugas Pemrograman Web · PHP & MySQL</div>
  </section>
  <section class="login-form-panel">
    <div class="form-heading"><p class="eyebrow">SELAMAT DATANG</p><h2>Masuk ke akun</h2><p>Masukkan akun untuk melanjutkan.</p></div>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="stack-form">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" placeholder="admin@example.com" autocomplete="username" required>
      <label for="password">Password</label>
      <div class="password-wrap"><input id="password" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required><button class="show-password" type="button" data-toggle-password="password">Lihat</button></div>
      <button class="btn btn-primary btn-full" type="submit">Login <span aria-hidden="true">→</span></button>
    </form>
    <p class="login-note">Demo: admin@example.com · Admin12345!</p>
  </section>
</main>
<script src="assets/app.js"></script>
</body></html>
