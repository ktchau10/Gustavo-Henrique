<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à Nossa Loja</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: var(--bg-color);
        }
        .portal-container {
            display: flex;
            gap: 3rem;
            text-align: center;
        }
        .portal-choice {
            background-color: var(--card-bg-color);
            padding: 4rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition-smooth);
            width: 300px;
        }
        .portal-choice:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .portal-choice a {
            text-decoration: none;
        }
        .portal-choice h2 {
            font-size: 2rem;
            margin-top: 1rem;
            color: var(--primary-color);
        }
        .portal-choice i {
            font-size: 4rem;
            color: var(--accent-color);
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="portal-container">
        <div class="portal-choice">
            <a href="main.php">
                <i class='bx bx-store'></i>
                <h2>Área do Cliente</h2>
            </a>
        </div>
        <div class="portal-choice">
            <a href="admin_login.php">
                <i class='bx bx-shield-quarter'></i>
                <h2>Área do Administrador</h2>
            </a>
        </div>
    </div>
</body>
</html>
