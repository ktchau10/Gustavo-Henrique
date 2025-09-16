# Loja Virtual em PHP

Este é um projeto de uma loja virtual simples desenvolvido em PHP, como parte de um trabalho acadêmico.

## Funcionalidades

-   **Visualização de Produtos:** Os usuários podem navegar pelos produtos na página principal.
-   **Carrinho de Compras:** Funcionalidade de carrinho de compras para adicionar e remover produtos.
-   **Painel de Administração:** Uma área restrita para administrar produtos, visualizar pedidos e clientes.

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

2.  **Crie o Banco de Dados:**
    -   Abra o **phpMyAdmin** (acessível por `http://localhost/phpmyadmin`).
    -   Crie um novo banco de dados com o nome `loja`.
    -   Selecione o banco de dados `loja` que você acabou de criar.
    -   Vá até a aba "Importar", clique em "Escolher arquivo" e selecione o arquivo `loja.sql` que está na pasta `documentos` do projeto.
    -   Clique em "Executar" para importar a estrutura das tabelas e os dados iniciais.

3.  **Crie um Usuário Administrador:**
    -   Após a importação, ainda no phpMyAdmin, selecione a tabela `administradores`.
    -   Clique na aba "Inserir".
    -   Preencha os campos `usuario` e `senha` com as credenciais que desejar para o acesso ao painel de administração.
    -   Clique em "Executar" para salvar o novo usuário administrador.

4.  **Verifique a Conexão com o Banco de Dados:**
    -   Abra o arquivo `config.php` na raiz do projeto.
    -   Verifique se as credenciais de acesso ao banco de dados (`$servername`, `$username`, `$password`, `$dbname`) estão corretas para o seu ambiente. A configuração padrão do XAMPP geralmente é:
        -   `$servername = "localhost"`
        -   `$username = "root"`
        -   `$password = ""`
        -   `$dbname = "loja"`

### Acessando a Aplicação

-   **Página Principal da Loja:**
    Abra o seu navegador e acesse: `http://localhost/prog/usuarios/main.php`

-   **Painel de Administração:**
    Acesse: `http://localhost/prog/administrador/admin_login.php`
    Utilize o usuário e senha que você cadastrou no passo 3 da instalação.
