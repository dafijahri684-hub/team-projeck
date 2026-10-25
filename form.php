<?php
require_once __DIR__ . '/config.php';
require_login();
$errors = [];
$title = $category = $notes = '';
$quantity = '1';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim((string)($_POST['title'] ?? ''));
    $category = trim((string)($_POST['category'] ?? ''));
    $quantity = trim((string)($_POST['quantity'] ?? '1'));
    $notes = trim((string)($_POST['notes'] ?? ''));
    if ($title === '' || mb_strlen($title) > 150) $errors[] = 'Nama data wajib diisi (maksimal 150 karakter).';
    if ($category === '' || mb_strlen($category) > 100) $errors[] = 'Kategori wajib diisi (maksimal 100 karakter).';
    if (!ctype_digit($quantity) || (int)$quantity < 1 || (int)$quantity > 1000000) $errors[] = 'Jumlah harus berupa angka dari 1 sampai 1.000.000.';
    if (mb_strlen($notes) > 2000) $errors[] = 'Keterangan maksimal 2.000 karakter.';
    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO records (title, category, quantity, notes, created_by) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$title, $category, (int)$quantity, $notes !== '' ? $notes : null, (int)$_SESSION['user_id']]);
        $_SESSION['flash'] = 'Data berhasil disimpan.';
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Form Input | DataDesk</title><link rel="stylesheet" href="assets/style.css"></head>
<body><div class="app-layout">
<aside class="sidebar"><a class="brand" href="dashboard.php"><span class="brand-mark">D</span><span>DataDesk</span></a><div class="nav-label">MENU UTAMA</div><a class="nav-link" href="dashboard.php"><span>▦</span> Dashboard</a><a class="nav-link active" href="form.php"><span>＋</span> Form Input</a><div class="sidebar-bottom"><div class="user-mini"><div class="avatar"><?= e(strtoupper(substr($_SESSION['user_name'], 0, 1))) ?></div><div><strong><?= e($_SESSION['user_name']) ?></strong><small>Administrator</small></div></div><a class="nav-link logout-link" href="logout.php">↪ Keluar</a></div></aside>
<main class="main-content">
<header class="topbar"><div><p class="eyebrow">MANAJEMEN DATA</p><h1>Form Input</h1></div><a class="btn btn-outline" href="dashboard.php">← Kembali</a></header>
<section class="panel form-panel"><div class="panel-heading"><div><h2>Tambah data baru</h2><p>Isi informasi di bawah ini, lalu tekan tombol simpan.</p></div><span class="required-note">* Wajib diisi</span></div>
<?php if ($errors): ?><div class="alert error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="data-form" id="data-form">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label for="title">Nama data <span>*</span></label><input id="title" name="title" maxlength="150" value="<?= e($title) ?>" placeholder="Contoh: Laptop Lenovo" required><small>Masukkan nama data yang ingin dicatat.</small></div>
<div class="field"><label for="category">Kategori <span>*</span></label><select id="category" name="category" required><option value="">Pilih kategori</option><?php foreach (['Elektronik','Perlengkapan','Dokumen','Operasional','Lainnya'] as $cat): ?><option value="<?= e($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option><?php endforeach; ?></select></div>
<div class="field"><label for="quantity">Jumlah <span>*</span></label><input id="quantity" type="number" name="quantity" min="1" max="1000000" value="<?= e($quantity) ?>" required><small>Jumlah minimal adalah 1.</small></div>
<div class="field"><label for="notes">Keterangan</label><textarea id="notes" name="notes" rows="4" maxlength="2000" placeholder="Tambahkan keterangan jika diperlukan..."><?= e($notes) ?></textarea><small>Opsional, maksimal 2.000 karakter.</small></div>
<div class="form-actions"><a class="btn btn-outline" href="dashboard.php">Batal</a><button class="btn btn-primary" type="submit">Simpan data <span>→</span></button></div>
</form></section><footer class="footer">Data yang disimpan akan muncul di dashboard.</footer>
</main></div><script src="assets/app.js"></script></body></html>
