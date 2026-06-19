<?php
// registo.php — Regista um novo utilizador na base de dados
// Recebe dados via POST do formulário de registo em cliente.html

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ??'');

    // Verifica se os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($password)) {
        echo "<p style='color:red'>Erro: Todos os campos são obrigatórios.</p>";
        echo "<a href='../cliente.html'>Voltar</a>";
        exit;
    }

    // Liga à base de dados
    $db = ligar_bd();

    // Verifica se o email já existe
    $stmt = $db->prepare("SELECT id FROM utilizadores WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $resultado = $stmt->execute();
    $utilizador = $resultado->fetchArray();

    if ($utilizador) {
        echo "<p style='color:red'>Erro: Este email já está registado.</p>";
        echo "<a href='../cliente.html'>Voltar</a>";
    } else {
        // Insere o novo utilizador
        $stmt = $db->prepare("INSERT INTO utilizadores (nome, email, password)
                              VALUES (:nome, :email, :password)");
        $stmt->bindValue(':nome',     $nome,     SQLITE3_TEXT);
        $stmt->bindValue(':email',    $email,    SQLITE3_TEXT);
        $stmt->bindValue(':password', $password, SQLITE3_TEXT);
        $stmt->execute();

        echo "<p style='color:green'>Conta criada com sucesso!</p>";
        echo "<a href='../cliente.html'>Ir para Login</a>";
    }

    unset($db);

} else {
    // Se alguém aceder diretamente a este ficheiro sem submeter o formulário
    header('Location: ../cliente.html');
    exit;
}
?>
