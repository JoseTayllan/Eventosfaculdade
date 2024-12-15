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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Filtrar Inscrições</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row">
            <div class="col-md-12">
                <!-- Formulário de Filtros -->
                <form method="GET" action="" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="departamento" class="form-label">Departamento:</label>
                        <select name="departamento" id="departamento" class="form-select">
                            <option value="">Todos</option>
                            <?php foreach ($departamentos as $departamento): ?>
                                <option value="<?php echo $departamento['DepartamentoId']; ?>" 
                                    <?php echo ($filtros['departamento'] == $departamento['DepartamentoId']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="data_inicio" class="form-label">Data Início:</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?php echo htmlspecialchars($filtros['data_inicio'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="data_fim" class="form-label">Data Fim:</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control" value="<?php echo htmlspecialchars($filtros['data_fim'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="tipo_participante" class="form-label">Tipo de Participante:</label>
                        <select name="tipo_participante" id="tipo_participante" class="form-select">
                            <option value="">Todos</option>
                            <option value="Interno" <?php echo ($filtros['tipo_participante'] == 'Interno') ? 'selected' : ''; ?>>Interno</option>
                            <option value="Externo" <?php echo ($filtros['tipo_participante'] == 'Externo') ? 'selected' : ''; ?>>Externo</option>
                        </select>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>

                <!-- Tabela de Inscrições -->
                <?php if (!empty($inscricoes)): ?>
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
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
                            <?php foreach ($inscricoes as $inscricao): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($inscricao['NomeEvento']); ?></td>
                                    <td><?php echo htmlspecialchars($inscricao['NomeParticipante']); ?></td>
                                    <td><?php echo htmlspecialchars($inscricao['EmailParticipante']); ?></td>
                                    <td><?php echo htmlspecialchars($inscricao['TipoParticipante']); ?></td>
                                    <td><?php echo htmlspecialchars($inscricao['NomeDepartamento']); ?></td>
                                    <td>
                                        <?php echo ($inscricao['Compareceu'] === null) 
                                            ? "<span class='text-muted'>Não registrado</span>" 
                                            : ($inscricao['Compareceu'] ? "<span class='text-success'>Sim</span>" : "<span class='text-danger'>Não</span>"); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning text-center">
                        Nenhuma inscrição encontrada.
                    </div>
                <?php endif; ?>

                <!-- Botão para Voltar -->
                <div class="text-center mt-4">
                    <a href="/Eventosfaculdade/src/views/dashboard/admin.php" class="btn btn-secondary">Voltar ao Painel Administrativo</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5 fixed-bottom">
    <p class="m-0">&copy; 2024 Sistema de Eventos</p>
</footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
