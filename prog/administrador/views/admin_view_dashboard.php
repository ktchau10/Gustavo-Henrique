<?php
// --- Lógica para buscar estatísticas do Dashboard ---
$result_total_vendas = mysqli_query($conn, "SELECT SUM(valor_total) as total FROM pedidos");
$total_vendas = mysqli_fetch_assoc($result_total_vendas)['total'] ?? 0;

$result_total_pedidos = mysqli_query($conn, "SELECT COUNT(id) as total FROM pedidos");
$total_pedidos = mysqli_fetch_assoc($result_total_pedidos)['total'] ?? 0;

$result_total_produtos = mysqli_query($conn, "SELECT COUNT(id) as total FROM produtos");
$total_produtos = mysqli_fetch_assoc($result_total_produtos)['total'] ?? 0;

$result_total_clientes = mysqli_query($conn, "SELECT COUNT(DISTINCT cliente_email) as total FROM pedidos");
$total_clientes = mysqli_fetch_assoc($result_total_clientes)['total'] ?? 0;
?>

<header class="admin-main-header">
    <h1>Dashboard</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_usuario']); ?>!</p>
</header>

<div class="admin-dashboard-stats">
    <div class="stat-card">
        <div class="stat-card__icon"><i class='bx bx-dollar-circle'></i></div>
        <div class="stat-card__info">
            <h4>Total de Vendas</h4>
            <p>R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon"><i class='bx bx-receipt'></i></div>
        <div class="stat-card__info">
            <h4>Total de Pedidos</h4>
            <p><?php echo $total_pedidos; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon"><i class='bx bxs-t-shirt'></i></div>
        <div class="stat-card__info">
            <h4>Produtos Cadastrados</h4>
            <p><?php echo $total_produtos; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon"><i class='bx bxs-user-account'></i></div>
        <div class="stat-card__info">
            <h4>Total de Clientes</h4>
            <p><?php echo $total_clientes; ?></p>
        </div>
    </div>
</div>
