<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

// Inicializar filtros
$filtros = [
    'departamento' => $_GET['departamento'] ?? null,
    'data_inicio' => $_GET['data_inicio'] ?? null,
    'data_fim' => $_GET['data_fim'] ?? null,
    'tipo_participante' => $_GET['tipo_participante'] ?? null,
];

// Buscar departamentos para o filtro
try {
    $sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
    $stmtDepartamentos = $pdo->query($sqlDepartamentos);
    $departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar departamentos: " . $e->getMessage());
}

// Construir consulta com filtros
$sql = "SELECT 
            e.NomeEvento,
            p.NomeParticipante,
            p.EmailParticipante,
            p.TipoParticipante,
            i.Compareceu,
            d.NomeDepartamento
        FROM Inscricoes i
        JOIN Participantes p ON i.ParticipanteId = p.ParticipanteId
        JOIN Eventos e ON i.EventoId = e.EventoId
        JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
        WHERE 1=1";

// Aplicar filtros
$params = [];
if (!empty($filtros['departamento'])) {
    $sql .= " AND e.DepartamentoEventoId = :departamento";
    $params[':departamento'] = $filtros['departamento'];
}
if (!empty($filtros['data_inicio'])) {
    $sql .= " AND e.DataInicioEvento >= :data_inicio";
    $params[':data_inicio'] = $filtros['data_inicio'];
}
if (!empty($filtros['data_fim'])) {
    $sql .= " AND e.DataFimEvento <= :data_fim";
    $params[':data_fim'] = $filtros['data_fim'];
}
if (!empty($filtros['tipo_participante'])) {
    $sql .= " AND p.TipoParticipante = :tipo_participante";
    $params[':tipo_participante'] = $filtros['tipo_participante'];
}

$sql .= " ORDER BY e.DataInicioEvento";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar inscrições: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Inscrições</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Filtrar Inscrições</h1>

    <!-- Formulário de Filtros -->
    <!-- Formulário de Filtros -->
<form method="GET" action="">
    <label for="departamento">Departamento:</label>
    <select name="departamento" id="departamento">
        <option value="">Todos</option>
        <?php foreach ($departamentos as $departamento): ?>
            <option value="<?php echo $departamento['DepartamentoId']; ?>" 
                <?php echo ($filtros['departamento'] == $departamento['DepartamentoId']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="data_inicio">Data Início:</label>
    <input type="date" name="data_inicio" id="data_inicio" value="<?php echo htmlspecialchars($filtros['data_inicio'] ?? ''); ?>">

    <label for="data_fim">Data Fim:</label>
    <input type="date" name="data_fim" id="data_fim" value="<?php echo htmlspecialchars($filtros['data_fim'] ?? ''); ?>">

    <label for="tipo_participante">Tipo de Participante:</label>
    <select name="tipo_participante" id="tipo_participante">
        <option value="">Todos</option>
        <option value="Interno" <?php echo ($filtros['tipo_participante'] == 'Interno') ? 'selected' : ''; ?>>Interno</option>
        <option value="Externo" <?php echo ($filtros['tipo_participante'] == 'Externo') ? 'selected' : ''; ?>>Externo</option>
    </select>

    <button type="submit">Filtrar</button>
</form>


    <!-- Tabela de Inscrições -->
    <table border="1">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Participante</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Departamento</th>
                <th>Compareceu</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inscricoes)): ?>
                <?php foreach ($inscricoes as $inscricao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inscricao['NomeEvento']); ?></td>
                        <td><?php echo htmlspecialchars($inscricao['NomeParticipante']); ?></td>
                        <td><?php echo htmlspecialchars($inscricao['EmailParticipante']); ?></td>
                        <td><?php echo htmlspecialchars($inscricao['TipoParticipante']); ?></td>
                        <td><?php echo htmlspecialchars($inscricao['NomeDepartamento']); ?></td>
                        <td><?php echo ($inscricao['Compareceu'] === null) ? "Não registrado" : ($inscricao['Compareceu'] ? "Sim" : "Não"); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhuma inscrição encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="/Eventosfaculdade/src/views/dashboard/admin.php">Voltar ao Painel Administrativo</a>
</body>
</html>
