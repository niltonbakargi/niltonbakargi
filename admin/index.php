<?php
session_start();
require 'config.php';

$erro    = '';
$sucesso = '';

// Login
if (isset($_POST['acao']) && $_POST['acao'] === 'login') {
    if ($_POST['senha'] === ADMIN_SENHA) {
        $_SESSION['logado'] = true;
    } else {
        $erro = 'Senha incorreta.';
    }
}

// Mensagens de retorno
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'salvo')   $sucesso = 'Trabalho adicionado com sucesso!';
    if ($_GET['msg'] === 'deletado') $sucesso = 'Trabalho removido.';
}
if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'campos')  $erro = 'Preencha todos os campos obrigatorios.';
    if ($_GET['erro'] === 'formato') $erro = 'Formato de imagem invalido. Use JPG, PNG ou WebP.';
    if ($_GET['erro'] === 'tamanho') $erro = 'Imagem muito grande. Limite: 5MB.';
    if ($_GET['erro'] === 'upload')  $erro = 'Erro ao fazer upload da imagem.';
}

// Tela de login
if (empty($_SESSION['logado'])): ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Montserrat', sans-serif;
      background: #f8fafc;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    .card {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 40px 36px;
      width: 100%;
      max-width: 360px;
    }
    h1 {
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #1e293b;
      margin-bottom: 28px;
      text-align: center;
    }
    label { display: block; font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
    input[type=password] {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.95rem;
      margin-bottom: 20px;
      outline: none;
    }
    input[type=password]:focus { border-color: #0284c7; }
    button {
      width: 100%;
      padding: 11px;
      background: #0284c7;
      color: white;
      border: none;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      cursor: pointer;
    }
    button:hover { background: #0369a1; }
    .erro { color: #dc2626; font-size: 0.85rem; margin-top: 14px; text-align: center; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Admin</h1>
    <form method="POST">
      <input type="hidden" name="acao" value="login" />
      <label for="senha">Senha</label>
      <input type="password" id="senha" name="senha" autofocus required />
      <button type="submit">Entrar</button>
    </form>
    <?php if ($erro): ?><p class="erro"><?= htmlspecialchars($erro) ?></p><?php endif; ?>
  </div>
</body>
</html>
<?php exit; endif;

// Painel admin — carrega trabalhos
$arquivo_json = __DIR__ . '/../trabalhos.json';
$trabalhos = [];
if (file_exists($arquivo_json)) {
    $trabalhos = json_decode(file_get_contents($arquivo_json), true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — Trabalhos</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Montserrat', sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; }

    .topbar {
      background: white;
      border-bottom: 1px solid #e2e8f0;
      padding: 16px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .topbar h1 { font-size: 0.9rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; }
    .topbar a { font-size: 0.8rem; color: #64748b; text-decoration: none; letter-spacing: 1px; text-transform: uppercase; }
    .topbar a:hover { color: #dc2626; }

    .container { max-width: 780px; margin: 0 auto; padding: 40px 24px; }

    .aviso { padding: 12px 16px; border-radius: 6px; font-size: 0.875rem; margin-bottom: 28px; }
    .aviso.sucesso { background: #dcfce7; color: #166534; }
    .aviso.erro    { background: #fee2e2; color: #991b1b; }

    .secao-titulo {
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #64748b;
      margin-bottom: 16px;
      padding-bottom: 8px;
      border-bottom: 1px solid #e2e8f0;
    }

    /* Formulario */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 10px; padding: 28px; margin-bottom: 48px; }
    .form-grupo { margin-bottom: 20px; }
    .form-grupo label { display: block; font-size: 0.75rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
    .form-grupo input,
    .form-grupo textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.95rem;
      outline: none;
      color: #1e293b;
    }
    .form-grupo input:focus,
    .form-grupo textarea:focus { border-color: #0284c7; }
    .form-grupo textarea { resize: vertical; min-height: 90px; }
    .form-linha { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .btn-salvar {
      padding: 11px 28px;
      background: #16a34a;
      color: white;
      border: none;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.875rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      cursor: pointer;
    }
    .btn-salvar:hover { background: #15803d; }

    /* Lista de trabalhos */
    .trabalho-item {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 12px;
      display: flex;
      gap: 16px;
      align-items: flex-start;
    }
    .trabalho-item img {
      width: 80px;
      height: 60px;
      object-fit: cover;
      border-radius: 4px;
      flex-shrink: 0;
      background: #f1f5f9;
    }
    .trabalho-item .sem-img {
      width: 80px;
      height: 60px;
      background: #f1f5f9;
      border-radius: 4px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      color: #94a3b8;
    }
    .trabalho-info { flex: 1; min-width: 0; }
    .trabalho-info .titulo { font-size: 0.95rem; font-weight: 600; margin-bottom: 4px; }
    .trabalho-info .data   { font-size: 0.75rem; color: #64748b; margin-bottom: 6px; }
    .trabalho-info .desc   { font-size: 0.825rem; color: #64748b; line-height: 1.5; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-deletar {
      padding: 6px 14px;
      background: white;
      color: #dc2626;
      border: 1px solid #fecaca;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.75rem;
      font-weight: 600;
      cursor: pointer;
      flex-shrink: 0;
    }
    .btn-deletar:hover { background: #fee2e2; }

    .vazio { font-size: 0.875rem; color: #94a3b8; text-align: center; padding: 32px; }
  </style>
</head>
<body>

<div class="topbar">
  <h1>Painel Admin</h1>
  <a href="logout.php">Sair</a>
</div>

<div class="container">

  <?php if ($sucesso): ?>
    <div class="aviso sucesso"><?= htmlspecialchars($sucesso) ?></div>
  <?php elseif ($erro): ?>
    <div class="aviso erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <!-- Formulario de novo trabalho -->
  <p class="secao-titulo">Novo trabalho</p>
  <div class="form-card">
    <form method="POST" action="salvar.php" enctype="multipart/form-data">
      <div class="form-grupo">
        <label for="titulo">Titulo *</label>
        <input type="text" id="titulo" name="titulo" placeholder="Ex: Georreferenciamento Fazenda Sao Joao" required />
      </div>
      <div class="form-linha">
        <div class="form-grupo">
          <label for="data">Data *</label>
          <input type="date" id="data" name="data" required />
        </div>
        <div class="form-grupo">
          <label for="imagem">Imagem (JPG / PNG / WebP, max 5MB)</label>
          <input type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp" />
        </div>
      </div>
      <div class="form-grupo">
        <label for="descricao">Descricao *</label>
        <textarea id="descricao" name="descricao" placeholder="Descreva brevemente o trabalho realizado..." required></textarea>
      </div>
      <button type="submit" class="btn-salvar">Salvar</button>
    </form>
  </div>

  <!-- Lista de trabalhos existentes -->
  <p class="secao-titulo">Trabalhos cadastrados (<?= count($trabalhos) ?>)</p>

  <?php if (empty($trabalhos)): ?>
    <p class="vazio">Nenhum trabalho cadastrado ainda.</p>
  <?php else: ?>
    <?php foreach ($trabalhos as $t): ?>
      <div class="trabalho-item">
        <?php if (!empty($t['imagem'])): ?>
          <img src="../<?= htmlspecialchars($t['imagem']) ?>" alt="" />
        <?php else: ?>
          <div class="sem-img">sem foto</div>
        <?php endif; ?>
        <div class="trabalho-info">
          <div class="titulo"><?= htmlspecialchars($t['titulo']) ?></div>
          <div class="data"><?= htmlspecialchars($t['data']) ?></div>
          <div class="desc"><?= htmlspecialchars($t['descricao']) ?></div>
        </div>
        <form method="POST" action="deletar.php" onsubmit="return confirm('Remover este trabalho?')">
          <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>" />
          <button type="submit" class="btn-deletar">Remover</button>
        </form>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>
</body>
</html>
