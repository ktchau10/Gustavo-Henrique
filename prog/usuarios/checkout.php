<?php
session_start();

// Se o carrinho estiver vazio, redireciona para a página principal
if (empty($_SESSION['cart'])) {
    header('Location: main.php');
    exit;
}

require_once '../config.php';

// --- Lógica para buscar categorias para a barra lateral ---
$sql_categorias = "SELECT DISTINCT categoria FROM produtos ORDER BY categoria ASC";
$result_categorias = mysqli_query($conn, $sql_categorias);

$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <header class="header">
        <a href="main.php" class="header__logo">UrbanLife</a>
        <div class="header__cart-icon" id="cart-icon">
            <i class='bx bx-shopping-bag'></i>
            <span class="cart-count"><?php echo count($_SESSION['cart'] ?? []); ?></span>
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
                                <?php echo htmlspecialchars(ucfirst(strtolower($cat_row['categoria']))); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </aside>

        <main class="main-content">
            <div class="checkout-container">
                <div class="checkout-form">
                    <h2>Informações de Entrega</h2>
                    <form action="cart_handler.php" method="POST">
                        <input type="hidden" name="action" value="place_order">
                        <div class="form-group">
                            <label for="nome">Nome Completo</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required list="email-suggestions">
                            <datalist id="email-suggestions"></datalist>
                        </div>
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" id="endereco" name="endereco" required>
                        </div>
                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" required>
                        </div>
                        <button type="submit" class="add-to-cart-btn">Finalizar Pedido</button>
                    </form>
                </div>
                <div class="order-summary">
                    <h2>Resumo do Pedido</h2>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="summary-item">
                            <img src="../imagens/<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars(ucfirst(strtolower($item['nome']))); ?>">
                            <div class="summary-item-info">
                                <p><?php echo htmlspecialchars(ucfirst(strtolower($item['nome']))); ?></p>
                                <small><?php echo htmlspecialchars(ucfirst(strtolower($item['cor']))); ?> / <?php echo htmlspecialchars(ucfirst(strtolower($item['tamanho']))); ?></small>
                                <p>Qtd: <?php echo $item['quantidade']; ?></p>
                            </div>
                            <p class="summary-item-price">R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
                        </div>
                    <?php endforeach; ?>
                    <div class="summary-total">
                        <strong>Total:</strong>
                        <strong>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></strong>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('email').addEventListener('input', function() {
            const emailInput = this.value;
            const suggestions = document.getElementById('email-suggestions');
            suggestions.innerHTML = '';
            if (emailInput.indexOf('@') === -1) {
                const domains = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'icloud.com'];
                domains.forEach(function(domain) {
                    const option = document.createElement('option');
                    option.value = emailInput + '@' + domain;
                    suggestions.appendChild(option);
                });
            }
        });
    </script>
</body>
</html>