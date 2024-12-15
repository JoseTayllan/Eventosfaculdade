<?php
require_once realpath(dirname(__DIR__) . '/config/database.php');

// Buscar eventos com palestrante e imagens
$sqlEventos = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, 
               e.LocalEvento, e.TipoEvento, e.ImagemEvento, e.Palestrante, d.NomeDepartamento
        FROM Eventos e
        JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId";
$stmtEventos = $pdo->query($sqlEventos);
$eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);

// Buscar banners
$sqlBanners = "SELECT ImagemBanner, Titulo FROM Banners ORDER BY DataCriacao DESC";
$stmtBanners = $pdo->query($sqlBanners);
$banners = $stmtBanners->fetchAll(PDO::FETCH_ASSOC);

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Ofertados</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<!-- Header -->
<header class="custom-ocean text-white text-center py-3">
    <h1>Sistema de Eventos Acadêmicos</h1>
    <nav class="navbar navbar-expand-lg navbar-dark custom-ocean">
        <div class="container">
            <!-- Logo -->
            <a href="#" class="navbar-brand d-flex align-items-center">
                <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" class="me-2" style="height: 70px;"> 
                
            </a>
            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm mx-1" href="/Eventosfaculdade/src/views/usuarios/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm mx-1" href="/Eventosfaculdade/src/views/usuarios/register.php">Cadastrar-se</a>
                    </li>
                    <li class="nav-item">
                        <!-- Botão Admin -->
                        <a class="btn btn-outline-light btn-sm mx-1" href="/Eventosfaculdade/src/views/admin/login.php">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>



    <!-- Carrossel de Banners -->
    <?php if (!empty($banners)): ?>
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($banners as $index => $banner): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($banner['ImagemBanner']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($banner['Titulo'] ?? 'Banner'); ?>" style="height: 400px; object-fit: cover;">
                        <?php if (!empty($banner['Titulo'])): ?>
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?php echo htmlspecialchars($banner['Titulo']); ?></h5>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <main class="container mt-5" style="padding-bottom: 80px;">
        <h2 class="text-center mb-4">Eventos Disponíveis</h2>
        <div class="row">
            <?php if (!empty($eventos)): ?>
                <?php foreach ($eventos as $evento): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <!-- Imagem do Curso -->
                            <?php if (!empty($evento['ImagemEvento'])): ?>
                                <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($evento['NomeEvento']); ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <img src="/Eventosfaculdade/public/images/default-evento.jpg" class="card-img-top" alt="Imagem Padrão" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                                <p class="card-text"><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                                <p class="card-text"><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                                <p class="card-text"><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                                <p class="card-text"><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                                <p class="card-text"><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                                <a href="/Eventosfaculdade/src/views/usuarios/login.php?redirect=inscricao&evento_id=<?php echo $evento['EventoId']; ?>" class="btn custom-ocean mt-auto">Inscrever-se</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Nenhum evento disponível no momento.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="custom-ocean text-white text-center py-3 mt-5">
    <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
</footer>


    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
