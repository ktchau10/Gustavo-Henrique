# Loja Virtual em PHP

Este é um projeto de uma loja virtual simples desenvolvido em PHP, como parte de um trabalho acadêmico.

## Funcionalidades

-   **Login Unificado:** Uma única tela de login para clientes e administradores.
-   **Visualização de Produtos:** Os usuários podem navegar pelos produtos na página principal.
-   **Carrinho de Compras:** Funcionalidade de carrinho de compras para adicionar e remover produtos.
-   **Painel de Administração:** Uma área restrita para administrar produtos, visualizar pedidos e clientes.

## Estrutura de Arquivos

-   `/login`: Contém os arquivos para o sistema de login (`index.php`, `login_handler.php`, `logout.php`).
-   `/administrador`: Contém as páginas e a lógica do painel de administração.
-   `/usuarios`: Contém as páginas da loja para os clientes.
-   `/documentos`: Contém os arquivos SQL.
-   `/imagens`: Contém as imagens dos produtos.

## Tecnologias Utilizadas

-   PHP
-   MySQL
-   HTML
-   CSS

## Como Configurar e Executar o Projeto

### Pré-requisitos

-   Um servidor web com suporte a PHP e MySQL. O **XAMPP** é uma opção recomendada e fácil de instalar.

### Passos para Instalação

1.  **Clone ou baixe o repositório:**
    Coloque a pasta do projeto (`prog`) dentro do diretório `htdocs` do seu XAMPP. O caminho final deverá ser `C:/xampp/htdocs/prog` (ou o equivalente no seu sistema operacional).

2.  **Crie e Importe o Banco de Dados:**
    -   Abra o **phpMyAdmin** (acessível por `http://localhost/phpmyadmin`).
    -   Crie um novo banco de dados com o nome `loja`.
    -   Selecione o banco de dados `loja` que você acabou de criar.
    -   Vá até a aba "Importar", clique em "Escolher arquivo" e selecione o arquivo `loja.sql` que está na pasta `documentos` do projeto.
    -   Clique em "Executar" para importar a estrutura das tabelas e os dados iniciais.
    -   Em seguida, importe também o arquivo `database_update.sql` (também na pasta `documentos`) para adicionar a tabela de clientes.

3.  **Verifique a Conexão com o Banco de Dados:**
    -   Abra o arquivo `config.php` na raiz do projeto.
    -   Verifique se as credenciais de acesso ao banco de dados (`$servername`, `$username`, `$password`, `$dbname`) estão corretas para o seu ambiente. A configuração padrão do XAMPP geralmente é:
        -   `$servername = "localhost"`
        -   `$username = "root"`
        -   `$password = ""`
        -   `$dbname = "loja"`

### Acessando a Aplicação

-   **Página de Login:**
    Abra o seu navegador e acesse: `http://localhost/prog/login/`

-   **Credenciais de Teste:**
    -   **Administrador:**
        -   **Usuário:** `admin`
        -   **Senha:** `admin123`
    -   **Cliente:**
        -   **Email:** `cliente@example.com`
        -   **Senha:** `cliente123`

Após o login, você será redirecionado para a área do cliente ou para o painel de administração, dependendo das credenciais utilizadas.
