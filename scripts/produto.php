<?php
// produtos.php — Lê os produtos da base de dados e mostra-os em HTML

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
$db = ligar_bd();

// Filtro de categoria (opcional, enviado via GET)
$categoria = trim($_GET['categoria'] ?? '');

if (!empty($categoria)) {
    $stmt = $db->prepare("SELECT * FROM produtos WHERE categoria = :cat");
    $stmt->bindValue(':cat', $categoria, SQLITE3_TEXT);
    $resultado = $stmt->execute();
} else {
    $resultado = $db->query("SELECT * FROM produtos");
}

// Gera o HTML dos cartões de produto
$html_produtos = '';
while ($produto = $resultado->fetchArray(SQLITE3_ASSOC)) {
    $html_produtos .= '
    <div class="cartao">
        <img src="images/produto' . $produto['id'] . '.jpg"
             alt="' . $produto['nome'] . '">
        <h3>' . $produto['nome'] . '</h3>
        <p class="categoria">' . $produto['categoria'] . '</p>
        <p class="preco">' . number_format($produto['preco'], 2, ',', '.') . ' €</p>
        <a href="produto.php?id=' . $produto['id'] . '" class="botao">Ver produto</a>
    </div>';
}

if (empty($html_produtos)) {
    $html_produtos = '<p>Nenhum produto encontrado.</p>';
}

unset($db);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo — ShopOnline</title>
    <link rel="stylesheet" href="styles/estilos.css">
</head>
<body>

    <header>
        <h1>ShopOnline</h1>
        <nav>
            <a href="index.html">Início</a>
            <a href="produtos.php">Catálogo</a>
            <a href="cliente.html">Login</a>
            <a href="carrinho.html">Carrinho</a>
        </nav>
    </header>

    <main>
        <h2>Catálogo de Produtos</h2>

        <div class="pagina-catalogo">

            <aside class="filtros">
                <h3>Filtrar por categoria</h3>
                <form method="get" action="produtos.php">
                    <label for="categoria">Categoria:</label>
                    <select id="categoria" name="categoria">
                        <option value="">Todas</option>
                        <option value="Electronica">Electrónica</option>
                        <option value="Desporto">Desporto</option>
                        <option value="Livros">Livros</option>
                        <option value="Vestuario">Vestuário</option>
                    </select>
                    <button type="submit" class="botao">Aplicar</button>
                </form>
            </aside>

            <section class="lista-produtos">
                <div class="grelha">
                    <?php echo $html_produtos; ?>
                </div>
            </section>

        </div>
    </main>

    <footer>
        <p>&copy; 2026 ShopOnline.</p>
    </footer>

</body>
</html>
