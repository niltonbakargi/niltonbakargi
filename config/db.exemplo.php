<?php
// Copie este arquivo para db.php e preencha as credenciais
// cp config/db.exemplo.php config/db.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'b62a4147_niltonbakargi');
define('DB_USER', '');  // usuario MySQL do cPanel
define('DB_PASS', '');  // senha MySQL do cPanel

function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
