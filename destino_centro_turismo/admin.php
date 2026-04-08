<?php
include 'config.php';
session_start();

// 1. Proteção: Só adm entra
if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. Lógica para SALVAR de verdade no Banco de Dados
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_salvar'])) {
    $destino = $_POST['destino'];
    $preco = $_POST['preco'];
    $desconto = $_POST['desconto'];
    $imagem = $_POST['imagem'];

    try {
        $sql = "INSERT INTO pacotes (destino, preco, desconto, imagem_url) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$destino, $preco, $desconto, $imagem]);

        // Mensagem de sucesso real
        echo "<script>alert('Destino salvo com sucesso no Banco de Dados!'); window.location='admin.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao salvar: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo | Destino Centro</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos específicos para o formulário admin */
        .form-cadastro {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-salvar {
            grid-column: span 2;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-salvar:hover {
            background: var(--primary-color);
        }

        .full-width {
            grid-column: span 2;
        }
    </style>
</head>

<body>
    <header>
        <img src="img/logo.png" alt="Destino Centro" style="height: 50px;">
        <nav>
            <a href="index.php">Visualizar Site</a>
            <a href="logout.php" style="color: #e74c3c; margin-left: 15px;">Sair</a>
        </nav>
    </header>

    <div class="container">
        <h2 style="margin: 20px 0; color: #003358;">Cadastrar Novo Pacote</h2>
        <form class="form-cadastro" method="POST">
            <div class="form-group full-width">
                <label>Nome do Destino</label>
                <input type="text" name="destino" placeholder="Ex: Fernando de Noronha" required>
            </div>
            <div class="form-group">
                <label>Preço Base (R$)</label>
                <input type="number" name="preco" step="0.01" placeholder="Ex: 1500" required>
            </div>
            <div class="form-group">
                <label>Desconto (%)</label>
                <input type="number" name="desconto" placeholder="Ex: 10" value="0">
            </div>
            <div class="form-group full-width">
                <label>URL da Imagem</label>
                <input type="url" name="imagem" placeholder="https://link-da-foto.jpg" required>
            </div>
            <button type="submit" name="btn_salvar" class="btn-salvar">ADICIONAR PACOTE AO SISTEMA</button>
        </form>

        <h2 style="margin-bottom: 20px; color: #003358;">Pacotes Ativos (Vitrine)</h2>
        <table class="admin-table">

            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM pacotes ORDER BY id DESC");
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['destino']}</td>
                            <td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>
                            <td>{$row['desconto']}%</td>
                            <td>
                                <a href='excluir_pacote.php?id={$row['id']}' 
                                   onclick='return confirm(\"Deseja excluir este destino?\")' 
                                   style='color:red; text-decoration:none;'>Excluir Destino</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Nenhum pacote cadastrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <hr style="margin: 50px 0; border: 0; border-top: 1px solid #ddd;">

        <h2 style="margin-bottom: 20px; color: #003358;">Reservas Solicitadas (Vendas)</h2>
        <table class="admin-table">
            
            <tbody>
                <?php
                $sqlReservas = "SELECT r.id as reserva_id, c.nome as cliente_nome, p.destino, r.data_reserva, r.status 
                                FROM reservas r
                                JOIN clientes c ON r.id_cliente = c.id
                                JOIN pacotes p ON r.id_pacote = p.id
                                ORDER BY r.data_reserva DESC";
                $stmtRes = $pdo->query($sqlReservas);

                if ($stmtRes->rowCount() > 0) {
                    while ($res = $stmtRes->fetch(PDO::FETCH_ASSOC)):
                        $data = date('d/m/Y H:i', strtotime($res['data_reserva']));
                        ?>
                        <tr>
                            <td><strong><?php echo $res['cliente_nome']; ?></strong></td>
                            <td><?php echo $res['destino']; ?></td>
                            <td><?php echo $data; ?></td>
                            <td>
                                <span
                                    style="padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; background: <?php echo ($res['status'] == 'Pendente' ? '#f39c12' : '#27ae60'); ?>; color: white;">
                                    <?php echo $res['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($res['status'] == 'Pendente'): ?>
                                    <a href="atualizar_reserva.php?id=<?php echo $res['reserva_id']; ?>&status=Confirmada"
                                        style="color: #27ae60; text-decoration: none; font-weight: bold;">[Confirmar]</a>
                                <?php endif; ?>
                                <a href="excluir_reserva.php?id=<?php echo $res['reserva_id']; ?>"
                                    style="color: #e74c3c; text-decoration: none; margin-left: 10px;">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Nenhuma reserva solicitada ainda.</td></tr>";
                } ?>
            </tbody>
        </table>
    </div>
</body>

</html>