<?php
// Busca todas as categorias existentes para o dropdown
$sql_categorias = "SELECT DISTINCT categoria FROM produtos ORDER BY categoria ASC";
$result_categorias = mysqli_query($conn, $sql_categorias);
?>
<header class="admin-main-header">
    <h1>Adicionar Novo Produto</h1>
    <a href="admin.php?view=produtos" class="back-link">&larr; Voltar para a lista de produtos</a>
</header>
<div class="admin-content-panel">
    <form action="admin_produto_handler.php" method="POST" enctype="multipart/form-data" class="cart-form">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label for="nome">Nome do Produto</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="preco">Preço (R$)</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required>
        </div>
        
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria">
                <option value="" disabled selected>Selecione uma categoria</option>
                <?php 
                while($cat = mysqli_fetch_assoc($result_categorias)) {
                    echo "<option value='" . htmlspecialchars($cat['categoria']) . "'>" . htmlspecialchars($cat['categoria']) . "</option>";
                }
                ?>
            </select>
            <label for="nova_categoria" style="margin-top: 1rem;">Ou adicione uma nova categoria:</label>
            <input type="text" id="nova_categoria" name="nova_categoria">
        </div>
        
        <div class="form-group">
            <label for="imagem">Imagem do Produto</label>
            <input type="file" id="imagem" name="imagem" accept="image/*" required>
        </div>
        
        <button type="submit" class="add-to-cart-btn">Salvar Produto</button>
    </form>
</div>
