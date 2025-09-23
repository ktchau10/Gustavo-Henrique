<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado (seja admin ou cliente)
if (!isset($_SESSION['user_type'])) {
    // Se não estiver logado, redireciona para a página de login unificada
    header('Location: ../login/index.php');
    exit;
}
