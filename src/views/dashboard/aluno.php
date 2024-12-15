<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o aluno está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Aluno') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

$alunoId = $_SESSION['user_id'];

// Buscar eventos inscritos pelo aluno
$sql = "SELECT e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, e.LocalEvento, e.TipoEvento, p.NomeParticipante AS Palestrante
        FROM Inscricoes i
        JOIN Eventos e ON i.EventoId = e.EventoId
        JOIN Participantes p ON e.PalestranteId = p.ParticipanteId
        WHERE i.ParticipanteId = :aluno_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':aluno_id' => $alunoId]);
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
    <title>Dashboard do Aluno</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <header>
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <nav>
            <ul>
                <li><a href="/Eventosfaculdade/src/views/eventos/listar_disponiveis.php">Ver Eventos Disponíveis</a></li>
                <li><a href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Meus Eventos</h2>
        <ul>
            <?php if (!empty($eventos)): ?>
                <?php foreach ($eventos as $evento): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($evento['NomeEvento']); ?></h3>
                        <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                        <p><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($evento['TipoEvento']); ?></p>
                        <p><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante']); ?></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Você ainda não está inscrito em nenhum evento.</p>
            <?php endif; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>
</body>
</html>
