<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o parâmetro evento_id foi passado
if (!isset($_GET['evento_id'])) {
    die("Evento não especificado.");
}

$eventoId = $_GET['evento_id'];

// Buscar alunos inscritos no evento
$sql = "SELECT p.ParticipanteId, p.NomeParticipante, i.Compareceu
        FROM Inscricoes i
        JOIN Participantes p ON i.ParticipanteId = p.ParticipanteId
        WHERE i.EventoId = :evento_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':evento_id' => $eventoId]);
$inscritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Presença</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Validar Presença - Evento</h1>
    <form method="POST" action="/Eventosfaculdade/src/controllers/SalvarPresencaController.php">
        <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($eventoId); ?>">
        <table>
            <thead>
                <tr>
                    <th>Nome do Participante</th>
                    <th>Presente</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($inscritos)): ?>
                    <?php foreach ($inscritos as $inscrito): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($inscrito['NomeParticipante']); ?></td>
                            <td>
                                <input type="checkbox" name="presenca[<?php echo $inscrito['ParticipanteId']; ?>]" <?php echo $inscrito['Compareceu'] ? 'checked' : ''; ?>>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Nenhum participante inscrito neste evento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button type="submit">Salvar Presenças</button>
    </form>
</body>
</html>
