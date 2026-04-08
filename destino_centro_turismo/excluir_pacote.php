<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // IMPORTANTE: Se houver reservas para este pacote, o banco pode dar erro.
    // Primeiro deletamos as reservas desse pacote (opcional, dependendo da sua regra)
    $stmt1 = $pdo->prepare("DELETE FROM reservas WHERE id_pacote = ?");
    $stmt1->execute([$id]);

    // Agora deleta o pacote
    $stmt2 = $pdo->prepare("DELETE FROM pacotes WHERE id = ?");
    $stmt2->execute([$id]);
}

header("Location: admin.php");
exit;