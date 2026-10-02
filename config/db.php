<?php
// ============================================================
//  Preencha com as credenciais do MySQL (cPanel)
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', '');   // ex: seulogin_niltonbakargi
define('DB_USER', '');   // ex: seulogin_usuario
define('DB_PASS', '');

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
