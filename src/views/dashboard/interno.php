<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o aluno interno está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Interno') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

$alunoId = $_SESSION['user_id'] ?? null;

// Buscar eventos inscritos pelo aluno
$sqlInscritos = "SELECT i.InscricaoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, 
                        e.LocalEvento, e.TipoEvento, e.ImagemEvento, e.VagasDisponiveis, 
                        p.NomeParticipante AS Palestrante, i.Compareceu
                 FROM Inscricoes i
                 JOIN Eventos e ON i.EventoId = e.EventoId
                 JOIN Participantes p ON e.PalestranteId = p.ParticipanteId
                 WHERE i.ParticipanteId = :aluno_id";
$stmtInscritos = $pdo->prepare($sqlInscritos);
$stmtInscritos->execute([':aluno_id' => $alunoId]);
$eventosInscritos = $stmtInscritos->fetchAll(PDO::FETCH_ASSOC);

// Buscar eventos disponíveis (não inscritos)
$sqlDisponiveis = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, 
                          e.LocalEvento, e.TipoEvento, e.ImagemEvento, e.VagasDisponiveis, 
                          d.NomeDepartamento
                   FROM Eventos e
                   JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
                   WHERE e.EventoId NOT IN (
                       SELECT EventoId FROM Inscricoes WHERE ParticipanteId = :aluno_id
                   )";
$stmtDisponiveis = $pdo->prepare($sqlDisponiveis);
$stmtDisponiveis->execute([':aluno_id' => $alunoId]);
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
    <title>Dashboard do Aluno Interno</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="bg-secondary text-white text-center py-3">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuário'); ?>!</h1>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/Eventosfaculdade/src/views/dashboard/perfil.php">Meu Perfil</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mt-5">
        <!-- Mensagens -->
        <?php if (isset($_GET['error']) || isset($_GET['success'])): ?>
            <div class="alert <?php echo isset($_GET['error']) ? 'alert-danger' : 'alert-success'; ?> text-center">
                <?php
                if (isset($_GET['error'])) {
                    echo htmlspecialchars($_GET['error'] === 'sem_vagas' ? 'Este evento não possui mais vagas disponíveis.' : 'A inscrição para este evento foi encerrada.');
                } elseif (isset($_GET['success'])) {
                    echo htmlspecialchars('Inscrição realizada com sucesso!');
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Eventos Inscritos -->
        <h2 class="mb-4">Meus Eventos</h2>
        <div class="row">
            <?php if (!empty($eventosInscritos)): ?>
                <?php foreach ($eventosInscritos as $evento): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($evento['ImagemEvento'])): ?>
                                <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" class="card-img-top" alt="Imagem do Evento">
                            <?php else: ?>
                                <img src="/Eventosfaculdade/public/images/default-evento.jpg" class="card-img-top" alt="Imagem Padrão">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                                <p class="card-text"><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                                <p class="card-text"><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                                <p class="card-text"><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                                <p class="card-text"><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante']); ?></p>
                                <form method="POST" action="/Eventosfaculdade/src/controllers/CancelarInscricaoController.php">
                                    <input type="hidden" name="inscricao_id" value="<?php echo $evento['InscricaoId']; ?>">
                                    <button class="btn btn-danger w-100">Cancelar Inscrição</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Você não está inscrito em nenhum evento.</p>
            <?php endif; ?>
        </div>

        <!-- Eventos Disponíveis -->
        <h2 class="mb-4">Eventos Disponíveis</h2>
        <div class="row">
            <?php if (!empty($eventosDisponiveis)): ?>
                <?php foreach ($eventosDisponiveis as $evento): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($evento['ImagemEvento'])): ?>
                                <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" class="card-img-top" alt="Imagem do Evento">
                            <?php else: ?>
                                <img src="/Eventosfaculdade/public/images/default-evento.jpg" class="card-img-top" alt="Imagem Padrão">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                                <p class="card-text"><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                                <p class="card-text"><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
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
                <p class="text-center">Nenhum evento disponível para inscrição.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
