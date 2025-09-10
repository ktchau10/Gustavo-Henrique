# Loja Virtual de Roupas em PHP

Este é um projeto de uma simples loja virtual (e-commerce) de roupas, desenvolvido como parte de um estudo em programação web. O sistema foi construído utilizando PHP puro, HTML, CSS e MySQL, e inclui funcionalidades tanto para clientes quanto para administradores.

## Funcionalidades

### Área do Cliente
- **Catálogo de Produtos:** Visualização dos produtos em uma galeria.
- **Filtro por Categoria:** Navegação simplificada através de uma barra lateral de categorias.
- **Página de Detalhes:** Visualização detalhada de cada produto com opções de cor e tamanho.
- **Carrinho de Compras:** Um carrinho lateral funcional que permite adicionar, remover e visualizar itens.
- **Checkout:** Uma página de finalização de compra para inserção de dados do cliente.

### Painel de Administração
- **Login Seguro:** Uma página de login para acesso ao painel.
- **Dashboard com Estatísticas:** Visão geral das vendas, número de pedidos, produtos e clientes.
- **Visualização de Pedidos:** Lista de todos os pedidos realizados, com uma página de detalhes para cada um.
- **Visualização de Clientes:** Lista de clientes que já realizaram compras.

## Tecnologias Utilizadas

- **Backend:** PHP 8
- **Frontend:** HTML5, CSS3, JavaScript (para interatividade)
- **Banco de Dados:** MySQL
- **Servidor Local:** XAMPP

## Configuração do Ambiente Local

Siga os passos abaixo para executar o projeto em sua máquina local.

### 1. Pré-requisitos

- Ter o **XAMPP** instalado (ou qualquer outro ambiente que forneça Apache, MySQL e PHP).

### 2. Instalação

1.  **Clone ou baixe** este repositório para a pasta `htdocs` do seu XAMPP.
    - O caminho deve ser algo como: `C:\xampp\htdocs\prog`

2.  **Inicie o XAMPP** e ative os módulos `Apache` e `MySQL`.

3.  **Crie o Banco de Dados:**
    - Abra o **phpMyAdmin** (geralmente em `http://localhost/phpmyadmin`).
    - Crie um novo banco de dados com o nome `loja`.
    - Selecione o banco de dados `loja` que você acabou de criar.
    - Vá para a aba **"Importar"**.
    - Clique em "Escolher arquivo" e selecione o arquivo `database_setup.sql` que está na raiz deste projeto.
    - Clique em **"Executar"** no final da página.

4.  **Ajuste a Conexão (se necessário):**
    - Abra o arquivo `config.php`.
    - Verifique se as credenciais do banco de dados (`$db_user`, `$db_pass`) correspondem às do seu ambiente XAMPP. O padrão é `root` e senha vazia.

### 3. Executando o Projeto

- Abra seu navegador e acesse `http://localhost/prog/inicio.php`.

## Credenciais de Acesso

Para acessar o painel de administração, utilize as seguintes credenciais:

- **URL:** `http://localhost/prog/admin_login.php`
- **Usuário:** `admin`
- **Senha:** `admin123`

**Nota:** A senha do administrador está em texto puro (`admin123`) para fins de simplicidade neste projeto de estudo. Em um ambiente de produção, **nunca** armazene senhas em texto puro.
