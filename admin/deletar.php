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

$id  = (int) $_POST['id'];
$pdo = db();

// Remove arquivos fisicos
$fotos = $pdo->prepare("SELECT arquivo FROM trabalho_fotos WHERE trabalho_id = ?");
$fotos->execute([$id]);
foreach ($fotos->fetchAll() as $foto) {
    $caminho = __DIR__ . '/../' . $foto['arquivo'];
    if (file_exists($caminho)) unlink($caminho);
}

// Remove do banco (CASCADE apaga trabalho_fotos)
$pdo->prepare("DELETE FROM trabalhos WHERE id = ?")->execute([$id]);

header('Location: index.php?msg=deletado');
exit;
