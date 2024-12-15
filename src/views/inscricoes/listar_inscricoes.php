<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

// Consulta para listar eventos com inscrições
try {
    $sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, COUNT(i.InscricaoId) AS TotalInscritos
            FROM Eventos e
            LEFT JOIN Inscricoes i ON e.EventoId = i.EventoId
            GROUP BY e.EventoId";
    $stmt = $pdo->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar eventos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrições por Evento</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Inscrições por Evento</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($eventos)): ?>
                    <!-- Tabela de Eventos -->
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Data</th>
                                <th>Total de Inscritos</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eventos as $evento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($evento['NomeEvento']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($evento['DataInicioEvento'])); ?> - <?php echo date('d/m/Y', strtotime($evento['DataFimEvento'])); ?></td>
                                    <td><?php echo $evento['TotalInscritos']; ?></td>
                                    <td>
                                        <a href="/Eventosfaculdade/src/views/inscricoes/detalhar_inscricoes.php?evento_id=<?php echo $evento['EventoId']; ?>" class="btn btn-info btn-sm">Detalhar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <!-- Mensagem de Nenhum Evento -->
                    <div class="alert alert-warning text-center">
                        Nenhum evento encontrado.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Link para o Painel Administrativo -->
        <div class="text-center mt-4">
            <a href="/Eventosfaculdade/src/views/dashboard/admin.php" class="btn btn-secondary">Voltar ao Painel Administrativo</a>
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
