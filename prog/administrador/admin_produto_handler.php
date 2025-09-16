<?php
require_once 'admin_auth_check.php';
require_once '../config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// --- FUNÇÕES --- //

function handleImageUpload($file_input_name, $current_image_url = null) {
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../imagens/';
        $file_tmp = $_FILES[$file_input_name]['tmp_name'];
        $file_name = basename($_FILES[$file_input_name]['name']);
        $new_file_name = uniqid('', true) . '-' . $file_name;
        $destination = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $destination)) {
            // Se for uma edição e uma imagem nova foi enviada, remove a antiga
            if ($current_image_url) {
                $old_image_path = $upload_dir . $current_image_url;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            return $new_file_name;
        }
    }
    // Retorna a imagem atual se não houver novo upload ou se der erro
    return $current_image_url;
}

// --- PROCESSAMENTO DAS AÇÕES --- //

switch ($action) {
    case 'add':
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = (float)$_POST['preco'];
        if (!empty($_POST['nova_categoria'])) {
            $categoria = $_POST['nova_categoria'];
        } elseif (!empty($_POST['categoria'])) {
            $categoria = $_POST['categoria'];
        } else {
            die("Erro: Você deve selecionar uma categoria existente ou adicionar uma nova.");
        }
        $imagem_url = handleImageUpload('imagem');

        $sql = "INSERT INTO produtos (nome, descricao, preco, categoria, imagem_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdss", $nome, $descricao, $preco, $categoria, $imagem_url);
        mysqli_stmt_execute($stmt);

        header('Location: admin.php?view=produtos&status=added');
        break;

    case 'edit':
        $produto_id = (int)$_POST['produto_id'];
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = (float)$_POST['preco'];
        
        if (!empty($_POST['nova_categoria'])) {
            $categoria = $_POST['nova_categoria'];
        } elseif (!empty($_POST['categoria'])) {
            $categoria = $_POST['categoria'];
        } else {
            die("Erro: Você deve selecionar uma categoria existente ou adicionar uma nova.");
        }

        // Pega a URL da imagem atual para o caso de precisar deletá-la
        $stmt_current = mysqli_prepare($conn, "SELECT imagem_url FROM produtos WHERE id = ?");
        mysqli_stmt_bind_param($stmt_current, "i", $produto_id);
        mysqli_stmt_execute($stmt_current);
        $current_image_url = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_current))['imagem_url'];

        $imagem_url = handleImageUpload('imagem', $current_image_url);

        $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ?, imagem_url = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdssi", $nome, $descricao, $preco, $categoria, $imagem_url, $produto_id);
        mysqli_stmt_execute($stmt);

        header('Location: admin.php?view=produtos&status=updated');
        break;

    case 'add_variation':
        $produto_id = (int)$_POST['produto_id'];
        $cor = $_POST['cor'];
        $tamanho = $_POST['tamanho'];
        $estoque = (int)$_POST['estoque'];

        $sql = "INSERT INTO variacoes (produto_id, cor, tamanho, estoque) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "issi", $produto_id, $cor, $tamanho, $estoque);
        mysqli_stmt_execute($stmt);

        header('Location: admin.php?view=edit_produto&id=' . $produto_id . '&status=variation_added');
        break;

    case 'delete_variation':
        $variacao_id = (int)$_GET['id'];
        $produto_id = (int)$_GET['produto_id']; // Needed for redirect

        $sql = "DELETE FROM variacoes WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $variacao_id);
        mysqli_stmt_execute($stmt);

        header('Location: admin.php?view=edit_produto&id=' . $produto_id . '&status=variation_deleted');
        break;

    case 'edit_variation':
        $variacao_id = (int)$_POST['variacao_id'];
        $produto_id = (int)$_POST['produto_id']; // For redirect
        $cor = $_POST['cor'];
        $tamanho = $_POST['tamanho'];
        $estoque = (int)$_POST['estoque'];

        $sql = "UPDATE variacoes SET cor = ?, tamanho = ?, estoque = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $cor, $tamanho, $estoque, $variacao_id);
        mysqli_stmt_execute($stmt);

        header('Location: admin.php?view=edit_produto&id=' . $produto_id . '&status=variation_updated');
        break;

    case 'delete':
        $produto_id = (int)$_GET['id'];

        // Primeiro, pega o nome do arquivo da imagem para poder deletá-lo
        $stmt_img = mysqli_prepare($conn, "SELECT imagem_url FROM produtos WHERE id = ?");
        mysqli_stmt_bind_param($stmt_img, "i", $produto_id);
        mysqli_stmt_execute($stmt_img);
        $imagem_url = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_img))['imagem_url'];

        // Deleta o produto do banco
        // NOTA: Em um sistema real, seria preciso deletar também as variações e verificar os itens de pedido.
        $stmt_del = mysqli_prepare($conn, "DELETE FROM produtos WHERE id = ?");
        mysqli_stmt_bind_param($stmt_del, "i", $produto_id);
        if (mysqli_stmt_execute($stmt_del)) {
            // Se o produto foi deletado do banco, remove o arquivo da imagem
            if ($imagem_url) {
                $image_path = '../imagens/' . $imagem_url;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }

        header('Location: admin.php?view=produtos&status=deleted');
        break;

    default:
        header('Location: admin.php?view=produtos');
        break;
}
exit;
