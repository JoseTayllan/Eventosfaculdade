<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

// Consulta para obter o resumo de presenças por evento
try {
    $sql = "SELECT 
                e.NomeEvento,
                COUNT(CASE WHEN i.Compareceu = 1 THEN 1 END) AS TotalPresentes,
                COUNT(CASE WHEN i.Compareceu = 0 THEN 1 END) AS TotalAusentes,
                COUNT(i.InscricaoId) AS TotalInscritos
            FROM Eventos e
            LEFT JOIN Inscricoes i ON e.EventoId = i.EventoId
            GROUP BY e.EventoId";
    $stmt = $pdo->query($sql);
    $resumo = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar resumo de presenças: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo Geral de Presenças</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Resumo Geral de Presenças</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nome do Evento</th>
                <th>Total Inscritos</th>
                <th>Total Presentes</th>
                <th>Total Ausentes</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($resumo)): ?>
                <?php foreach ($resumo as $evento): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($evento['NomeEvento']); ?></td>
                        <td><?php echo $evento['TotalInscritos']; ?></td>
                        <td><?php echo $evento['TotalPresentes']; ?></td>
                        <td><?php echo $evento['TotalAusentes']; ?></td>
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
