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

try {
    // Buscar departamentos para o filtro
    $sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
    $stmtDepartamentos = $pdo->query($sqlDepartamentos);
    $departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

    // Buscar eventos (removido o filtro por palestrante)
    $sqlEventos = "SELECT e.EventoId, e.NomeEvento, d.NomeDepartamento
                   FROM Eventos e
                   JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId";

    if ($departamentoId) {
        $sqlEventos .= " WHERE e.DepartamentoEventoId = :departamento_id";
    }

    $stmtEventos = $pdo->prepare($sqlEventos);
    $params = [];

    if ($departamentoId) {
        $params[':departamento_id'] = $departamentoId;
    }

    $stmtEventos->execute($params);
    $eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Presença - Seleção de Eventos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Validar Presença</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row">
            <div class="col-md-12">
                <!-- Formulário de Filtro -->
                <form method="GET" action="" class="row mb-4">
                    <div class="col-md-8">
                        <label for="departamento_id" class="form-label">Filtrar por Departamento:</label>
                        <select name="departamento_id" id="departamento_id" class="form-select">
                            <option value="">Todos os Departamentos</option>
                            <?php foreach ($departamentos as $departamento): ?>
                                <option value="<?php echo $departamento['DepartamentoId']; ?>" <?php echo ($departamentoId == $departamento['DepartamentoId']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-grid align-items-end">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>

                <!-- Lista de Eventos -->
                <?php if (count($eventos) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($eventos as $evento): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <strong><?php echo htmlspecialchars($evento['NomeEvento']); ?></strong> - <?php echo htmlspecialchars($evento['NomeDepartamento']); ?>
                                </span>
                                <a href="/Eventosfaculdade/src/views/presenca/validar.php?evento_id=<?php echo $evento['EventoId']; ?>" class="btn btn-success btn-sm">
                                    Validar Presenças
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        Nenhum evento encontrado para o filtro selecionado.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="custom-ocean text-white text-center py-3 mt-5 fixed-bottom">
        <p class="m-0">&copy; 2024 Sistema de Eventos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
