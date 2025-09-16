<?php
session_start();
require_once '../config.php';

// --- Validação do ID do produto ---
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Produto não encontrado!");
}
$produto_id = $_GET['id'];

// --- Lógica para buscar o produto específico ---
$sql_produto = "SELECT nome, descricao, preco, categoria, imagem_url FROM produtos WHERE id = ?";
$stmt_produto = mysqli_prepare($conn, $sql_produto);
mysqli_stmt_bind_param($stmt_produto, "i", $produto_id);
mysqli_stmt_execute($stmt_produto);
$result_produto = mysqli_stmt_get_result($stmt_produto);

if (mysqli_num_rows($result_produto) === 0) {
    die("Produto não encontrado!");
}
$produto = mysqli_fetch_assoc($result_produto);

// --- Lógica para buscar as variações do produto ---
$sql_variacoes = "SELECT cor, tamanho, estoque FROM variacoes WHERE produto_id = ? ORDER BY cor, tamanho";
$stmt_variacoes = mysqli_prepare($conn, $sql_variacoes);
mysqli_stmt_bind_param($stmt_variacoes, "i", $produto_id);
mysqli_stmt_execute($stmt_variacoes);
$result_variacoes = mysqli_stmt_get_result($stmt_variacoes);

$variacoes = [];
while($row = mysqli_fetch_assoc($result_variacoes)) {
    $variacoes[] = $row;
}
$cores_disponiveis = array_unique(array_column($variacoes, 'cor'));
$tamanhos_disponiveis = array_unique(array_column($variacoes, 'tamanho'));

// --- Lógica para buscar categorias para a barra lateral ---
$sql_categorias = "SELECT DISTINCT categoria FROM produtos ORDER BY categoria ASC";
$result_categorias = mysqli_query($conn, $sql_categorias);

$cart_items = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - Nossa Loja</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <header class="header">
        <a href="main.php" class="header__logo">Nossa<span>Loja</span></a>
        <div class="header__nav">
            <div class="header__toggle" id="header-toggle">
                <i class='bx bx-menu'></i>
            </div>
            <div class="header__cart-icon" id="cart-icon">
                <i class='bx bx-shopping-bag'></i>
                <span class="cart-count"><?php echo count($cart_items); ?></span>
            </div>
        </div>
    </header>

    <div class="page-container">
        <aside class="sidebar" id="sidebar">
            <h3 class="sidebar__title">Categorias</h3>
            <ul class="sidebar__list">
                <li class="sidebar__item"><a href="main.php" class="sidebar__link">Mostrar Todas</a></li>
                <?php if (mysqli_num_rows($result_categorias) > 0): ?>
                    <?php while($cat_row = mysqli_fetch_assoc($result_categorias)): ?>
                        <li class="sidebar__item">
                            <a href="main.php?categoria=<?php echo urlencode($cat_row['categoria']); ?>" class="sidebar__link">
                                <?php echo htmlspecialchars($cat_row['categoria']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </aside>

        <main class="main-content">
            <div class="product-detail-container">
                <div class="product-image-main">
                    <img src="../imagens/<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                </div>
                <div class="product-details-info">
                    <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
                    <p class="price-detail">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <p class="description-detail"><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
                    
                    <form action="cart_handler.php" method="POST" class="cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="produto_id" value="<?php echo $produto_id; ?>">
                        
                        <div class="form-group">
                            <label for="cor">Cor:</label>
                            <select name="cor" id="cor" required>
                                <?php foreach ($cores_disponiveis as $cor): ?>
                                    <option value="<?php echo htmlspecialchars($cor); ?>"><?php echo htmlspecialchars($cor); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tamanho">Tamanho:</label>
                            <select name="tamanho" id="tamanho" required>
                                <?php foreach ($tamanhos_disponiveis as $tamanho): ?>
                                    <option value="<?php echo htmlspecialchars($tamanho); ?>"><?php echo htmlspecialchars($tamanho); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantidade">Quantidade:</label>
                            <input type="number" id="quantidade" name="quantidade" value="1" min="1" max="10">
                        </div>

                        <button type="submit" class="add-to-cart-btn">Adicionar ao Carrinho</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- SIDE CART -->
    <div class="side-cart" id="side-cart">
        <div class="cart__header">
            <h3 class="cart__title">Meu Carrinho</h3>
            <i class='bx bx-x cart__close' id="cart-close"></i>
        </div>
        <div class="cart__body">
            <?php if (empty($cart_items)): ?>
                <p>Seu carrinho está vazio.</p>
            <?php else: 
                $subtotal = 0;
                foreach ($cart_items as $item_id => $item): 
                $subtotal += $item['preco'] * $item['quantidade'];
            ?>
                <div class="cart-item">
                    <img src="../imagens/<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" class="cart-item__image">
                    <div class="cart-item__info">
                        <p class="cart-item__name"><?php echo htmlspecialchars($item['nome']); ?></p>
                        <small class="cart-item__details"><?php echo htmlspecialchars($item['cor']); ?> / <?php echo htmlspecialchars($item['tamanho']); ?></small>
                        <p class="cart-item__price">Qtd: <?php echo $item['quantidade']; ?> - R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
                        <a href="cart_handler.php?action=remove&id=<?php echo $item_id; ?>" class="cart-item__remove">Remover</a>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
        <?php if (!empty($cart_items)): ?>
        <div class="cart__footer">
            <div class="cart__subtotal">
                <span>Subtotal:</span>
                <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
            </div>
            <a href="checkout.php" class="details-button" style="width: 100%; text-align: center;">Finalizar Compra</a>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Função genérica para toggles
        const setupToggle = (toggleId, elementId, className) => {
            const toggle = document.getElementById(toggleId);
            const element = document.getElementById(elementId);
            if (toggle && element) {
                toggle.addEventListener('click', () => element.classList.toggle(className));
            }
        }
        // Menu da sidebar para mobile
        setupToggle('header-toggle', 'sidebar', 'active');
        // Carrinho lateral
        setupToggle('cart-icon', 'side-cart', 'active');
        setupToggle('cart-close', 'side-cart', 'active');
    </script>

</body>
</html>
<?php
mysqli_close($conn);
?>
