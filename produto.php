<?php
// produto.php — Mostra o detalhe de um produto lido da base de dados

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'scripts/db.php';

// Lê o ID do produto enviado via GET
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: produtos.php');
    exit;
}

$db = ligar_bd();

$stmt = $db->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$resultado = $stmt->execute();
$produto = $resultado->fetchArray(SQLITE3_ASSOC);

if (!$produto) {
    echo "<p>Produto não encontrado.</p>";
    echo "<a href='produtos.php'>Voltar ao catálogo</a>";
    unset($db);
    exit;
}

unset($db);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto['nome']; ?> — ShopOnline</title>
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
        <p><a href="produtos.php">← Voltar ao catálogo</a></p>

        <div class="detalhe-produto">

            <div class="produto-imagem">
                <img src="images/produto<?php echo $produto['id']; ?>.jpg"
                     alt="<?php echo $produto['nome']; ?>">
            </div>

            <div class="produto-info">
                <h2><?php echo $produto['nome']; ?></h2>
                <p class="categoria">Categoria: <?php echo $produto['categoria']; ?></p>
                <p class="preco-grande">
                    <?php echo number_format($produto['preco'], 2, ',', '.'); ?> €
                </p>

                <?php if ($produto['stock'] > 0): ?>
                    <p class="stock">Em stock (<?php echo $produto['stock']; ?> unidades)</p>
                <?php else: ?>
                    <p style="color:red;">Esgotado</p>
                <?php endif; ?>

                <h3>Descrição</h3>
                <p><?php echo $produto['descricao']; ?></p>

                <div class="acao-compra">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" id="quantidade" value="1" min="1"
                           max="<?php echo $produto['stock']; ?>">
                    <button class="botao">Adicionar ao Carrinho</button>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <p>&copy; 2026 ShopOnline.</p>
    </footer>

</body>
</html>
