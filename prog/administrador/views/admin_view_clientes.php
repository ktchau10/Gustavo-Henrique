<?php
// Busca clientes únicos (baseado no email) da tabela de pedidos
$result_clientes = mysqli_query($conn, 
    "SELECT DISTINCT cliente_email, cliente_nome, cliente_endereco FROM pedidos ORDER BY cliente_nome ASC"
);
?>
<header class="admin-main-header">
    <h1>Clientes</h1>
    <p>Lista de todos os clientes que já realizaram pedidos.</p>
</header>
<div class="admin-content-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nome do Cliente</th>
                <th>Email</th>
                <th>Último Endereço Registrado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_clientes) > 0): ?>
                <?php while($cliente = mysqli_fetch_assoc($result_clientes)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['cliente_nome']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['cliente_email']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['cliente_endereco']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
