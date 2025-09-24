<?php
// Configurações do banco de dados
$db_host = 'localhost';
$db_user = 'root'; // Usuário padrão do XAMPP
$db_pass = '';     // Senha padrão do XAMPP é vazia
$db_name = 'loja'; // O nome do banco de dados foi corrigido aqui

// Habilita a exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cria a conexão com o banco de dados
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Verifica se a conexão falhou e exibe uma mensagem de erro clara
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Define o charset para UTF-8 para evitar problemas com acentuação
mysqli_set_charset($conn, "utf8");
?>
