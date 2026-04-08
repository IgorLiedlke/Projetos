<?php
include 'config.php';
session_start();

// Segurança: Só adm pode deletar
if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->execute([$id]);
}

// Volta para o painel
header("Location: admin.php");
exit;