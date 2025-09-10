<?php
session_start();
require_once 'config.php';

$error_message = '';

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $stmt = mysqli_prepare($conn, "SELECT id, usuario, senha FROM administradores WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($admin = mysqli_fetch_assoc($result)) {
        // Verifica a senha (comparação de texto simples - NÃO SEGURO)
        if ($senha === $admin['senha']) {
            // Sucesso! Armazena na sessão e redireciona
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_usuario'] = $admin['usuario'];
            header('Location: admin.php');
            exit;
        } else {
            $error_message = "Usuário ou senha inválidos.";
        }
    } else {
        $error_message = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login do Administrador</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            width: 400px;
            padding: 3rem;
            background-color: var(--card-bg-color);
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 2rem;
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login do Admin</h1>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="admin_login.php" method="POST" class="cart-form">
            <div class="form-group">
                <label for="usuario">Usuário</label>
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
