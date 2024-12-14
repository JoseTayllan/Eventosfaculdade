<?php
require_once '../../../config/database.php';

// Buscar departamentos
$sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
$stmtDepartamentos = $pdo->query($sqlDepartamentos);
$departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

// Buscar administradores para o campo de palestrante
$sqlPalestrantes = "SELECT ParticipanteId, NomeParticipante FROM Participantes WHERE TipoParticipante = 'Admin'";
$stmtPalestrantes = $pdo->query($sqlPalestrantes);
$palestrantes = $stmtPalestrantes->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Cadastrar Evento</h1>
    <form method="POST" action="/Eventosfaculdade/src/controllers/CadastrarEventoController.php">
        <input type="text" name="nome" placeholder="Nome do Evento" required>
        <input type="date" name="data_inicio" required>
        <input type="date" name="data_fim" required>
        <input type="time" name="horario_inicio" required>
        <input type="time" name="horario_termino" required>
        <input type="text" name="local" placeholder="Local do Evento" required>
        <select name="tipo" required>
            <option value="Interno">Interno</option>
            <option value="Externo">Externo</option>
        </select>
        <select name="departamento" required>
            <option value="">Selecione um Departamento</option>
            <?php foreach ($departamentos as $departamento): ?>
                <option value="<?php echo $departamento['DepartamentoId']; ?>">
                    <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="carga_horaria" placeholder="Carga Horária (em horas)" required>
        <textarea name="descricao" placeholder="Descrição do Evento" required></textarea>
        <select name="palestrante" required>
            <option value="">Selecione um Palestrante</option>
            <?php foreach ($palestrantes as $palestrante): ?>
                <option value="<?php echo $palestrante['ParticipanteId']; ?>">
                    <?php echo htmlspecialchars($palestrante['NomeParticipante']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
