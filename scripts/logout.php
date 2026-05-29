<?php
// logout.php — Termina a sessão do utilizador

session_start();
session_destroy();
header('Location: ../index.html');
exit;
?>
