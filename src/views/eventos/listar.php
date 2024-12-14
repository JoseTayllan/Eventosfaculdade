<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o usuário é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

// Busca todos os eventos
$sql = "SELECT * FROM Eventos";
$stmt = $pdo->query($sql);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Eventos</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Gerenciar Eventos</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Data Início</th>
                <th>Data Fim</th>
                <th>Horário</th>
                <th>Local</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventos as $evento): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evento['NomeEvento']); ?></td>
                    <td><?php echo htmlspecialchars($evento['DataInicioEvento']); ?></td>
                    <td><?php echo htmlspecialchars($evento['DataFimEvento']); ?></td>
                    <td><?php echo htmlspecialchars($evento['HorarioInicio'] . " - " . $evento['HorarioTermino']); ?></td>
                    <td><?php echo htmlspecialchars($evento['LocalEvento']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $evento['EventoId']; ?>">Editar</a>
                        <a href="excluir.php?id=<?php echo $evento['EventoId']; ?>" onclick="return confirm('Tem certeza que deseja excluir este evento?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
