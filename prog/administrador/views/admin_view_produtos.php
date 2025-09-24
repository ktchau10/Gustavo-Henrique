<?php
// Busca todos os produtos do banco de dados
$result_produtos = mysqli_query($conn, "SELECT * FROM produtos ORDER BY nome ASC");
?>
<header class="admin-main-header">
    <h1>Gerenciar Produtos</h1>
    <a href="admin.php?view=add_produto" class="btn btn--primary admin-header-btn">Adicionar Novo Produto</a>
</header>
<div class="admin-content-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome do Produto</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_produtos) > 0): ?>
                <?php while($produto = mysqli_fetch_assoc($result_produtos)): ?>
                <tr>
                    <td><img src="../imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars(ucfirst(strtolower($produto['nome']))); ?>" style="width: 50px; height: auto;"></td>
                    <td><?php echo htmlspecialchars(ucfirst(strtolower($produto['nome']))); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst(strtolower($produto['categoria']))); ?></td>
                    <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="admin.php?view=edit_produto&id=<?php echo $produto['id']; ?>" class="btn action-btn">Editar</a>
                        <a href="admin_produto_handler.php?action=delete&id=<?php echo $produto['id']; ?>" class="btn action-btn delete-btn" onclick="return confirm('Tem certeza que deseja remover este produto?');">Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum produto cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
