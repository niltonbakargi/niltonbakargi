<?php
require '../config/db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = trim($_POST['senha'] ?? '');
    if (strlen($senha) >= 6) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = db()->prepare("UPDATE admin SET senha = ? WHERE usuario = 'admin'");
        $stmt->execute([$hash]);
        $msg = 'Senha atualizada! Apague este arquivo agora: admin/reset.php';
    } else {
        $msg = 'Senha precisa ter no minimo 6 caracteres.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Reset de Senha</title>
  <style>
    body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #f8fafc; }
    .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 32px; width: 320px; }
    h2 { font-size: 1rem; margin-bottom: 20px; }
    input { width: 100%; padding: 9px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; margin-bottom: 14px; box-sizing: border-box; }
    button { width: 100%; padding: 10px; background: #0284c7; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem; }
    .msg { margin-top: 14px; font-size: 0.85rem; color: #16a34a; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Definir nova senha — admin</h2>
    <form method="POST">
      <input type="password" name="senha" placeholder="Nova senha (min. 6 caracteres)" required />
      <button type="submit">Salvar</button>
    </form>
    <?php if ($msg): ?><p class="msg"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
  </div>
</body>
</html>
