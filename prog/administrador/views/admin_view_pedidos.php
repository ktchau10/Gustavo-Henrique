<?php
$result_pedidos = mysqli_query($conn, "SELECT * FROM pedidos ORDER BY data_pedido DESC");
?>
<header class="admin-main-header">
    <h1>Pedidos Recebidos</h1>
</header>
<div class="admin-content-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID do Pedido</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Valor Total</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_pedidos) > 0): ?>
                <?php while($pedido = mysqli_fetch_assoc($result_pedidos)): ?>
                <tr>
                    <td>#<?php echo $pedido['id']; ?></td>
                    <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['cliente_email']); ?></td>
                    <td>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($pedido['data_pedido'])); ?></td>
                    <td>
                        <a href="admin.php?view=pedido_detalhe&id=<?php echo $pedido['id']; ?>" class="action-btn">Ver Detalhes</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum pedido encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
