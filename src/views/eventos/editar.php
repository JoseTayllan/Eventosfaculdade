<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Verifica se o evento existe
$eventoId = $_GET['evento_id'] ?? null;
if (!$eventoId) {
    header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=evento_nao_encontrado");
    exit;
}

try {
    $sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, 
                   e.LocalEvento, e.CargaHoraria, e.DescricaoEvento, e.ImagemEvento, e.VagasDisponiveis, 
                   d.DepartamentoId, d.NomeDepartamento, p.ParticipanteId, p.NomeParticipante 
            FROM Eventos e
            JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
            JOIN Participantes p ON e.PalestranteId = p.ParticipanteId
            WHERE e.EventoId = :evento_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':evento_id' => $eventoId]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=evento_nao_encontrado");
        exit;
    }

    // Buscar todos os departamentos e palestrantes
    $sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
    $stmtDepartamentos = $pdo->query($sqlDepartamentos);
    $departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

    $sqlPalestrantes = "SELECT ParticipanteId, NomeParticipante FROM Participantes WHERE TipoParticipante = 'Admin'";
    $stmtPalestrantes = $pdo->query($sqlPalestrantes);
    $palestrantes = $stmtPalestrantes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar dados do evento: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Editar Evento</h1>
    <form method="POST" action="/Eventosfaculdade/src/controllers/EditarEventoController.php" enctype="multipart/form-data">
        <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">

        <label for="nomeEvento">Nome do Evento:</label>
        <input type="text" id="nomeEvento" name="nome" value="<?php echo htmlspecialchars($evento['NomeEvento']); ?>" required>

        <label for="dataInicio">Data de Início:</label>
        <input type="date" id="dataInicio" name="data_inicio" value="<?php echo htmlspecialchars($evento['DataInicioEvento']); ?>" required>

        <label for="dataFim">Data de Término:</label>
        <input type="date" id="dataFim" name="data_fim" value="<?php echo htmlspecialchars($evento['DataFimEvento']); ?>" required>

        <label for="horarioInicio">Horário de Início:</label>
        <input type="time" id="horarioInicio" name="horario_inicio" value="<?php echo htmlspecialchars($evento['HorarioInicio']); ?>" required>

        <label for="horarioTermino">Horário de Término:</label>
        <input type="time" id="horarioTermino" name="horario_termino" value="<?php echo htmlspecialchars($evento['HorarioTermino']); ?>" required>

        <label for="local">Local:</label>
        <input type="text" id="local" name="local" value="<?php echo htmlspecialchars($evento['LocalEvento']); ?>" required>

        <label for="vagasDisponiveis">Vagas Disponíveis:</label>
        <input type="number" id="vagasDisponiveis" name="vagas_disponiveis" value="<?php echo htmlspecialchars($evento['VagasDisponiveis']); ?>" required>

        <label for="departamento">Departamento:</label>
        <select id="departamento" name="departamento" required>
            <option value="">Selecione o Departamento</option>
            <?php foreach ($departamentos as $departamento): ?>
                <option value="<?php echo $departamento['DepartamentoId']; ?>" <?php echo $evento['DepartamentoId'] == $departamento['DepartamentoId'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="palestrante">Palestrante:</label>
        <select id="palestrante" name="palestrante" required>
            <option value="">Selecione o Palestrante</option>
            <?php foreach ($palestrantes as $palestrante): ?>
                <option value="<?php echo $palestrante['ParticipanteId']; ?>" <?php echo $evento['ParticipanteId'] == $palestrante['ParticipanteId'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($palestrante['NomeParticipante']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="imagem">Imagem:</label>
        <?php if (!empty($evento['ImagemEvento'])): ?>
            <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" alt="Imagem Atual" style="width: 100px; height: auto;">
        <?php endif; ?>
        <input type="file" id="imagem" name="imagem">

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($evento['DescricaoEvento']); ?></textarea>

        <button type="submit">Salvar Alterações</button>
    </form>
</body>
</html>
