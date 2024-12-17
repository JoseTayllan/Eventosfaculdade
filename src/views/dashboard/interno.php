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
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Aluno Interno</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
        <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="custom-ocean text-white py-3">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
            <!-- Logo -->
            <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" class="mb-3 mb-md-0" style="height: 50px;">
            
            <!-- Título -->
            <h1 class="h5 text-center text-md-start mb-3 mb-md-0">
                Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            </h1>
            
            <!-- Navegação -->
            <nav>
                <ul class="list-unstyled d-flex flex-wrap justify-content-center justify-content-md-end m-0">
                    <li class="me-2">
                        <a href="/Eventosfaculdade/src/views/dashboard/eventos_disponiveis.php" class="btn btn-outline-light btn-sm">Ver Eventos Disponíveis</a>
                    </li>
                    <li class="me-2">
                        <a href="/Eventosfaculdade/src/views/dashboard/perfil.php" class="btn btn-outline-light btn-sm">Meu Perfil</a>
                    </li>
                    <li>
                        <a href="/Eventosfaculdade/public/index.php" class="btn btn-outline-light btn-sm">Sair</a>
                    </li>
                </ul>
            </nav>
        </div>
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
                <?php foreach ($eventosInscritos as $index => $evento): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            
                            <?php if (!empty($evento['ImagemEvento'])): ?>
                                <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($evento['NomeEvento']); ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <img src="/Eventosfaculdade/public/images/default-evento.jpg" class="card-img-top" alt="Imagem Padrão" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento'] ?? 'Evento sem nome'); ?></h5>
                                <p class="card-text"><strong>Data:</strong> <?php echo htmlspecialchars(formatarData($evento['DataInicioEvento'] ?? '0000-00-00')); ?> a <?php echo htmlspecialchars(formatarData($evento['DataFimEvento'] ?? '0000-00-00')); ?></p>
                                <p class="card-text"><strong>Horário:</strong> <?php echo htmlspecialchars(($evento['HorarioInicio'] ?? '00:00') . ' - ' . ($evento['HorarioTermino'] ?? '00:00')); ?></p>
                                <p class="card-text"><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento'] ?? 'Não informado'); ?></p>
                                <p class="card-text"><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                                
                                <!-- Botão Ver Mais -->
                                <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalEvento<?php echo $index; ?>">Ver Mais</button>
                                
                                <!-- Botão de cancelar inscrição -->
                                <form method="POST" action="/Eventosfaculdade/src/controllers/CancelarInscricaoController.php">
                                    <input type="hidden" name="inscricao_id" value="<?php echo htmlspecialchars($evento['InscricaoId'] ?? ''); ?>">
                                    <button class="btn btn-danger w-100">Cancelar Inscrição</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para o Evento -->
                    <div class="modal fade" id="modalEvento<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalEventoLabel<?php echo $index; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEventoLabel<?php echo $index; ?>"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($evento['DescricaoEvento'] ?? 'Sem descrição disponível.'); ?></p>
                                    <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento'] ?? '0000-00-00'); ?> a <?php echo formatarData($evento['DataFimEvento'] ?? '0000-00-00'); ?></p>
                                    <p><strong>Horário:</strong> <?php echo htmlspecialchars(($evento['HorarioInicio'] ?? '00:00') . ' - ' . ($evento['HorarioTermino'] ?? '00:00')); ?></p>
                                    <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento'] ?? 'Não informado'); ?></p>
                                    <p><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fim do Modal -->
                <?php endforeach; ?>


            <?php else: ?>
                <p class="text-center">Você não está inscrito em nenhum evento.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="custom-ocean text-white text-center py-3 mt-5 fixed-bottom">
    <p class="m-0">&copy; 2024 Sistema de Eventos</p>
</footer>
</body>
</html>