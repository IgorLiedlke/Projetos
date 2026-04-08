<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destino Centro Turismo | Sua próxima viagem começa aqui</title>
    <link rel="icon" type="image/png" href="img/logo.png">
</head>

<style>
    :root {
        --primary-color: #003358;
        /* Azul escuro da borda da logo */
        --accent-color: #388087;
        /* Verde/Azul turquesa do centro */
        --bg-light: #f9f9f9;
        --white: #ffffff;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: var(--bg-light);
        color: var(--secondary-color);
    }

    /* Header & Navegação */
    header {
        background: var(--white);
        padding: 1rem 5%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .logo {
        height: 50px;
        font-weight: bold;
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    nav a {
        text-decoration: none;
        color: var(--secondary-color);
        margin-left: 20px;
        font-weight: 600;
        transition: 0.3s;
    }

    nav a:hover,
    nav a.active {
        color: var(--primary-color);
    }

    /* Grid de Pacotes */
    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .grid-pacotes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    /* Card Estilo Profissional */
    .card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        transition: transform 0.3s ease;
        cursor: pointer;
        box-shadow: var(--shadow);
    }

    .card:hover {
        transform: translateY(-8px);
    }

    .card-img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .card-overlay {
        position: absolute;
        bottom: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
        width: 100%;
        padding: 20px;
        color: white;
    }

    .badge-promo {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #ffc107;
        color: #000;
        padding: 5px 12px;
        font-weight: bold;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .btn-card {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 25px;
        border: 1px solid white;
        border-radius: 20px;
        color: white;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-card:hover {
        background: white;
        color: black;
    }

    /* Tabela Admin */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .admin-table th,
    .admin-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .admin-table th {
        background: var(--secondary-color);
        color: white;
    }

    .btn-add {
        background: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
    }

    /* --- NOVO HERO PREMIUM --- */
    .hero-premium {
        background-color: #f4f7f6;
        /* Cor de fundo leve para destacar o branco dos cards */
        padding: 60px 0 80px 0;
        overflow: hidden;
    }

    .hero-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 50px;
    }

    /* LADO ESQUERDO */
    .hero-text-side {
        flex: 1;
        max-width: 550px;
    }

    .hero-subtitle {
        color: var(--accent-color);
        /* Verde Água */
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: bold;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 10px;
    }

    .hero-text-side h1 {
        font-size: 3.2rem;
        color: var(--primary-color);
        /* Azul Marinho */
        line-height: 1.2;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .hero-text-side h1 .highlight {
        position: relative;
        z-index: 1;
    }

    /* Efeito de rabisco/sublinhado atrás da palavra destaque */
    .hero-text-side h1 .highlight::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 5px;
        width: 100%;
        height: 15px;
        background-color: rgba(56, 128, 135, 0.2);
        /* Verde água transparente */
        z-index: -1;
        transform: rotate(-2deg);
    }

    .hero-text-side p {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 35px;
        line-height: 1.6;
    }

    .hero-cta-group {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .btn-premium-hero {
        background: var(--primary-color);
        color: white;
        padding: 18px 35px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1rem;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(0, 51, 88, 0.2);
    }

    .btn-premium-hero:hover {
        background: var(--accent-color);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(56, 128, 135, 0.3);
    }

    .btn-secondary-hero {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-secondary-hero:hover {
        color: var(--accent-color);
        text-decoration: underline;
    }

    /* LADO DIREITO */
    .hero-image-side {
        flex: 1;
        position: relative;
        display: flex;
        justify-content: center;
    }

    .image-floating-card {
        position: relative;
        width: 100%;
        max-width: 450px;
    }

    .hero-featured-img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .floating-badge {
        position: absolute;
        bottom: -30px;
        left: -40px;
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 250px;
        border-left: 5px solid var(--accent-color);
    }

    .floating-badge p {
        margin: 5px 0 0 0;
        font-size: 0.85rem;
        color: #555;
        font-style: italic;
    }

    .star-rating {
        color: #f39c12;
        /* Amarelo ouro */
        font-size: 0.9rem;
    }

    /* --- RESPONSIVIDADE (CELULARES) --- */
    @media (max-width: 992px) {
        .hero-premium {
            padding: 40px 0;
        }

        .hero-wrapper {
            flex-direction: column;
            text-align: center;
            gap: 40px;
        }

        .hero-text-side h1 {
            font-size: 2.5rem;
        }

        .hero-cta-group {
            justify-content: center;
            flex-direction: column;
            gap: 15px;
        }

        .hero-image-side {
            max-width: 100%;
        }

        .hero-featured-img {
            height: 350px;
        }

        .floating-badge {
            position: relative;
            bottom: 0;
            left: 0;
            width: 100%;
            margin-top: 20px;
        }
    }
</style>

<body>
    <header>
        <img src="img/logo.png" alt="Destino Centro" style="height: 50px;">
        <nav>
            <a href="index.php">Pacotes</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="reservas.php">Minhas Reservas</a>
                <?php if ($_SESSION['user_nivel'] == 'admin'): ?>
                    <a href="admin.php" style="font-weight: bold; color: var(--accent-color);">Painel Admin</a>
                <?php endif; ?>
                <a href="logout.php" style="color: #e74c3c;">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
            <?php endif; ?>
        </nav>
    </header>

    <section class="hero-premium">
        <div class="hero-wrapper container">

            <div class="hero-text-side">
                <span class="hero-subtitle">Destino Centro Turismo</span>
                <h1>Encontre sua próxima <span class="highlight">grande história</span>.</h1>
                <p>Milhares de destinos, pacotes exclusivos e os melhores preços do Brasil. Onde você quer estar amanhã?
                </p>

                <div class="hero-cta-group">
                    <a href="#ofertas" class="btn-premium-hero">Explorar Ofertas 🔥</a>
                    <a href="cadastro.php" class="btn-secondary-hero">Criar Conta Grátis</a>
                </div>
            </div>

            <div class="hero-image-side">
                <div class="image-floating-card">
                    <img src="https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="Destino em Destaque" class="hero-featured-img">
                    <div class="floating-badge">
                        <span class="star-rating">⭐⭐⭐⭐⭐</span>
                        <p>"A melhor viagem da minha vida!" - Ana J.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div id="ofertas" style="margin-top: -100px; position: absolute;"></div>

    <main class="container">
        <h2 style="margin: 40px 0 20px 0; color: var(--primary-color);">Ofertas de Hoje</h2>

        <div class="grid-pacotes">
            <?php
            // Busca todos os pacotes reais cadastrados por você no Admin
            $stmt = $pdo->query("SELECT * FROM pacotes ORDER BY id DESC");

            // Verifica se existe algum pacote no banco
            if ($stmt->rowCount() > 0):
                while ($p = $stmt->fetch(PDO::FETCH_ASSOC)):
                    // Cálculos de preço real
                    $valor_original = $p['preco'];
                    $desconto = $p['desconto'];
                    $valor_final = $valor_original - ($valor_original * ($desconto / 100));
                    ?>
                    <div class="card">
                        <?php if ($desconto > 0): ?>
                            <div class="badge-promo">-<?php echo $desconto; ?>%</div>
                        <?php endif; ?>

                        <img src="<?php echo $p['imagem_url']; ?>" class="card-img" alt="<?php echo $p['destino']; ?>">

                        <div class="card-overlay">
                            <h3><?php echo $p['destino']; ?></h3>
                            <p style="font-size: 0.8rem; opacity: 0.8; margin-bottom: 5px;">A PARTIR DE</p>
                            <div class="preco">
                                <?php if ($desconto > 0): ?>
                                    <span style="text-decoration: line-through; font-size: 0.85rem; opacity: 0.7;">
                                        R$ <?php echo number_format($valor_original, 2, ',', '.'); ?>
                                    </span><br>
                                <?php endif; ?>
                                <strong style="font-size: 1.4rem;">R$
                                    <?php echo number_format($valor_final, 2, ',', '.'); ?></strong>
                            </div>
                            <a href="detalhes.php?id=<?php echo $p['id']; ?>" class="btn-card">VER DETALHES</a>
                        </div>
                    </div>
                    <?php
                endwhile;
            else:
                ?>
                <p style="grid-column: span 3; text-align: center; padding: 50px; color: #666;">
                    Nenhuma oferta disponível no momento. Volte mais tarde!
                </p>
            <?php endif; ?>
        </div>


    </main>

    <footer style="margin-top: 50px; padding: 30px; text-align: center; background: #f4f7f6; color: #666;">
        <p>&copy; 2026 Destino Centro Turismo - Todos os direitos reservados.</p>
    </footer>

</body>

</html>