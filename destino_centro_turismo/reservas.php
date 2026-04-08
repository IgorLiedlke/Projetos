<?php
session_start();
require_once 'config.php';

// Se não estiver logado, manda para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Minhas Reservas | Destino Centro</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="style.css">
    <style>
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            color: white;
        }

        .status-Pendente {
            background-color: #f39c12;
        }

        .status-Confirmada {
            background-color: #27ae60;
        }

        .status-Cancelada {
            background-color: #e74c3c;
        }

        .reserva-item {
            background: white;
            margin-bottom: 15px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reserva-info h3 {
            color: var(--primary-color);
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <header>
        <img src="img/logo.png" alt="Destino Centro" style="height: 50px;">
        <nav>
            <a href="index.php">Ver Pacotes</a>
            <a href="logout.php" style="color: #e74c3c;">Sair</a>
        </nav>
    </header>

    <div class="container">
        <h2 style="margin-bottom: 30px; color: var(--primary-color);">Olá, <?php echo $_SESSION['user_nome']; ?>! 
        </h2>
        <p style="margin-bottom: 20px;">Abaixo estão suas solicitações de viagem:</p>

        <?php
        // Busca reservas do cliente logado cruzando com a tabela de pacotes
        $sql = "SELECT r.*, p.destino, p.imagem_url 
                FROM reservas r 
                JOIN pacotes p ON r.id_pacote = p.id 
                WHERE r.id_cliente = ? 
                ORDER BY r.data_reserva DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $minhas_reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($minhas_reservas) > 0):
            foreach ($minhas_reservas as $res):
                ?>
                <div class="reserva-item">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <img src="<?php echo $res['imagem_url']; ?>"
                            style="width: 80px; height: 60px; object-fit: cover; border-radius: 5px;">
                        <div class="reserva-info">
                            <h3><?php echo $res['destino']; ?></h3>
                            <small>Solicitado em: <?php echo date('d/m/Y', strtotime($res['data_reserva'])); ?></small>
                        </div>
                    </div>

                    <div>
                        <span class="status-badge status-<?php echo $res['status']; ?>">
                            <?php echo $res['status']; ?>
                        </span>
                    </div>
                </div>
            <?php
            endforeach;
        else:
            ?>
            <div style="text-align: center; padding: 50px; background: white; border-radius: 15px;">
                <p>Você ainda não tem nenhuma reserva. Que tal escolher seu próximo destino?</p>
                <a href="index.php" class="btn-add" style="margin-top: 20px; text-decoration: none;">Explorar Pacotes</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>