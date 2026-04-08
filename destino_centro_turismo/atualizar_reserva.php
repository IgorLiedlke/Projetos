<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] == 'admin') {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $stmt = $pdo->prepare("UPDATE reservas SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}

header("Location: admin.php");
exit;