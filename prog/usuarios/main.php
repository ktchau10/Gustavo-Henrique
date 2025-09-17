<?php
session_start();
require_once '../config.php';

// --- Lógica para buscar produtos com filtro de categoria ---
$sql_produtos = "SELECT id, nome, preco, categoria, imagem_url FROM produtos";
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria_filtro = $_GET['categoria'];
    $sql_produtos .= " WHERE categoria = ?";
    $stmt = mysqli_prepare($conn, $sql_produtos);
    mysqli_stmt_bind_param($stmt, "s", $categoria_filtro);
    mysqli_stmt_execute($stmt);
    $result_produtos = mysqli_stmt_get_result($stmt);
} else {
    $result_produtos = mysqli_query($conn, $sql_produtos);
}

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
    <title>Nossa Loja</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="header__toggle" id="header-toggle">
            <i class='bx bx-menu'></i>
        </div>
        <a href="main.php" class="header__logo">Nossa<span>Loja</span></a>
        <div class="header__nav">
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
            <section class="product-gallery">
                <?php if ($result_produtos && mysqli_num_rows($result_produtos) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result_produtos)): ?>
                        <div class="product-card">
                            <div class="product-card__image-box">
                                <img src="../imagens/<?php echo htmlspecialchars($row['imagem_url']); ?>" alt="<?php echo htmlspecialchars($row['nome']); ?>">
                            </div>
                            <div class="product-card__info">
                                <h2 class="product-card__name"><?php echo htmlspecialchars($row['nome']); ?></h2>
                                <p class="product-card__price">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
                                <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn--primary">Ver Detalhes</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Nenhum produto encontrado.</p>
                <?php endif; ?>
            </section>
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
                <p class="text-center">Seu carrinho está vazio.</p>
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
            <a href="checkout.php" class="btn btn--primary btn-block">Finalizar Compra</a>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
        });
    </script>

</body>
</html>
<?php
mysqli_close($conn);
?>