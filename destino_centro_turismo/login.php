<?php
session_start();
require_once 'config.php';

// Se o usuário já estiver logado, redireciona
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['user_nivel'] == 'admin' ? "admin.php" : "index.php"));
    exit;
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (!empty($email) && !empty($senha)) {
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_nivel'] = $user['nivel'];

            header("Location: " . ($user['nivel'] == 'admin' ? "admin.php" : "index.php"));
            exit;
        } else {
            $erro = "E-mail ou senha incorretos!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Destino Centro Turismo</title>
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Imagem de fundo de viagem (pode trocar a URL por uma sua) */
            background: linear-gradient(rgba(0, 51, 88, 0.6), rgba(0, 51, 88, 0.6)),
                url('https://hotelrotadosol.com.br/wp-content/uploads/2017/09/4c4b8972-f59d-4873-835f-d9a9aeff7fb8.c10-1024x640.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .login-card img {
            width: 140px;
            margin-bottom: 20px;
        }

        .login-card h2 {
            color: var(--primary);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .login-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 8px;
            margin-left: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
            font-size: 1rem;
        }

        .input-group input:focus {
            border-color: var(--accent);
            background: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(0, 51, 88, 0.3);
        }

        .btn-login:hover {
            background: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(56, 128, 135, 0.4);
        }

        .error-msg {
            background: #fdeaea;
            color: var(--error);
            padding: 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: 1px solid #fadbd8;
        }

        .footer-links {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #777;
        }

        .footer-links a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <img src="img/logo.png" alt="Destino Centro">
        <h2>Bem-vindo!</h2>
        <p>Faça login para planejar sua próxima aventura.</p>

        <?php if ($erro): ?>
            <div class="error-msg"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['sucesso'])): ?>
            <div style="color: #27ae60; margin-bottom: 15px; font-size: 0.85rem;">
                Conta criada com sucesso! Faça login.
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>E-mail</label>
                <input type="email" name="email" placeholder="seu@email.com" required>
            </div>

            <div class="input-group">
                <label>Senha</label>
                <input type="password" name="senha" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">ACESSAR MINHA CONTA</button>
        </form>

        <div class="footer-links">
            Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a>
        </div>
    </div>

</body>

</html>