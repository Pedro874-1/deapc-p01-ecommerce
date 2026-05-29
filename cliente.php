<?php
// cliente.php — Área do cliente após login
// Mostra histórico de encomendas e dados pessoais

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'scripts/db.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A minha Conta — ShopOnline</title>
    <link rel="stylesheet" href="styles/estilos.css">
</head>
<body>

    <header>
        <h1>ShopOnline</h1>
        <nav>
            <a href="index.html">Início</a>
            <a href="produtos.php">Catálogo</a>
            <?php if (isset($_SESSION['utilizador_id'])): ?>
                <a href="scripts/logout.php">Logout</a>
            <?php else: ?>
                <a href="cliente.html">Login</a>
            <?php endif; ?>
            <a href="carrinho.html">Carrinho</a>
        </nav>
    </header>

    <main>
        <h2>A minha Conta</h2>

        <?php if (!isset($_SESSION['utilizador_id'])): ?>

            <!-- Utilizador não está autenticado -->
            <div class="pagina-conta">
                <section class="caixa-formulario">
                    <h3>Iniciar Sessão</h3>
                    <form action="scripts/login.php" method="post" class="formulario">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email"
                               placeholder="o-seu-email@exemplo.com" required>

                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password"
                               placeholder="A sua password" required>

                        <button type="submit" class="botao">Entrar</button>
                        <p>Não tem conta? <a href="cliente.html">Registe-se aqui</a></p>
                    </form>
                </section>
            </div>

        <?php else: ?>

            <!-- Utilizador autenticado -->
            <?php
            $db = ligar_bd();
            $uid = $_SESSION['utilizador_id'];

            // Lê dados do utilizador (inclui ultimo_acesso)
            $stmt = $db->prepare("SELECT * FROM utilizadores WHERE id = :id");
            $stmt->bindValue(':id', $uid, SQLITE3_INTEGER);
            $res = $stmt->execute();
            $user = $res->fetchArray(SQLITE3_ASSOC);

            // Lê encomendas do utilizador
            $stmt2 = $db->prepare("SELECT * FROM encomendas
                                   WHERE utilizador_id = :id
                                   ORDER BY data DESC");
            $stmt2->bindValue(':id', $uid, SQLITE3_INTEGER);
            $res2 = $stmt2->execute();
            ?>

            <p>Bem-vindo, <strong><?php echo $user['nome']; ?></strong>!</p>
            <p>Último acesso: <?php echo $user['ultimo_acesso'] ?? 'Primeiro acesso'; ?></p>

            <div class="pagina-conta">

                <section class="caixa-formulario">
                    <h3>Histórico de Encomendas</h3>

                    <table class="tabela-encomendas">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Data</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $tem_encomendas = false;
                        while ($enc = $res2->fetchArray(SQLITE3_ASSOC)):
                            $tem_encomendas = true;
                        ?>
                            <tr>
                                <td>#<?php echo $enc['id']; ?></td>
                                <td><?php echo $enc['data']; ?></td>
                                <td><?php echo number_format($enc['total'], 2, ',', '.'); ?> €</td>
                                <td><?php echo $enc['estado']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (!$tem_encomendas): ?>
                            <tr><td colspan="4">Ainda não tens encomendas.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </section>

                <section class="caixa-formulario">
                    <h3>Os meus Dados</h3>
                    <form action="scripts/atualizar_dados.php" method="post" class="formulario">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome"
                               value="<?php echo $user['nome']; ?>" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email"
                               value="<?php echo $user['email']; ?>" required>

                        <button type="submit" class="botao">Guardar alterações</button>
                    </form>
                </section>

            </div>

            <?php unset($db); ?>

        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2026 ShopOnline.</p>
    </footer>

</body>
</html>
