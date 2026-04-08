<?php
session_start();
require_once 'config.php';

// 1. Pega o ID do pacote pela URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_pacote = $_GET['id'];

// 2. Busca os dados do pacote no banco
$stmt = $pdo->prepare("SELECT * FROM pacotes WHERE id = ?");
$stmt->execute([$id_pacote]);
$pacote = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pacote) {
    header("Location: index.php");
    exit;
}

// 3. Lógica da Reserva
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_reserva'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?erro=faca_login");
        exit;
    }

    $id_usuario = $_SESSION['user_id'];

    try {
        $sql = "INSERT INTO reservas (id_cliente, id_pacote, status) VALUES (?, ?, 'Pendente')";
        $insert = $pdo->prepare($sql);
        $insert->execute([$id_usuario, $id_pacote]);

        header("Location: reservas.php?status=sucesso");
        exit;
    } catch (PDOException $e) {
        $erro_reserva = "Erro ao processar reserva.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pacote['destino']; ?> | Destino Centro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary: #003358;
            --accent: #388087;
            --price-green: #27ae60;
            --bg-light: #f4f7f6;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .details-container {
            max-width: 1100px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            padding: 0 20px;
        }

        .package-image-container {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 500px;
        }

        .package-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge-discount-detail {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .info-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-card h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin: 0 0 15px 0;
            line-height: 1.2;
        }

        .destination-tag {
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 10px;
            display: block;
        }

        .price-section {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 15px;
        }

        .old-price {
            text-decoration: line-through;
            color: #888;
            font-size: 1rem;
        }

        .current-price {
            color: var(--price-green);
            font-size: 2.2rem;
            font-weight: 600;
            margin: 0;
        }

        .btn-reserve {
            background: var(--primary);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0, 51, 88, 0.2);
        }

        .btn-reserve:hover {
            background: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(56, 128, 135, 0.3);
        }

        .benefits {
            margin-top: 25px;
            list-style: none;
            padding: 0;
        }

        .benefits li {
            margin-bottom: 10px;
            color: #555;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .benefits li::before {
            content: "✓";
            color: var(--price-green);
            font-weight: bold;
            margin-right: 10px;
        }

        @media (max-width: 900px) {
            .details-container {
                grid-template-columns: 1fr;
            }

            .package-image-container {
                height: 350px;
            }
        }
    </style>
</head>

<body>

    <header>
        <img src="img/logo.png" alt="Logo" style="height: 50px;">
        <nav>
            <a href="index.php">← Voltar para Ofertas</a>
        </nav>
    </header>

    <main class="details-container">
        <div class="package-image-container">
            <?php if ($pacote['desconto'] > 0): ?>
                <div class="badge-discount-detail">-<?php echo $pacote['desconto']; ?>% OFF</div>
            <?php endif; ?>
            <img src="<?php echo $pacote['imagem_url']; ?>" alt="<?php echo $pacote['destino']; ?>">
        </div>

        <div class="info-card">
            <span class="destination-tag">Pacote Especial</span>
            <h1><?php echo $pacote['destino']; ?></h1>

            <p style="color: #666; line-height: 1.6;">
                Prepare as malas! Conheça as belezas naturais e a cultura única de
                <strong><?php echo $pacote['destino']; ?></strong>.
                Uma experiência selecionada pela Destino Centro Turismo para você e sua família.
            </p>

            <div class="price-section">
                <?php
                $valor_original = $pacote['preco'];
                $valor_final = $valor_original - ($valor_original * ($pacote['desconto'] / 100));
                ?>

                <?php if ($pacote['desconto'] > 0): ?>
                    <span class="old-price">De: R$ <?php echo number_format($valor_original, 2, ',', '.'); ?></span>
                <?php endif; ?>

                <p style="margin: 5px 0 0 0; font-size: 0.9rem; color: #555;">Por apenas:</p>
                <h2 class="current-price">R$ <?php echo number_format($valor_final, 2, ',', '.'); ?></h2>
                <small style="color: #888;">*Preço por pessoa em quarto duplo.</small>
            </div>

            <ul class="benefits">
                <li>Suporte 24h via WhatsApp</li>
                <li>Cancelamento grátis até 7 dias antes</li>
                <li>Melhor preço garantido</li>
            </ul>

            <form method="POST" style="margin-top: 30px;">
                <button type="submit" name="confirmar_reserva" class="btn-reserve">
                    SOLICITAR RESERVA AGORA
                </button>
            </form>

            <?php if (isset($erro_reserva)): ?>
                <p style="color: red; margin-top: 15px; font-size: 0.9rem;"><?php echo $erro_reserva; ?></p>
            <?php endif; ?>
        </div>
    </main>

    <footer style="text-align: center; padding: 40px; color: #888;">
        &copy; 2026 Destino Centro Turismo - Todos os direitos reservados.
    </footer>

</body>

</html>