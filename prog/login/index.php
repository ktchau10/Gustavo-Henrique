<?php
session_start();

// Se o usuário já estiver logado, redireciona para a página correta
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'admin') {
        header('Location: ../administrador/admin.php');
    } else {
        header('Location: ../usuarios/main.php');
    }
    exit;
}

$error_message = '';
if (isset($_GET['error'])) {
    $error_message = "Usuário ou senha inválidos.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nossa Loja</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
</head>
<body class="login-body">
    <div class="login-container">
        <h1>Nossa Loja</h1>
        <p>Bem-vindo! Faça login para continuar.</p>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="login_handler.php" method="POST" class="cart-form">
            <div class="form-group">
                <label for="usuario">Usuário ou Email</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <button type="submit" class="add-to-cart-btn">Entrar</button>
        </form>
    </div>
</body>
</html>
