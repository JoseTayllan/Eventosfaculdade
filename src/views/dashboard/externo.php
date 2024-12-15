<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o aluno interno está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Externo') {
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
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <header>
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuário'); ?>!</h1>
        <nav>
            <ul>
                <li><a href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Mensagem de erro ou sucesso -->
        <?php if (isset($_GET['error']) && $_GET['error'] === 'sem_vagas'): ?>
            <p style="color: red;">Este evento não possui mais vagas disponíveis.</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] === 'inscricao_fechada'): ?>
            <p style="color: red;">A inscrição para este evento foi encerrada.</p>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'inscrito'): ?>
            <p style="color: green;">Inscrição realizada com sucesso!</p>
        <?php endif; ?>

        <h2>Meus Eventos</h2>
        <ul>
            <?php if (!empty($eventosInscritos)): ?>
                <?php foreach ($eventosInscritos as $evento): ?>
                    <li>
                        <?php if (!empty($evento['ImagemEvento'])): ?>
                            <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" alt="Imagem do Evento" style="width: 100px; height: auto;">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($evento['NomeEvento']); ?></h3>
                        <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                        <p><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($evento['TipoEvento']); ?></p>
                        <p><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante']); ?></p>
                        <?php if (strtotime($evento['DataFimEvento']) < time()): ?>
                            <p><strong>Comparecimento:</strong>
                                <?php
                                if ($evento['Compareceu'] === null) {
                                    echo "Não registrado";
                                } elseif ($evento['Compareceu'] === 1) {
                                    echo "Compareceu";
                                } else {
                                    echo "Não Compareceu";
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                        <form method="POST" action="/Eventosfaculdade/src/controllers/CancelarInscricaoController.php">
                            <input type="hidden" name="inscricao_id" value="<?php echo $evento['InscricaoId']; ?>">
                            <button type="submit">Cancelar Inscrição</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Você não está inscrito em nenhum evento.</p>
            <?php endif; ?>
        </ul>

        <h2>Eventos Disponíveis</h2>
        <ul>
            <?php if (!empty($eventosDisponiveis)): ?>
                <?php foreach ($eventosDisponiveis as $evento): ?>
                    <li>
                        <?php if (!empty($evento['ImagemEvento'])): ?>
                            <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" alt="Imagem do Evento" style="width: 100px; height: auto;">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($evento['NomeEvento']); ?></h3>
                        <p><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                        <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                        <p><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($evento['TipoEvento']); ?></p>
                        <p><strong>Vagas Disponíveis:</strong> <?php echo htmlspecialchars($evento['VagasDisponiveis']); ?></p>
                        <form method="POST" action="/Eventosfaculdade/src/controllers/InscreverEventoController.php">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                            <button type="submit">Inscrever-se</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum evento disponível para inscrição.</p>
            <?php endif; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>
</body>
</html>
