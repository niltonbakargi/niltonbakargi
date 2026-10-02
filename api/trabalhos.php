<?php
require '../config/db.php';

header('Content-Type: application/json; charset=utf-8');

$rows = db()->query("
    SELECT
        t.id,
        t.titulo,
        t.descricao,
        t.data_obra  AS data,
        t.local_obra AS local,
        t.cliente,
        t.categoria,
        (SELECT f.arquivo FROM trabalho_fotos f
         WHERE f.trabalho_id = t.id
         ORDER BY f.ordem LIMIT 1) AS imagem
    FROM trabalhos t
    WHERE t.ativo = 1
    ORDER BY t.criado_em DESC
")->fetchAll();

echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
