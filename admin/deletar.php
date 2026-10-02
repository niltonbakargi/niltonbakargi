<?php
session_start();
require 'config.php';

if (empty($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_POST['id'];
$arquivo_json = __DIR__ . '/../trabalhos.json';
$trabalhos = [];

if (file_exists($arquivo_json)) {
    $trabalhos = json_decode(file_get_contents($arquivo_json), true) ?: [];
}

foreach ($trabalhos as $key => $t) {
    if ($t['id'] === $id) {
        if (!empty($t['imagem'])) {
            $caminho_img = __DIR__ . '/../' . $t['imagem'];
            if (file_exists($caminho_img)) {
                unlink($caminho_img);
            }
        }
        array_splice($trabalhos, $key, 1);
        break;
    }
}

file_put_contents($arquivo_json, json_encode($trabalhos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: index.php?msg=deletado');
exit;
