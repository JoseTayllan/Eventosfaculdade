<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

// Consulta para listar eventos com inscrições
try {
    $sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, COUNT(i.InscricaoId) AS TotalInscritos
            FROM Eventos e
            LEFT JOIN Inscricoes i ON e.EventoId = i.EventoId
            GROUP BY e.EventoId";
    $stmt = $pdo->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar eventos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrições por Evento</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Inscrições por Evento</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nome do Evento</th>
                <th>Data</th>
                <th>Total de Inscritos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($eventos)): ?>
                <?php foreach ($eventos as $evento): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($evento['NomeEvento']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($evento['DataInicioEvento'])); ?> - <?php echo date('d/m/Y', strtotime($evento['DataFimEvento'])); ?></td>
                        <td><?php echo $evento['TotalInscritos']; ?></td>
                        <td>
                            <a href="/Eventosfaculdade/src/views/inscricoes/detalhar_inscricoes.php?evento_id=<?php echo $evento['EventoId']; ?>">Detalhar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum evento encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="/Eventosfaculdade/src/views/dashboard/admin.php">Voltar ao Painel Administrativo</a>
</body>
</html>
