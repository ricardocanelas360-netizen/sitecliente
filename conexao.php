<?php
/**
 * Arquivo de Conexão com o Banco de Dados MySQL
 * Este arquivo centraliza a conexão com o banco de dados
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_db');

// Criar conexão
$conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexão
if ($conexao->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conexao->connect_error);
}

// Definir charset para UTF-8
$conexao->set_charset("utf8mb4");

?>
