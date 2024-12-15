<?php
require_once '../../../config/database.php';

try {
    // Buscar departamentos
    $sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
    $stmtDepartamentos = $pdo->query($sqlDepartamentos);
    $departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

    // Buscar palestrantes
    $sqlPalestrantes = "SELECT ParticipanteId, NomeParticipante FROM Participantes WHERE TipoParticipante = 'Admin'";
    $stmtPalestrantes = $pdo->query($sqlPalestrantes);
    $palestrantes = $stmtPalestrantes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Evento</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Cadastro de Evento</h1>
    <form method="POST" action="/Eventosfaculdade/src/controllers/CadastrarEventoController.php" enctype="multipart/form-data">
        <label for="nomeEvento">Nome do Evento:</label>
        <input type="text" id="nomeEvento" name="nome" required>

        <label for="dataInicio">Data de Início:</label>
        <input type="date" id="dataInicio" name="data_inicio" required>

        <label for="dataFim">Data de Término:</label>
        <input type="date" id="dataFim" name="data_fim" required>

        <label for="horarioInicio">Horário de Início:</label>
        <input type="time" id="horarioInicio" name="horario_inicio" required>

        <label for="horarioTermino">Horário de Término:</label>
        <input type="time" id="horarioTermino" name="horario_termino" required>

        <label for="local">Local:</label>
        <input type="text" id="local" name="local" required>

        <label for="departamento">Departamento:</label>
        <select id="departamento" name="departamento" required>
            <option value="">Selecione o Departamento</option>
            <?php foreach ($departamentos as $departamento): ?>
                <option value="<?php echo $departamento['DepartamentoId']; ?>">
                    <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="palestrante">Palestrante:</label>
        <select id="palestrante" name="palestrante" required>
            <option value="">Selecione o Palestrante</option>
            <?php foreach ($palestrantes as $palestrante): ?>
                <option value="<?php echo $palestrante['ParticipanteId']; ?>">
                    <?php echo htmlspecialchars($palestrante['NomeParticipante']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="cargaHoraria">Carga Horária:</label>
        <input type="number" id="cargaHoraria" name="carga_horaria" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>

        <label for="imagem">Imagem Representativa:</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">

        <label for="vagas">Número de Vagas Disponíveis:</label>
        <input type="number" id="vagas" name="vagas" min="1" required>

        <button type="submit">Cadastrar Evento</button>
    </form>
</body>
</html>
