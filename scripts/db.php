<?php
// db.php — Ligação à base de dados SQLite3
// Cria a BD e as tabelas se ainda não existirem

function ligar_bd() {
    $db = new SQLite3(__DIR__ . '/../loja.db');

    // Ativa mensagens de erro para debug
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Criação da tabela utilizadores
    $db->exec("CREATE TABLE IF NOT EXISTS utilizadores (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        ultimo_acesso TEXT
    )");

    // Criação da tabela produtos
    $db->exec("CREATE TABLE IF NOT EXISTS produtos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        categoria TEXT,
        preco REAL NOT NULL,
        stock INTEGER DEFAULT 0,
        descricao TEXT
    )");

    // Criação da tabela encomendas
    $db->exec("CREATE TABLE IF NOT EXISTS encomendas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        utilizador_id INTEGER,
        data TEXT,
        total REAL,
        estado TEXT DEFAULT 'Pendente',
        morada TEXT
    )");

    // Criação da tabela itens_encomenda
    $db->exec("CREATE TABLE IF NOT EXISTS itens_encomenda (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        encomenda_id INTEGER,
        produto_id INTEGER,
        quantidade INTEGER,
        preco_unit REAL
    )");

    // Inserir produtos de exemplo se a tabela estiver vazia
    $resultado = $db->query("SELECT COUNT(*) as total FROM produtos");
    $linha = $resultado->fetchArray();
    if ($linha['total'] == 0) {
        $db->exec("INSERT INTO produtos (nome, categoria, preco, stock, descricao)
                   VALUES ('Smartphone XPro', 'Electronica', 299.99, 15,
                   'Smartphone com ecra 6.7 e camara 64MP')");
        $db->exec("INSERT INTO produtos (nome, categoria, preco, stock, descricao)
                   VALUES ('Auriculares Wireless', 'Electronica', 45.00, 42,
                   'Auriculares sem fios com cancelamento de ruido')");
        $db->exec("INSERT INTO produtos (nome, categoria, preco, stock, descricao)
                   VALUES ('Tenis Running', 'Desporto', 59.50, 30,
                   'Tenis ideais para corrida')");
        $db->exec("INSERT INTO produtos (nome, categoria, preco, stock, descricao)
                   VALUES ('Livro HTML e CSS', 'Livros', 18.99, 20,
                   'Guia completo de HTML e CSS para principiantes')");

        // Inserir utilizador admin de exemplo
        $db->exec("INSERT INTO utilizadores (nome, email, password)
                   VALUES ('Administrador', 'admin@shoponline.pt', 'admin123')");
    }

    return $db;
}
?>
