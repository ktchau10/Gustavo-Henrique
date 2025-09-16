<?php
if (!isset($_GET['id'])) {
    echo "<p>ID do produto não fornecido.</p>";
    return;
}
$produto_id = (int)$_GET['id'];

// Busca dados do produto
$stmt = mysqli_prepare($conn, "SELECT * FROM produtos WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $produto_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produto = mysqli_fetch_assoc($result);

if (!$produto) {
    echo "<p>Produto não encontrado.</p>";
    return;
}

// Busca todas as categorias existentes para o dropdown
$sql_categorias = "SELECT DISTINCT categoria FROM produtos ORDER BY categoria ASC";
$result_categorias = mysqli_query($conn, $sql_categorias);

// Busca todas as variações do produto
$sql_variacoes = "SELECT * FROM variacoes WHERE produto_id = ? ORDER BY cor, tamanho";
$stmt_variacoes = mysqli_prepare($conn, $sql_variacoes);
mysqli_stmt_bind_param($stmt_variacoes, "i", $produto_id);
mysqli_stmt_execute($stmt_variacoes);
$result_variacoes = mysqli_stmt_get_result($stmt_variacoes);

?>
<header class="admin-main-header">
    <h1>Editar Produto #<?php echo $produto['id']; ?></h1>
    <a href="admin.php?view=produtos" class="back-link">&larr; Voltar para a lista de produtos</a>
</header>
<div class="admin-content-panel">
    <form action="admin_produto_handler.php" method="POST" enctype="multipart/form-data" class="cart-form">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
        
        <div class="form-group">
            <label for="nome">Nome do Produto</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="preco">Preço (R$)</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria">
                <?php 
                mysqli_data_seek($result_categorias, 0); // Reset pointer
                while($cat = mysqli_fetch_assoc($result_categorias)) {
                    $is_selected = ($produto['categoria'] === $cat['categoria']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($cat['categoria']) . "' $is_selected>" . htmlspecialchars($cat['categoria']) . "</option>";
                }
                ?>
            </select>
            <label for="nova_categoria" style="margin-top: 1rem;">Ou adicione uma nova categoria:</label>
            <input type="text" id="nova_categoria" name="nova_categoria">
        </div>
        
        <div class="form-group">
            <label for="imagem">Imagem do Produto</label>
            <p>Imagem Atual: <img src="../imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="Imagem atual" style="width: 100px; height: auto;"></p>
            <label for="imagem">Substituir Imagem (opcional)</label>
            <input type="file" id="imagem" name="imagem" accept="image/*">
        </div>
        
        <button type="submit" class="add-to-cart-btn">Salvar Alterações</button>
    </form>
</div>

<div class="admin-content-panel" style="margin-top: 2rem;">
    <header class="admin-main-header">
        <h2>Gerenciar Variações de Estoque</h2>
    </header>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Cor</th>
                <th>Tamanho</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_variacoes) > 0): ?>
                <?php while($variacao = mysqli_fetch_assoc($result_variacoes)):
                <tr>
                    <td><?php echo htmlspecialchars($variacao['cor']); ?></td>
                    <td><?php echo htmlspecialchars($variacao['tamanho']); ?></td>
                    <td><?php echo $variacao['estoque']; ?></td>
                    <td>
                        <a href="admin.php?view=edit_variation&id=<?php echo $variacao['id']; ?>" class="action-btn">Editar</a>
                        <a href="admin_produto_handler.php?action=delete_variation&id=<?php echo $variacao['id']; ?>&produto_id=<?php echo $produto_id; ?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja remover esta variação?');">Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhuma variação cadastrada para este produto.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr style="margin: 2rem 0;">

    <h3>Adicionar Nova Variação</h3>
    <form action="admin_produto_handler.php" method="POST" class="cart-form">
        <input type="hidden" name="action" value="add_variation">
        <input type="hidden" name="produto_id" value="<?php echo $produto_id; ?>">
        <div class="form-group-inline">
            <div class="form-group">
                <label for="cor">Cor</label>
                <input type="text" id="cor" name="cor" required>
            </div>
            <div class="form-group">
                <label for="tamanho">Tamanho</label>
                <input type="text" id="tamanho" name="tamanho" required>
            </div>
            <div class="form-group">
                <label for="estoque">Estoque</label>
                <input type="number" id="estoque" name="estoque" min="0" required>
            </div>
            <button type="submit" class="add-to-cart-btn">Adicionar Variação</button>
        </div>
    </form>
</div>
