<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

$eventoId = $_GET['evento_id'] ?? null;

if (!$eventoId) {
    header("Location: /Eventosfaculdade/src/views/inscricoes/listar_inscricoes.php?error=evento_nao_encontrado");
    exit;
}

// Busca os participantes inscritos no evento
try {
    $sql = "SELECT p.NomeParticipante, p.EmailParticipante, p.TipoParticipante, i.Compareceu
            FROM Inscricoes i
            JOIN Participantes p ON i.ParticipanteId = p.ParticipanteId
            WHERE i.EventoId = :evento_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':evento_id' => $eventoId]);
    $inscritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar inscrições: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes das Inscrições</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Inscritos no Evento</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Compareceu</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inscritos)): ?>
                <?php foreach ($inscritos as $inscrito): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inscrito['NomeParticipante']); ?></td>
                        <td><?php echo htmlspecialchars($inscrito['EmailParticipante']); ?></td>
                        <td><?php echo htmlspecialchars($inscrito['TipoParticipante']); ?></td>
                        <td>
                            <?php
                            if ($inscrito['Compareceu'] === null) {
                                echo "Não registrado";
                            } elseif ($inscrito['Compareceu'] == 1) {
                                echo "Sim";
                            } else {
                                echo "Não";
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum participante inscrito.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="/Eventosfaculdade/src/views/inscricoes/listar_inscricoes.php">Voltar</a>
</body>
</html>
