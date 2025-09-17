<?php
session_start();

// Se a sessão não existe ou não está como logada, redireciona para o login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}
