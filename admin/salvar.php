<?php
session_start();
require 'config.php';

if (empty($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

$arquivo_json = __DIR__ . '/../trabalhos.json';
$trabalhos = [];
if (file_exists($arquivo_json)) {
    $trabalhos = json_decode(file_get_contents($arquivo_json), true) ?: [];
}

$titulo    = trim($_POST['titulo']    ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$data      = trim($_POST['data']      ?? '');

if (!$titulo || !$descricao || !$data) {
    header('Location: index.php?erro=campos');
    exit;
}

$imagem = '';
if (!empty($_FILES['imagem']['name'])) {
    $ext      = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $permitidos)) {
        header('Location: index.php?erro=formato');
        exit;
    }

    if ($_FILES['imagem']['size'] > 5 * 1024 * 1024) {
        header('Location: index.php?erro=tamanho');
        exit;
    }

    $dir_upload = __DIR__ . '/../uploads/trabalhos/';
    if (!is_dir($dir_upload)) {
        mkdir($dir_upload, 0755, true);
    }

    $nome_arquivo = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $dir_upload . $nome_arquivo)) {
        header('Location: index.php?erro=upload');
        exit;
    }

    $imagem = 'uploads/trabalhos/' . $nome_arquivo;
}

$novo = [
    'id'        => (string) time() . rand(100, 999),
    'titulo'    => $titulo,
    'descricao' => $descricao,
    'data'      => $data,
    'imagem'    => $imagem,
];

array_unshift($trabalhos, $novo);

file_put_contents($arquivo_json, json_encode($trabalhos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: index.php?msg=salvo');
exit;
