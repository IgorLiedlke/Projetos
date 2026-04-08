<?php
include 'config.php';

$email = 'admin@destino.com';
$senha_pura = 'admin123';
$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);
$nome = 'Administrador';

// Tenta atualizar se já existir, senão insere
$check = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
$check->execute([$email]);

if ($check->rowCount() > 0) {
    $sql = "UPDATE clientes SET senha = ?, nivel = 'admin' WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$senha_hash, $email]);
    echo "Senha do administrador atualizada com sucesso!";
} else {
    $sql = "INSERT INTO clientes (nome, email, senha, nivel) VALUES (?, ?, ?, 'admin')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $senha_hash]);
    echo "Novo administrador criado com sucesso!";
}
?>