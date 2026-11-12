<?php
/**
 * Checker - Cek apakah environment siap untuk Laravel 12
 * Akses via: http://localhost:8000/check.php
 * Hapus file ini setelah setup selesai!
 */

$checks = [];
$pass = 0;
$total = 0;

// PHP Version
$total++;
if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
    $checks[] = ['ok' => true, 'msg' => 'PHP version: ' . PHP_VERSION . ' (>= 8.2 ✓)'];
    $pass++;
} else {
    $checks[] = ['ok' => false, 'msg' => 'PHP version: ' . PHP_VERSION . ' (butuh >= 8.2)'];
}

// Required extensions
$required = ['pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'curl', 'gd'];
foreach ($required as $ext) {
    $total++;
    if (extension_loaded($ext)) {
        $checks[] = ['ok' => true, 'msg' => "Extension $ext: loaded ✓"];
        $pass++;
    } else {
        $checks[] = ['ok' => false, 'msg' => "Extension $ext: NOT loaded ✗ (aktifkan di php.ini)"];
    }
}

// Writable folders
$folders = ['storage', 'storage/app', 'storage/framework', 'storage/framework/cache', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache'];
foreach ($folders as $folder) {
    $total++;
    $path = __DIR__ . '/' . $folder;
    if (!file_exists($path)) {
        @mkdir($path, 0775, true);
    }
    if (is_writable($path)) {
        $checks[] = ['ok' => true, 'msg' => "Folder $folder: writable ✓"];
        $pass++;
    } else {
        $checks[] = ['ok' => false, 'msg' => "Folder $folder: NOT writable ✗ (chmod 775)"];
    }
}

// Vendor
$total++;
if (is_dir(__DIR__ . '/vendor')) {
    $checks[] = ['ok' => true, 'msg' => 'Composer vendor/: exists ✓'];
    $pass++;
} else {
    $checks[] = ['ok' => false, 'msg' => 'Composer vendor/: missing ✗ (jalankan: composer install)'];
}

// .env file
$total++;
if (file_exists(__DIR__ . '/.env')) {
    $checks[] = ['ok' => true, 'msg' => '.env file: exists ✓'];
    $pass++;
} else {
    $checks[] = ['ok' => false, 'msg' => '.env file: missing ✗ (jalankan: cp .env.example .env)'];
}

// APP_KEY
$total++;
$envContent = file_exists(__DIR__ . '/.env') ? file_get_contents(__DIR__ . '/.env') : '';
if (preg_match('/APP_KEY=base64:/', $envContent)) {
    $checks[] = ['ok' => true, 'msg' => 'APP_KEY: generated ✓'];
    $pass++;
} else {
    $checks[] = ['ok' => false, 'msg' => 'APP_KEY: missing ✗ (jalankan: php artisan key:generate)'];
}

// Database connection
$total++;
$dbConfigOk = false;
if (preg_match('/DB_HOST=(.+)/', $envContent, $h) &&
    preg_match('/DB_DATABASE=(.+)/', $envContent, $d) &&
    preg_match('/DB_USERNAME=(.+)/', $envContent, $u)) {

    $host = trim($h[1]);
    $db   = trim($d[1]);
    $user = trim($u[1]);
    $pass = preg_match('/DB_PASSWORD=(.*)/', $envContent, $p) ? trim($p[1]) : '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $checks[] = ['ok' => true, 'msg' => "MySQL: connected ✓ (database: $db)"];
        $dbConfigOk = true;
        $pass++;
    } catch (PDOException $e) {
        $checks[] = ['ok' => false, 'msg' => 'MySQL: ' . $e->getMessage()];
    }
} else {
    $checks[] = ['ok' => false, 'msg' => 'DB config: incomplete in .env ✗'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Environment Check - SIP Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fc; padding: 30px; }
        .check-card { max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>
<div class="check-card">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Environment Check - SIP Perpustakaan</h4>
        </div>
        <div class="card-body">
            <div class="alert <?= $pass === $total ? 'alert-success' : 'alert-warning' ?>">
                <strong><?= $pass ?></strong> / <?= $total ?> checks passed.
                <?php if ($pass === $total): ?>
                    ✅ Semua siap! Jalankan <code>php artisan migrate --seed</code> lalu <code>php artisan serve</code>.
                <?php else: ?>
                    ⚠️ Ada masalah. Perbaiki yang ditandai ✗ di bawah.
                <?php endif; ?>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="50">Status</th>
                        <th>Check</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($checks as $c): ?>
                    <tr class="<?= $c['ok'] ? 'table-success' : 'table-danger' ?>">
                        <td class="text-center">
                            <?php if ($c['ok']): ?>✅<?php else: ?>❌<?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($c['msg']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="alert alert-info">
                <strong>Tips:</strong>
                <ul class="mb-0">
                    <li>Untuk aktifkan extension PHP: edit <code>php.ini</code>, hapus <code>;</code> di depan <code>extension=pdo_mysql</code> dll.</li>
                    <li>Untuk permission folder: <code>chmod -R 775 storage bootstrap/cache</code></li>
                    <li>Setelah semua ✅, hapus file <code>check.php</code> ini.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
</html>
