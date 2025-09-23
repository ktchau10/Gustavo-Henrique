<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e se é um administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Se não estiver logado como admin, destrói a sessão e redireciona para o login
    session_destroy();
    header('Location: ../login/index.php');
    exit;
}
