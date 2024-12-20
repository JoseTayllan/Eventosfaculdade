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
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Disponíveis</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
        <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body>
    <!-- Header -->
    <header class="d-flex align-items-center justify-content-between py-3 px-4 custom-ocean text-white">
        <div>
            <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;">
        </div>
        <h1 class="m-0">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <nav>
            <ul class="list-unstyled d-flex m-0">
                <li>
                    <a href="/Eventosfaculdade/public/index.php" class="btn btn-outline-light btn-sm me-4">Sair</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mt-5" style="padding-bottom: 80px;">
    <!-- Mensagem de Feedback -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?> text-center">
            <?php
            switch ($_GET['status']) {
                case 'success':
                    echo 'Inscrição realizada com sucesso!';
                    break;
                case 'cancel_success':
                    echo 'Inscrição cancelada com sucesso!';
                    break;
                case 'full':
                    echo 'Este evento não possui mais vagas disponíveis.';
                    break;
                case 'closed':
                    echo 'A inscrição para este evento foi encerrada.';
                    break;
                case 'already_registered':
                    echo 'Você já está inscrito neste evento.';
                    break;
                default:
                    echo 'Ocorreu um erro ao processar sua solicitação.';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($eventosDisponiveis)): ?>
            <?php foreach ($eventosDisponiveis as $index => $evento): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($evento['ImagemEvento'])): ?>
                            <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($evento['NomeEvento']); ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <img src="/Eventosfaculdade/public/images/default-evento.jpg" class="card-img-top" alt="Imagem Padrão" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                            <p class="card-text"><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                            <p class="card-text"><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                            <p class="card-text"><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                            <p class="card-text"><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                            <p class="card-text"><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                            <p class="card-text"><strong>Vagas Disponíveis:</strong> <?php echo htmlspecialchars($evento['VagasDisponiveis']); ?></p>
                            <form method="POST" action="/Eventosfaculdade/src/controllers/InscreverEventoController.php">
                                <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                                <button class="btn custom-ocean w-100 mb-2">Inscrever-se</button>
                            </form>
                            <!-- Botão Ver Mais -->
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalEvento<?php echo $index; ?>">Ver Mais</button>
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
                                <p><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                                <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                                <p><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                                <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                                <p><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                                <p><strong>Vagas Disponíveis:</strong> <?php echo htmlspecialchars($evento['VagasDisponiveis']); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <form method="POST" action="/Eventosfaculdade/src/controllers/InscreverEventoController.php">
                                    <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                                    <button class="btn btn-primary">Inscrever-se</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim do Modal -->
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Nenhum evento disponível no momento.</p>
        <?php endif; ?>
    </div>
</main>


    <!-- Footer -->
    <footer class="custom-ocean text-white text-center py-3 mt-5 fixed-bottom">
    <p class="m-0">&copy; 2024 Sistema de Eventos</p>
</footer>
</body>
</html>
