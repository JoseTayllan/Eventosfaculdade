<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

// Consulta para obter o resumo de presenças por evento
try {
    $sql = "SELECT 
                e.NomeEvento,
                COUNT(CASE WHEN i.Compareceu = 1 THEN 1 END) AS TotalPresentes,
                COUNT(CASE WHEN i.Compareceu = 0 THEN 1 END) AS TotalAusentes,
                COUNT(i.InscricaoId) AS TotalInscritos
            FROM Eventos e
            LEFT JOIN Inscricoes i ON e.EventoId = i.EventoId
            GROUP BY e.EventoId";
    $stmt = $pdo->query($sql);
    $resumo = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar resumo de presenças: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo Geral de Presenças</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
        <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Resumo Geral de Presenças</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($resumo)): ?>
                    <!-- Tabela de Resumo -->
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Total Inscritos</th>
                                <th>Total Presentes</th>
                                <th>Total Ausentes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resumo as $evento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($evento['NomeEvento']); ?></td>
                                    <td><?php echo $evento['TotalInscritos']; ?></td>
                                    <td><?php echo $evento['TotalPresentes']; ?></td>
                                    <td><?php echo $evento['TotalAusentes']; ?></td>
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

        <!-- Botão para Voltar -->
        <div class="text-center mt-4">
            <a href="/Eventosfaculdade/src/views/dashboard/admin.php" class="btn btn-secondary">Voltar ao Painel Administrativo</a>
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
