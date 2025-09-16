<?php
if (!isset($_GET['id'])) {
    echo "<p>ID da variação não fornecido.</p>";
    return;
}
$variacao_id = (int)$_GET['id'];

// Busca dados da variação
$stmt = mysqli_prepare($conn, "SELECT * FROM variacoes WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $variacao_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$variacao = mysqli_fetch_assoc($result);

if (!$variacao) {
    echo "<p>Variação não encontrada.</p>";
    return;
}

$produto_id = $variacao['produto_id']; // Para o link de "voltar"
?>
<header class="admin-main-header">
    <h1>Editar Variação de Estoque</h1>
    <a href="admin.php?view=edit_produto&id=<?php echo $produto_id; ?>" class="back-link">&larr; Voltar para o produto</a>
</header>
<div class="admin-content-panel">
    <form action="admin_produto_handler.php" method="POST" class="cart-form">
        <input type="hidden" name="action" value="edit_variation">
        <input type="hidden" name="variacao_id" value="<?php echo $variacao['id']; ?>">
        <input type="hidden" name="produto_id" value="<?php echo $produto_id; ?>"> 

        <div class="form-group">
            <label for="cor">Cor</label>
            <input type="text" id="cor" name="cor" value="<?php echo htmlspecialchars($variacao['cor']); ?>" required>
        </div>
        <div class="form-group">
            <label for="tamanho">Tamanho</label>
            <input type="text" id="tamanho" name="tamanho" value="<?php echo htmlspecialchars($variacao['tamanho']); ?>" required>
        </div>
        <div class="form-group">
            <label for="estoque">Estoque</label>
            <input type="number" id="estoque" name="estoque" min="0" value="<?php echo $variacao['estoque']; ?>" required>
        </div>
        <button type="submit" class="add-to-cart-btn">Salvar Alterações na Variação</button>
    </form>
</div>
