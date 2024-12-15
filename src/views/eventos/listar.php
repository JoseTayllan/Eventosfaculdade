<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Buscar todos os eventos
try {
    $sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino,
                   e.LocalEvento, e.CargaHoraria, e.DescricaoEvento, e.ImagemEvento, e.VagasDisponiveis, d.NomeDepartamento, 
                   p.NomeParticipante AS Palestrante
            FROM Eventos e
            JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
            JOIN Participantes p ON e.PalestranteId = p.ParticipanteId";
    $stmt = $pdo->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar eventos: " . $e->getMessage());
}

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Eventos</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Eventos Cadastrados</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome do Evento</th>
                <th>Data</th>
                <th>Horário</th>
                <th>Local</th>
                <th>Departamento</th>
                <th>Palestrante</th>
                <th>Carga Horária</th>
                <th>Descrição</th>
                <th>Vagas Disponíveis</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($eventos)): ?>
                <?php foreach ($eventos as $evento): ?>
                    <tr>
                        <td>
                            <?php if (!empty($evento['ImagemEvento'])): ?>
                                <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" alt="Imagem do Evento" style="width: 100px; height: auto;">
                            <?php else: ?>
                                Sem imagem
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($evento['NomeEvento']); ?></td>
                        <td><?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></td>
                        <td><?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></td>
                        <td><?php echo htmlspecialchars($evento['LocalEvento']); ?></td>
                        <td><?php echo htmlspecialchars($evento['NomeDepartamento']); ?></td>
                        <td><?php echo htmlspecialchars($evento['Palestrante']); ?></td>
                        <td><?php echo htmlspecialchars($evento['CargaHoraria']); ?> horas</td>
                        <td><?php echo htmlspecialchars($evento['DescricaoEvento']); ?></td>
                        <td><?php echo htmlspecialchars($evento['VagasDisponiveis']); ?></td>
                        <td>
                            <a href="/Eventosfaculdade/src/views/eventos/editar.php?evento_id=<?php echo $evento['EventoId']; ?>">Editar</a>
                            <form method="POST" action="/Eventosfaculdade/src/controllers/ExcluirEventoController.php" style="display:inline;">
                                <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este evento?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11">Nenhum evento cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
