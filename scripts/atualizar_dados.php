<?php
// atualizar_dados.php — Atualiza os dados do utilizador na BD

session_start();
require_once 'db.php';

if (!isset($_SESSION['utilizador_id'])) {
    header('Location: ../cliente.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($nome) || empty($email)) {
        echo "<p style='color:red'>Preenche todos os campos.</p>";
        echo "<a href='../cliente.php'>Voltar</a>";
        exit;
    }

    $db = ligar_bd();
    $stmt = $db->prepare("UPDATE utilizadores SET nome = :nome, email = :email
                          WHERE id = :id");
    $stmt->bindValue(':nome',  $nome,  SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':id',    $_SESSION['utilizador_id'], SQLITE3_INTEGER);
    $stmt->execute();

    $_SESSION['utilizador_nome']  = $nome;
    $_SESSION['utilizador_email'] = $email;

    unset($db);
    header('Location: ../cliente.php');
    exit;
} else {
    header('Location: ../cliente.php');
    exit;
}
?>
