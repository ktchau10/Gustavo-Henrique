<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- FUNÇÕES DO CARRINHO ---

function addToCart($produto_id, $quantidade, $cor, $tamanho) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT nome, preco, imagem_url FROM produtos WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $produto_id);
    mysqli_stmt_execute($stmt);
    $produto = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($produto) {
        $cart_item_id = $produto_id . '-' . md5($cor . $tamanho);
        if (isset($_SESSION['cart'][$cart_item_id])) {
            $_SESSION['cart'][$cart_item_id]['quantidade'] += $quantidade;
        } else {
            $_SESSION['cart'][$cart_item_id] = [
                'id' => $produto_id,
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'imagem' => $produto['imagem_url'],
                'cor' => $cor,
                'tamanho' => $tamanho,
                'quantidade' => $quantidade
            ];
        }
    }
}

function removeFromCart($cart_item_id) {
    unset($_SESSION['cart'][$cart_item_id]);
}

function placeOrder($customer_data) {
    global $conn;
    if (empty($_SESSION['cart'])) {
        return false;
    }

    // Inicia a transação
    mysqli_begin_transaction($conn);

    try {
        // 1. Calcula o total e insere na tabela `pedidos`
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }

        $sql_pedido = "INSERT INTO pedidos (cliente_nome, cliente_email, cliente_endereco, valor_total) VALUES (?, ?, ?, ?)";
        $stmt_pedido = mysqli_prepare($conn, $sql_pedido);
        mysqli_stmt_bind_param($stmt_pedido, "sssd", $customer_data['nome'], $customer_data['email'], $customer_data['endereco'], $total);
        mysqli_stmt_execute($stmt_pedido);

        // 2. Pega o ID do pedido que acabamos de criar
        $pedido_id = mysqli_insert_id($conn);

        // 3. Insere cada item do carrinho na tabela `pedido_itens`
        $sql_item = "INSERT INTO pedido_itens (pedido_id, produto_id, cor, tamanho, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_item = mysqli_prepare($conn, $sql_item);

        foreach ($_SESSION['cart'] as $item) {
            mysqli_stmt_bind_param($stmt_item, "iissid", $pedido_id, $item['id'], $item['cor'], $item['tamanho'], $item['quantidade'], $item['preco']);
            mysqli_stmt_execute($stmt_item);
        }

        // Se tudo deu certo, comita a transação
        mysqli_commit($conn);

        // 4. Limpa o carrinho e retorna sucesso
        $_SESSION['cart'] = [];
        return true;

    } catch (Exception $e) {
        // Se algo deu errado, reverte a transação
        mysqli_rollback($conn);
        // Opcional: logar o erro $e->getMessage();
        return false;
    }
}

// --- PROCESSAMENTO DAS AÇÕES ---
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        if (isset($_POST['produto_id'])) {
            addToCart((int)$_POST['produto_id'], (int)$_POST['quantidade'], $_POST['cor'], $_POST['tamanho']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        break;

    case 'remove':
        if (isset($_GET['id'])) {
            removeFromCart($_GET['id']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        break;

    case 'place_order':
        if (!empty($_POST)) {
            $customer_data = [
                'nome' => $_POST['nome'] ?? 'Nome não informado',
                'email' => $_POST['email'] ?? 'Email não informado',
                'endereco' => ($_POST['endereco'] ?? '') . ', ' . ($_POST['cidade'] ?? '')
            ];
            if (placeOrder($customer_data)) {
                header('Location: thank_you.php');
            } else {
                // Opcional: redirecionar para uma página de erro
                die("Ocorreu um erro ao processar seu pedido. Tente novamente.");
            }
        } else {
             header('Location: checkout.php');
        }
        break;

    default:
        header('Location: main.php');
        break;
}
exit;
