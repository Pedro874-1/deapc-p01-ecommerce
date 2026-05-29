<?php
// login.php — Valida o login do utilizador e inicia sessão

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo "<p style='color:red'>Erro: Preenche o email e a password.</p>";
        echo "<a href='../cliente.html'>Voltar</a>";
        exit;
    }

    $db = ligar_bd();

    // Procura o utilizador pelo email e password
    $stmt = $db->prepare("SELECT * FROM utilizadores
                          WHERE email = :email AND password = :password");
    $stmt->bindValue(':email',    $email,    SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $resultado = $stmt->execute();
    $utilizador = $resultado->fetchArray(SQLITE3_ASSOC);

    if ($utilizador) {
        // Login bem sucedido — guarda dados na sessão
        $_SESSION['utilizador_id']   = $utilizador['id'];
        $_SESSION['utilizador_nome'] = $utilizador['nome'];
        $_SESSION['utilizador_email']= $utilizador['email'];

        // Atualiza o registo do último acesso
        $agora = date('Y-m-d H:i:s');
        $stmt2 = $db->prepare("UPDATE utilizadores SET ultimo_acesso = :data
                               WHERE id = :id");
        $stmt2->bindValue(':data', $agora,             SQLITE3_TEXT);
        $stmt2->bindValue(':id',   $utilizador['id'],  SQLITE3_INTEGER);
        $stmt2->execute();

        // Redireciona para a página do cliente
        header('Location: ../cliente.php');
        exit;
    } else {
        echo "<p style='color:red'>Erro: Email ou password incorretos.</p>";
        echo "<a href='../cliente.html'>Voltar</a>";
    }

    unset($db);

} else {
    header('Location: ../cliente.html');
    exit;
}
?>
