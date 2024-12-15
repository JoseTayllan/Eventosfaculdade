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
                        e.LocalEvento, e.DescricaoEvento, e.ImagemEvento, e.Palestrante 
                 FROM Inscricoes i
                 JOIN Eventos e ON i.EventoId = e.EventoId
                 WHERE i.ParticipanteId = :aluno_id";
$stmtInscritos = $pdo->prepare($sqlInscritos);
$stmtInscritos->execute([':aluno_id' => $alunoId]);
$eventosInscritos = $stmtInscritos->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="d-flex align-items-center justify-content-between py-3 px-4 bg-secondary text-white">
        <div>
            <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;">
        </div>
        <h1 class="m-0">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <nav>
            <ul class="list-unstyled d-flex m-0">
                <li class="me-3">
                    <a href="/Eventosfaculdade/src/views/dashboard/eventos_disponiveis.php" class="btn btn-outline-light btn-sm me-2">Ver Eventos Disponíveis</a>
                </li>
                <li>
                    <a href="/Eventosfaculdade/src/views/dashboard/perfil.php" class="btn btn-outline-light btn-sm me-2">Meu Perfil</a>
                </li>
                <li>
                    <a href="/Eventosfaculdade/public/index.php" class="btn btn-outline-light btn-sm me-2">Sair</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Mensagens de feedback -->
    <main class="container mt-5" style="padding-bottom: 80px;">
        <?php if (isset($_GET['status'])): ?>
            <div class="alert <?php echo ($_GET['status'] === 'cancel_success') ? 'alert-success' : 'alert-danger'; ?> text-center">
                <?php
                if ($_GET['status'] === 'cancel_success') {
                    echo "Inscrição cancelada com sucesso!";
                } elseif ($_GET['status'] === 'error_db') {
                    echo "Erro ao cancelar a inscrição. Por favor, tente novamente.";
                } elseif ($_GET['status'] === 'error_permission') {
                    echo "Erro: Você não tem permissão para cancelar esta inscrição.";
                }
                ?>
            </div>
        <?php endif; ?>

        <h2 class="mb-4">Meus Eventos</h2>
        <div class="row">
            <?php if (!empty($eventosInscritos)): ?>
                <?php foreach ($eventosInscritos as $evento): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo htmlspecialchars($evento['ImagemEvento'] ?: '/Eventosfaculdade/public/images/default-evento.jpg'); ?>" class="card-img-top" alt="Imagem do Evento">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento'] ?? 'Evento sem nome'); ?></h5>
                                <p class="card-text"><strong>Data:</strong> <?php echo htmlspecialchars(formatarData($evento['DataInicioEvento'] ?? '0000-00-00')); ?> a <?php echo htmlspecialchars(formatarData($evento['DataFimEvento'] ?? '0000-00-00')); ?></p>
                                <p class="card-text"><strong>Horário:</strong> <?php echo htmlspecialchars(($evento['HorarioInicio'] ?? '00:00') . ' - ' . ($evento['HorarioTermino'] ?? '00:00')); ?></p>
                                <p class="card-text"><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento'] ?? 'Não informado'); ?></p>
                                <p class="card-text"><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                                <p class="card-text"><strong>Descrição:</strong> <?php echo htmlspecialchars($evento['DescricaoEvento'] ?? 'Sem descrição'); ?></p>
                            </div>
                            <form method="POST" action="/Eventosfaculdade/src/controllers/CancelarInscricaoController.php">
                                <input type="hidden" name="inscricao_id" value="<?php echo htmlspecialchars($evento['InscricaoId'] ?? ''); ?>">
                                <button class="btn btn-danger w-100">Cancelar Inscrição</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Você não está inscrito em nenhum evento.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-secondary text-white text-center py-3 mt-5 fixed-bottom">
    <p class="m-0">&copy; 2024 Sistema de Eventos</p>
</footer>
</body>
</html>
