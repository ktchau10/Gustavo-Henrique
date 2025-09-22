<?php
require_once 'admin_auth_check.php';
require_once '../config.php';

// Roteador de visualização simples
$view = $_GET['view'] ?? 'dashboard'; // Visualização padrão é o dashboard

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-page-container">
        <aside class="admin-sidebar">
            <div class="admin-sidebar__header">
                <h2>Admin</h2>
            </div>
            <ul class="admin-sidebar__nav">
                <li class="<?php echo ($view === 'dashboard') ? 'active' : ''; ?>"><a href="admin.php?view=dashboard"><i class='bx bxs-dashboard'></i> Dashboard</a></li>
                <li class="<?php echo (strpos($view, 'pedido') === 0) ? 'active' : ''; ?>"><a href="admin.php?view=pedidos"><i class='bx bx-package'></i> Pedidos</a></li>
                <li class="<?php echo (in_array($view, ['produtos', 'add_produto', 'edit_produto'])) ? 'active' : ''; ?>"><a href="admin.php?view=produtos"><i class='bx bxs-t-shirt'></i> Produtos</a></li>
                <li class="<?php echo ($view === 'clientes') ? 'active' : ''; ?>"><a href="admin.php?view=clientes"><i class='bx bxs-group'></i> Clientes</a></li>
                <li><a href="admin_logout.php"><i class='bx bx-log-out'></i> Sair</a></li>
            </ul>
        </aside>
        <main class="admin-main-content">
            <?php 
                // Carrega a visualização apropriada
                switch ($view) {
                    case 'pedidos':
                        include 'views/admin_view_pedidos.php';
                        break;
                    case 'pedido_detalhe':
                        include 'views/admin_view_pedido_detalhe.php';
                        break;
                    case 'produtos':
                        include 'views/admin_view_produtos.php';
                        break;
                    case 'add_produto':
                        include 'views/admin_view_add_produto.php';
                        break;
                    case 'edit_produto':
                        include 'views/admin_view_edit_produto.php';
                        break;
                    case 'clientes':
                        include 'views/admin_view_clientes.php';
                        break;
                    case 'dashboard':
                    default:
                        include 'views/admin_view_dashboard.php';
                        break;
                }
            ?>
        </main>
    </div>
</body>
</html>