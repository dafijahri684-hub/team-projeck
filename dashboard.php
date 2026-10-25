<?php
require_once __DIR__ . '/config.php';
require_login();
$pdo = db();
$total = (int)$pdo->query('SELECT COUNT(*) FROM records')->fetchColumn();
$categories = (int)$pdo->query('SELECT COUNT(DISTINCT category) FROM records')->fetchColumn();
$recent = $pdo->query('SELECT r.*, u.name AS creator FROM records r JOIN users u ON u.id = r.created_by ORDER BY r.created_at DESC LIMIT 6')->fetchAll();
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Dashboard | DataDesk</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="app-layout">
<aside class="sidebar">
  <a class="brand" href="dashboard.php"><span class="brand-mark">D</span><span>DataDesk</span></a>
  <div class="nav-label">MENU UTAMA</div>
  <a class="nav-link active" href="dashboard.php"><span>▦</span> Dashboard</a>
  <a class="nav-link" href="form.php"><span>＋</span> Form Input</a>
  <div class="sidebar-bottom"><div class="user-mini"><div class="avatar"><?= e(strtoupper(substr($_SESSION['user_name'], 0, 1))) ?></div><div><strong><?= e($_SESSION['user_name']) ?></strong><small>Administrator</small></div></div><a class="nav-link logout-link" href="logout.php">↪ Keluar</a></div>
</aside>
<main class="main-content">
  <header class="topbar"><div><p class="eyebrow">OVERVIEW</p><h1>Dashboard</h1></div><div class="topbar-user"><span class="status-dot"></span> Sistem aktif</div></header>
  <?php if ($flash): ?><div class="alert success"><?= e($flash) ?></div><?php endif; ?>
  <section class="welcome-card"><div><p class="eyebrow">RINGKASAN APLIKASI</p><h2>Halo, <?= e($_SESSION['user_name']) ?>! 👋</h2><p>Berikut ringkasan data yang tersimpan di aplikasi kamu.</p><a class="btn btn-white" href="form.php">＋ Tambah data</a></div><div class="welcome-art" aria-hidden="true"><span>▦</span><i></i><b></b></div></section>
  <section class="stats-grid">
    <article class="stat-card"><div class="stat-icon purple">▤</div><p>Total data</p><strong><?= $total ?></strong><small>Semua data tersimpan</small></article>
    <article class="stat-card"><div class="stat-icon green">◉</div><p>Kategori</p><strong><?= $categories ?></strong><small>Kategori berbeda</small></article>
    <article class="stat-card"><div class="stat-icon orange">＋</div><p>Form input</p><strong>Aktif</strong><small>Siap menerima data baru</small></article>
  </section>
  <section class="panel">
    <div class="panel-heading"><div><h2>Data terbaru</h2><p>Enam data terakhir yang ditambahkan.</p></div><a class="text-link" href="form.php">Tambah data →</a></div>
    <?php if (!$recent): ?>
      <div class="empty-state"><div class="empty-icon">▤</div><h3>Belum ada data</h3><p>Tambahkan data pertama melalui form input.</p><a class="btn btn-primary" href="form.php">Buat data pertama</a></div>
    <?php else: ?>
      <div class="table-wrap"><table><thead><tr><th>Nama data</th><th>Kategori</th><th>Jumlah</th><th>Dibuat oleh</th><th>Tanggal</th></tr></thead><tbody>
      <?php foreach ($recent as $row): ?><tr><td><strong><?= e($row['title']) ?></strong><?php if ($row['notes']): ?><small class="cell-sub"><?= e($row['notes']) ?></small><?php endif; ?></td><td><span class="tag"><?= e($row['category']) ?></span></td><td><?= (int)$row['quantity'] ?></td><td><?= e($row['creator']) ?></td><td><?= e(date('d M Y', strtotime($row['created_at']))) ?></td></tr><?php endforeach; ?>
      </tbody></table></div>
    <?php endif; ?>
  </section>
  <footer class="footer">© <?= date('Y') ?> DataDesk · Contoh tugas frontend dan backend PHP</footer>
</main></div>
</body></html>
