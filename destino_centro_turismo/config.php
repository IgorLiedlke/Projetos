<?php
// Configurações do Banco de Dados
$host = 'localhost';
$db   = 'destino_certo';
$user = 'root';
$pass = ''; // No XAMPP o padrão é vazio

try {
    // Conexão usando PDO com suporte a caracteres brasileiros (utf8)
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Configura o PDO para lançar exceções em caso de erro de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Caso a conexão falhe, exibe o erro e para a execução
    die("Erro crítico na conexão: " . $e->getMessage());
}
?>