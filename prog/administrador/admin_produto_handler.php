<?php
require_once 'admin_auth_check.php';
require_once '../config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// --- FUNÇÕES --- //

function handleImageUpload($file_input_name, $current_image_url = null) {
    // Se nenhum arquivo foi enviado ou se foi enviado mas sem intenção de upload, retorna a imagem atual.
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] == UPLOAD_ERR_NO_FILE) {
        return $current_image_url;
    }

    // Verifica outros erros de upload
    if ($_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => "O arquivo excede a diretiva upload_max_filesize no php.ini.",
            UPLOAD_ERR_FORM_SIZE  => "O arquivo excede a diretiva MAX_FILE_SIZE especificada no formulário HTML.",
            UPLOAD_ERR_PARTIAL    => "O upload do arquivo foi feito parcialmente.",
            UPLOAD_ERR_NO_TMP_DIR => "Faltando uma pasta temporária.",
            UPLOAD_ERR_CANT_WRITE => "Falha ao escrever o arquivo no disco.",
            UPLOAD_ERR_EXTENSION  => "Uma extensão do PHP interrompeu o upload do arquivo.",
        ];
        $error_code = $_FILES[$file_input_name]['error'];
        // Termina a execução e mostra um erro claro.
        die("Erro no upload do arquivo: " . ($upload_errors[$error_code] ?? "Erro desconhecido. Código: " . $error_code));
    }

    $upload_dir = '../imagens/';

    // Garante que o diretório de upload exista e seja gravável.
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            die("Erro: Falha ao criar o diretório de uploads.");
        }
    }

    $file_tmp = $_FILES[$file_input_name]['tmp_name'];
    $file_name = basename($_FILES[$file_input_name]['name']);
    // Sanitiza o nome do arquivo para remover caracteres problemáticos.
    $safe_file_name = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $file_name);
    $new_file_name = uniqid('', true) . '-' . $safe_file_name;
    $destination = $upload_dir . $new_file_name;

    // Tenta mover o arquivo para o destino final.
    if (move_uploaded_file($file_tmp, $destination)) {
        // Se for uma edição e uma imagem antiga existir, remove-a.
        if ($current_image_url) {
            // basename() para segurança, garantindo que não haja travessia de diretório.
            $old_image_path = $upload_dir . basename($current_image_url);
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        return $new_file_name; // Retorna o nome do novo arquivo.
    } else {
        // Se move_uploaded_file falhar, informa o erro.
        die("Erro: Falha ao mover o arquivo de upload. Verifique as permissões de escrita no diretório '{$upload_dir}'.");
    }

    // Se algo der errado e não for tratado acima, retorna a imagem original.
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

    case 'update_variations':
        $produto_id = (int)$_POST['produto_id'];
        $variacoes = $_POST['variacoes'] ?? [];
        $has_error = false;

        foreach ($variacoes as $id => $data) {
            $id = (int)$id;
            $cor = $data['cor'];
            $tamanho = $data['tamanho'];
            $estoque = (int)$data['estoque'];

            $sql = "UPDATE variacoes SET cor = ?, tamanho = ?, estoque = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssii", $cor, $tamanho, $estoque, $id);
            
            if (!mysqli_stmt_execute($stmt)) {
                $has_error = true;
                die("Erro ao atualizar a variação ID $id: " . mysqli_stmt_error($stmt));
            }
        }

        if (!$has_error) {
            header('Location: admin.php?view=edit_produto&id=' . $produto_id . '&status=variations_updated');
        }
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
