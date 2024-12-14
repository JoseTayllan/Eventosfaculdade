<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

$adminId = $_SESSION['user_id'];
$departamentoId = $_GET['departamento_id'] ?? null;

// Buscar departamentos para o filtro
$sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
$stmtDepartamentos = $pdo->query($sqlDepartamentos);
$departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

// Buscar eventos ministrados pelo administrador
$sqlEventos = "SELECT e.EventoId, e.NomeEvento, d.NomeDepartamento
               FROM Eventos e
               JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
               WHERE e.PalestranteId = :admin_id";

if ($departamentoId) {
    $sqlEventos .= " AND e.DepartamentoEventoId = :departamento_id";
}

$stmtEventos = $pdo->prepare($sqlEventos);
$params = [':admin_id' => $adminId];
if ($departamentoId) {
    $params[':departamento_id'] = $departamentoId;
}
$stmtEventos->execute($params);
$eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Presença - Seleção de Eventos</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Validar Presença</h1>
    <form method="GET" action="">
        <label for="departamento_id">Filtrar por Departamento:</label>
        <select name="departamento_id" id="departamento_id">
            <option value="">Todos os Departamentos</option>
            <?php foreach ($departamentos as $departamento): ?>
                <option value="<?php echo $departamento['DepartamentoId']; ?>" <?php echo ($departamentoId == $departamento['DepartamentoId']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <ul>
        <?php foreach ($eventos as $evento): ?>
            <li>
                <?php echo htmlspecialchars($evento['NomeEvento']) . ' - ' . htmlspecialchars($evento['NomeDepartamento']); ?>
                <a href="/Eventosfaculdade/src/views/presenca/validar.php?evento_id=<?php echo $evento['EventoId']; ?>">Validar Presenças</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
