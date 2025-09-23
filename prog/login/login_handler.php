<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$usuario_input = $_POST['usuario'];
$senha_input = $_POST['senha'];

// 1. Tenta autenticar como Administrador
$stmt_admin = mysqli_prepare($conn, "SELECT id, usuario, senha FROM administradores WHERE usuario = ?");
mysqli_stmt_bind_param($stmt_admin, "s", $usuario_input);
mysqli_stmt_execute($stmt_admin);
$result_admin = mysqli_stmt_get_result($stmt_admin);

if ($admin = mysqli_fetch_assoc($result_admin)) {
    // ATENÇÃO: Comparação de senha em texto plano. MUITO INSEGURO para produção.
    if ($senha_input === $admin['senha']) {
        $_SESSION['user_type'] = 'admin';
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['usuario'];
        header('Location: administrador/admin.php');
        exit;
    }
}

// 2. Se não for admin, tenta autenticar como Cliente
// ATENÇÃO: Assumindo que existe uma tabela `clientes` com colunas `email` e `senha`.
$stmt_cliente = mysqli_prepare($conn, "SELECT id, nome, email, senha FROM clientes WHERE email = ?");
mysqli_stmt_bind_param($stmt_cliente, "s", $usuario_input);
mysqli_stmt_execute($stmt_cliente);
$result_cliente = mysqli_stmt_get_result($stmt_cliente);

if ($cliente = mysqli_fetch_assoc($result_cliente)) {
    // ATENÇÃO: Comparação de senha em texto plano. MUITO INSEGURO para produção.
    if ($senha_input === $cliente['senha']) {
        $_SESSION['user_type'] = 'cliente';
        $_SESSION['user_id'] = $cliente['id'];
        $_SESSION['user_name'] = $cliente['nome'];
        header('Location: usuarios/main.php');
        exit;
    }
}

// 3. Se não encontrou em nenhuma tabela, redireciona com erro
header('Location: index.php?error=1');
exit;
