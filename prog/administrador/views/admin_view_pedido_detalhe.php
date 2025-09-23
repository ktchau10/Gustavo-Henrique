<?php
if (!isset($_GET['id'])) {
    echo "<p>ID do pedido não fornecido.</p>";
    return;
}
$pedido_id = (int)$_GET['id'];

// Busca dados do pedido principal
$stmt_pedido = mysqli_prepare($conn, "SELECT * FROM pedidos WHERE id = ?");
mysqli_stmt_bind_param($stmt_pedido, "i", $pedido_id);
mysqli_stmt_execute($stmt_pedido);
$result_pedido = mysqli_stmt_get_result($stmt_pedido);
$pedido = mysqli_fetch_assoc($result_pedido);

if (!$pedido) {
    echo "<p>Pedido não encontrado.</p>";
    return;
}

// Busca itens do pedido
$stmt_itens = mysqli_prepare($conn, 
    "SELECT pi.*, p.nome as produto_nome 
     FROM pedido_itens pi 
     JOIN produtos p ON pi.produto_id = p.id 
     WHERE pi.pedido_id = ?"
);
mysqli_stmt_bind_param($stmt_itens, "i", $pedido_id);
mysqli_stmt_execute($stmt_itens);
$result_itens = mysqli_stmt_get_result($stmt_itens);

?>
<header class="admin-main-header">
    <h1>Detalhes do Pedido #<?php echo $pedido['id']; ?></h1>
    <a href="admin.php?view=pedidos" class="back-link">&larr; Voltar para todos os pedidos</a>
</header>

<div class="admin-content-panel">
    <div class="order-details-container">
        <div class="customer-details">
            <h3>Informações do Cliente</h3>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars(ucfirst(strtolower($pedido['cliente_nome']))); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['cliente_email']); ?></p>
            <p><strong>Endereço de Entrega:</strong> <?php echo htmlspecialchars($pedido['cliente_endereco']); ?></p>
            <p><strong>Data do Pedido:</strong> <?php echo date("d/m/Y H:i", strtotime($pedido['data_pedido'])); ?></p>
            <p><strong>Valor Total:</strong> <span class="total-price">R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></span></p>
        </div>
        <div class="order-items-details">
            <h3>Itens do Pedido</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Cor</th>
                        <th>Tamanho</th>
                        <th>Qtd.</th>
                        <th>Preço Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = mysqli_fetch_assoc($result_itens)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(ucfirst(strtolower($item['produto_nome']))); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst(strtolower($item['cor']))); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst(strtolower($item['tamanho']))); ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
