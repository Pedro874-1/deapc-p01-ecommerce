<?php
// checkout.php — Recebe os dados do carrinho e cria a encomenda na BD

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome     = trim($_POST['nome'] ?? '');
    $morada   = trim($_POST['morada'] ?? '');
    $cidade   = trim($_POST['cidade'] ?? '');
    $cp       = trim($_POST['cp'] ?? '');

    if (empty($nome) || empty($morada) || empty($cidade) || empty($cp)) {
        echo "<p style='color:red'>Preenche todos os campos da morada.</p>";
        echo "<a href='../carrinho.html'>Voltar</a>";
        exit;
    }

    $morada_completa = $nome . ', ' . $morada . ', ' . $cp . ' ' . $cidade;
    $data  = date('Y-m-d H:i:s');
    $total = 344.99; // Em produção viria do carrinho em sessão
    $uid   = $_SESSION['utilizador_id'] ?? null;

    $db = ligar_bd();

    $stmt = $db->prepare("INSERT INTO encomendas (utilizador_id, data, total, estado, morada)
                          VALUES (:uid, :data, :total, 'Pendente', :morada)");
    $stmt->bindValue(':uid',    $uid,             SQLITE3_INTEGER);
    $stmt->bindValue(':data',   $data,            SQLITE3_TEXT);
    $stmt->bindValue(':total',  $total,           SQLITE3_FLOAT);
    $stmt->bindValue(':morada', $morada_completa, SQLITE3_TEXT);
    $stmt->execute();

    unset($db);

    echo "<!DOCTYPE html><html lang='pt'><head>
          <meta charset='UTF-8'>
          <link rel='stylesheet' href='styles/estilos.css'>
          </head><body>
          <header><h1>ShopOnline</h1></header>
          <main>
          <h2>Encomenda realizada com sucesso!</h2>
          <p>Obrigado pela tua compra, <strong>" . $nome . "</strong>.</p>
          <p>Entrega prevista para: <strong>" . $morada_completa . "</strong></p>
          <p>Receberás um email de confirmação em breve.</p>
          <a href='index.html' class='botao'>Voltar à loja</a>
          </main></body></html>";

} else {
    header('Location: ../carrinho.html');
    exit;
}
?>
