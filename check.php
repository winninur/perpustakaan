<?php
/**
 * ============================================================================
 * CEK ENVIRONMENT - jalankan dari PowerShell: php check.php
 * ============================================================================
 * Tujuan: cek apakah komputer kamu siap untuk jalanin Laravel 12,
 * SEBELUM composer install. Biar gak mentok di tengah jalan.
 * ============================================================================
 */

$pass = 0;
$total = 0;
$warnings = [];
$errors = [];

function check($label, $ok, $msg, $fix = '') {
    global $pass, $total, $warnings, $errors;
    $total++;
    if ($ok) {
        $pass++;
        echo "  [OK]   $msg\n";
    } else {
        if ($fix) {
            $errors[] = "$msg\n         FIX: $fix";
            echo "  [FAIL] $msg\n         -> $fix\n";
        } else {
            $warnings[] = $msg;
            echo "  [WARN] $msg\n";
        }
    }
}

echo "\n";
echo "============================================================================\n";
echo "  CEK ENVIRONMENT - SIP Perpustakaan (Laravel 12)\n";
echo "  OS: " . php_uname() . "\n";
echo "============================================================================\n\n";

echo "[1] PHP VERSION (butuh >= 8.2):\n";
check('php_version', version_compare(PHP_VERSION, '8.2.0', '>='),
    "PHP " . PHP_VERSION,
    "Download XAMPP terbaru (PHP 8.2+) dari https://www.apachefriends.org/\n         Atau update PHP. Laravel 12 butuh PHP 8.2 minimum."
);
echo "\n";

echo "[2] PHP EXTENSIONS wajib:\n";
$required = [
    'pdo_mysql' => 'Untuk koneksi MySQL',
    'mbstring'   => 'Untuk string multibyte (wajib Laravel)',
    'openssl'    => 'Untuk enkripsi (APP_KEY)',
    'tokenizer'  => 'Untuk Blade template',
    'xml'        => 'Untuk parsing config',
    'ctype'      => 'Untuk validasi',
    'json'       => 'Untuk JSON handling',
    'bcmath'     => 'Untuk kalkulasi angka',
    'fileinfo'   => 'Untuk upload file',
    'curl'       => 'Untuk HTTP request',
    'gd'         => 'Untuk manipulasi gambar',
];
foreach ($required as $ext => $desc) {
    check('ext_' . $ext, extension_loaded($ext),
        "Extension $ext " . (extension_loaded($ext) ? "(loaded)" : "(NOT loaded)") . " - $desc",
        "Edit php.ini (biasanya di D:\\xampp\\php\\php.ini),\n         hapus ';' di depan 'extension=$ext', restart Apache di XAMPP."
    );
}
echo "\n";

echo "[3] COMPOSER (wajib buat install dependency):\n";
$composerOut = [];
exec('composer --version 2>&1', $composerOut, $exitCode);
check('composer', $exitCode === 0,
    "Composer " . ($exitCode === 0 ? "terinstall: " . trim($composerOut[0] ?? '') : "BELUM terinstall"),
    "Download dari https://getcomposer.org/download/\n         Jalankan Composer-Setup.exe, saat ditanya PHP path pilih D:\\xampp\\php\\php.exe"
);
echo "\n";

echo "[4] FOLDER PERMISSION:\n";
$folders = ['storage', 'storage/app', 'storage/framework', 'storage/framework/cache',
            'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views',
            'storage/logs', 'bootstrap/cache'];
foreach ($folders as $f) {
    if (!file_exists($f)) {
        @mkdir($f, 0775, true);
    }
    check('folder_' . str_replace('/', '_', $f), is_writable($f),
        "Folder $f " . (is_writable($f) ? "writable" : "NOT writable"),
        "Klik kanan folder -> Properties -> Security -> Edit -> Users -> Full Control"
    );
}
echo "\n";

echo "[5] FILE .env:\n";
check('env_exists', file_exists('.env'),
    "File .env " . (file_exists('.env') ? "ada" : "BELUM ada (normal sebelum setup)"),
    file_exists('.env') ? '' : "Akan dibuat otomatis oleh setup.bat"
);
echo "\n";

echo "[6] VENDOR FOLDER:\n";
check('vendor_exists', is_dir('vendor'),
    "Folder vendor/ " . (is_dir('vendor') ? "ada (composer install sudah dijalankan)" : "BELUM ada (composer install belum dijalankan)"),
    is_dir('vendor') ? '' : "Jalankan: composer install"
);
echo "\n";

echo "[7] DATABASE (opsional, untuk cek koneksi MySQL):\n";
$envContent = file_exists('.env') ? file_get_contents('.env') : file_get_contents('.env.example');
$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'db_perpustakaan';
$dbUser = 'root';
$dbPass = '';
if (preg_match('/DB_HOST=(.+)/', $envContent, $m)) $dbHost = trim($m[1]);
if (preg_match('/DB_PORT=(.+)/', $envContent, $m)) $dbPort = trim($m[1]);
if (preg_match('/DB_DATABASE=(.+)/', $envContent, $m)) $dbName = trim($m[1]);
if (preg_match('/DB_USERNAME=(.+)/', $envContent, $m)) $dbUser = trim($m[1]);
if (preg_match('/DB_PASSWORD=(.*)/', $envContent, $m)) $dbPass = trim($m[1]);

if (extension_loaded('pdo_mysql')) {
    try {
        $pdo = new PDO("mysql:host=$dbHost;port=$dbPort", $dbUser, $dbPass, [PDO::ATTR_TIMEOUT => 3]);
        check('mysql_running', true, "MySQL jalan di $dbHost:$dbPort");

        // Cek database exists
        try {
            $pdo2 = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPass, [PDO::ATTR_TIMEOUT => 3]);
            check('db_exists', true, "Database '$dbName' ada");
        } catch (PDOException $e) {
            check('db_exists', false,
                "Database '$dbName' BELUM ada",
                "Buka http://localhost/phpmyadmin, buat database '$dbName',\n         collation utf8mb4_unicode_ci, klik Create"
            );
        }
    } catch (PDOException $e) {
        check('mysql_running', false,
            "MySQL TIDAK jalan / tidak bisa connect: " . $e->getMessage(),
            "Buka XAMPP Control Panel -> klik Start di baris MySQL"
        );
    }
} else {
    echo "  (skip - extension pdo_mysql tidak loaded)\n";
}
echo "\n";

echo "============================================================================\n";
echo "  HASIL: $pass / $total check PASS\n";
echo "============================================================================\n\n";

if ($pass === $total) {
    echo "  ✅ SEMUA SIAP! Lanjut jalankan:\n";
    echo "     composer install\n";
    echo "     copy .env.example .env\n";
    echo "     php artisan key:generate\n";
    echo "     php artisan migrate:fresh --seed\n";
    echo "     php artisan serve\n\n";
} else {
    echo "  ❌ Ada " . count($errors) . " masalah yang HARUS diperbaiki dulu!\n\n";
    if ($errors) {
        echo "  Daftar masalah:\n";
        foreach ($errors as $i => $e) {
            echo "  " . ($i+1) . ". $e\n";
        }
        echo "\n";
    }
    echo "  Perbaiki dulu semua yang [FAIL], lalu jalankan 'php check.php' lagi.\n\n";
}
