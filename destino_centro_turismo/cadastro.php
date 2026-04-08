<?php
require_once 'config.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha'];

    if (!empty($nome) && !empty($email) && !empty($senha)) {
        // Verifica se o e-mail já existe
        $check = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            // Criptografia de segurança
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            try {
                $sql = "INSERT INTO clientes (nome, email, telefone, senha, nivel) VALUES (?, ?, ?, ?, 'cliente')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $email, $telefone, $senhaHash]);

                header("Location: login.php?sucesso=1");
                exit;
            } catch (PDOException $e) {
                $erro = "Erro ao conectar com o servidor. Tente novamente.";
            }
        }
    } else {
        $erro = "Por favor, preencha os campos obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Destino Centro Turismo</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #003358;
            --accent: #388087;
            --white: #ffffff;
            --error: #e74c3c;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(0, 51, 88, 0.7), rgba(0, 51, 88, 0.7)),
                url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1353&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 35px;
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        .register-card img {
            width: 120px;
            margin-bottom: 15px;
        }

        .register-card h2 {
            color: var(--primary);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .register-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            text-align: left;
        }

        .input-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .full-width {
            grid-column: span 2;
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 5px;
            margin-left: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
            font-size: 0.95rem;
        }

        .input-group input:focus {
            border-color: var(--accent);
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }

        .error-msg {
            background: #fdeaea;
            color: var(--error);
            padding: 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .footer-links {
            margin-top: 20px;
            font-size: 0.85rem;
            color: #777;
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        /* Responsividade para celulares */
        @media (max-width: 480px) {
            .input-row {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>

<body>

    <div class="register-card">
        <img src="img/logo.png" alt="Destino Centro">
        <h2>Crie sua conta</h2>
        <p>Junte-se a nós e descubra destinos incríveis.</p>

        <?php if ($erro): ?>
            <div class="error-msg"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-row">
                <div class="input-group full-width">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" placeholder="Ex: Maria Oliveira" required>
                </div>

                <div class="input-group">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="maria@email.com" required>
                </div>

                <div class="input-group">
                    <label>Telefone</label>
                    <input type="text" name="telefone" placeholder="(00) 00000-0000">
                </div>

                <div class="input-group full-width">
                    <label>Escolha uma Senha</label>
                    <input type="password" name="senha" placeholder="Mínimo 6 caracteres" required>
                </div>
            </div>

            <button type="submit" class="btn-register">CRIAR MINHA CONTA</button>
        </form>

        <div class="footer-links">
            Já possui cadastro? <a href="login.php">Fazer Login</a>
        </div>
    </div>

</body>

</html>