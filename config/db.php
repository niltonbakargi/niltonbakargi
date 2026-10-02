<?php
// ============================================================
//  Preencha com as credenciais do MySQL (cPanel)
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'b62a4147_niltonbakargi');
define('DB_USER', 'b62a4147_site_niltonbakargi');   // ex: b62a4147_usuario
define('DB_PASS', ';1$21H,9y)ZYu3D&');

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
