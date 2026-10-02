#!/bin/bash
# ============================================================
#  setup_banco.sh
#  Execute no terminal do cPanel:  bash setup_banco.sh
#
#  ANTES de rodar:
#  1. Crie o banco via cPanel > MySQL Databases
#  2. Crie o usuario e associe ao banco (privilegio ALL)
#  3. Rode este script informando as credenciais
# ============================================================

echo ""
echo "=== Criacao das tabelas — niltonbakargi ==="
echo ""
printf "Usuario MySQL (formato: seulogin_usuario): "
read DB_USER

printf "Senha MySQL: "
read -s DB_PASS
echo ""

printf "Nome do banco  (formato: seulogin_banco): "
read DB_NAME
echo ""

# Filtra CREATE DATABASE e USE (nao necessarios: o banco ja existe)
grep -v -E "^CREATE DATABASE|^USE " database.sql \
  | mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME"

if [ $? -eq 0 ]; then
  echo ""
  echo "Tabelas criadas com sucesso no banco '$DB_NAME'!"
else
  echo ""
  echo "Erro. Verifique usuario, senha e nome do banco."
fi
