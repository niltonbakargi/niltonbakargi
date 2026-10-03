<?php
session_start();
require 'config.php';

if (empty($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

$titulo     = trim($_POST['titulo']     ?? '');
$descricao  = trim($_POST['descricao']  ?? '');
$data_obra  = trim($_POST['data']       ?? '');
$local_obra = trim($_POST['local_obra'] ?? '');
$cliente    = trim($_POST['cliente']    ?? '');
$categoria  = in_array($_POST['categoria'] ?? '', ['florestal', 'geo', 'mecanica', 'educacao', 'ti'])
              ? $_POST['categoria'] : 'florestal';

if (!$titulo || !$descricao || !$data_obra) {
    header('Location: index.php?erro=campos');
    exit;
}

$pdo  = db();
$stmt = $pdo->prepare(
    "INSERT INTO trabalhos (titulo, categoria, descricao, data_obra, local_obra, cliente) VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->execute([$titulo, $categoria, $descricao, $data_obra, $local_obra ?: null, $cliente ?: null]);
$trabalho_id = (int) $pdo->lastInsertId();

// Upload de multiplas imagens
if (!empty($_FILES['imagens']['name'][0])) {
    $dir_upload = __DIR__ . '/../uploads/trabalhos/';
    if (!is_dir($dir_upload)) {
        mkdir($dir_upload, 0755, true);
    }

    $permitidos  = ['jpg', 'jpeg', 'png', 'webp'];
    $stmt_foto   = $pdo->prepare(
        "INSERT INTO trabalho_fotos (trabalho_id, arquivo, ordem) VALUES (?, ?, ?)"
    );
    $ordem = 0;

    foreach ($_FILES['imagens']['tmp_name'] as $i => $tmp) {
        if ($_FILES['imagens']['error'][$i] !== UPLOAD_ERR_OK) continue;
        if ($_FILES['imagens']['size'][$i]  > 5 * 1024 * 1024)  continue;

        $ext = strtolower(pathinfo($_FILES['imagens']['name'][$i], PATHINFO_EXTENSION));
        if (!in_array($ext, $permitidos)) continue;

        $nome = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (move_uploaded_file($tmp, $dir_upload . $nome)) {
            $stmt_foto->execute([$trabalho_id, 'uploads/trabalhos/' . $nome, $ordem++]);
        }
    }
}

header('Location: index.php?msg=salvo');
exit;
