<?php
require_once realpath(dirname(__DIR__) . '/config/database.php');

// Buscar eventos com palestrante e imagens
$sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, 
               e.LocalEvento, e.TipoEvento, e.ImagemEvento, d.NomeDepartamento, p.NomeParticipante AS Palestrante
        FROM Eventos e
        JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
        JOIN Participantes p ON e.PalestranteId = p.ParticipanteId";
$stmt = $pdo->query($sql);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Ofertados</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white text-center py-3">
        <h1>Sistema de Eventos Acadêmicos</h1>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Eventos Acadêmicos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/Eventosfaculdade/src/views/usuarios/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Eventosfaculdade/src/views/usuarios/cadastrar.php">Cadastrar-se</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container-lg mt-5">
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
                                <a href="/Eventosfaculdade/src/views/usuarios/login.php?redirect=inscricao&evento_id=<?php echo $evento['EventoId']; ?>" class="btn btn-primary mt-auto">Inscrever-se</a>
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
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
