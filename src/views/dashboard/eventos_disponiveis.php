<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o aluno está logado como Interno ou Externo
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['Interno', 'Externo'])) {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type'];

// Buscar eventos disponíveis para inscrição (com vagas disponíveis)
$sqlDisponiveis = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, 
                          e.HorarioInicio, e.HorarioTermino, e.LocalEvento, e.TipoEvento, 
                          e.ImagemEvento, e.VagasDisponiveis, e.DescricaoEvento, e.Palestrante, 
                          d.NomeDepartamento
                   FROM Eventos e
                   JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
                   WHERE e.VagasDisponiveis > 0";
$stmtDisponiveis = $pdo->query($sqlDisponiveis);
$eventosDisponiveis = $stmtDisponiveis->fetchAll(PDO::FETCH_ASSOC);

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Disponíveis</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white text-center py-3">
        <h1>Eventos Disponíveis</h1>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/Eventosfaculdade/src/views/dashboard/<?php echo $userType === 'Interno' ? 'interno.php' : 'externo.php'; ?>">Meus Eventos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mt-5">
        <div class="row">
            <?php if (!empty($eventosDisponiveis)): ?>
                <?php foreach ($eventosDisponiveis as $evento): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo htmlspecialchars($evento['ImagemEvento'] ?: '/Eventosfaculdade/public/images/default-evento.jpg'); ?>" class="card-img-top" alt="Imagem do Evento">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                                <p class="card-text"><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                                <p class="card-text"><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                                <p class="card-text"><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                                <p class="card-text"><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                                <p class="card-text"><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                                <p class="card-text"><strong>Descrição:</strong> <?php echo htmlspecialchars($evento['DescricaoEvento'] ?? 'Sem descrição'); ?></p>
                                <p class="card-text"><strong>Vagas Disponíveis:</strong> <?php echo htmlspecialchars($evento['VagasDisponiveis']); ?></p>
                                <form method="POST" action="/Eventosfaculdade/src/controllers/InscreverEventoController.php">
                                    <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                                    <button class="btn btn-primary w-100">Inscrever-se</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Nenhum evento disponível no momento.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>
</body>
</html>
