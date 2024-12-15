<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

$eventoId = $_GET['evento_id'] ?? null;

if (!$eventoId) {
    header("Location: /Eventosfaculdade/src/views/inscricoes/listar_inscricoes.php?error=evento_nao_encontrado");
    exit;
}

// Busca os participantes inscritos no evento
try {
    $sql = "SELECT p.NomeParticipante, p.EmailParticipante, p.TipoParticipante, i.Compareceu
            FROM Inscricoes i
            JOIN Participantes p ON i.ParticipanteId = p.ParticipanteId
            WHERE i.EventoId = :evento_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':evento_id' => $eventoId]);
    $inscritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar inscrições: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes das Inscrições</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Detalhes das Inscrições</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($inscritos)): ?>
                    <!-- Tabela de Inscritos -->
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Compareceu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscritos as $inscrito): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($inscrito['NomeParticipante']); ?></td>
                                    <td><?php echo htmlspecialchars($inscrito['EmailParticipante']); ?></td>
                                    <td><?php echo htmlspecialchars($inscrito['TipoParticipante']); ?></td>
                                    <td>
                                        <?php
                                        if ($inscrito['Compareceu'] === null) {
                                            echo "<span class='text-muted'>Não registrado</span>";
                                        } elseif ($inscrito['Compareceu'] == 1) {
                                            echo "<span class='text-success'>Sim</span>";
                                        } else {
                                            echo "<span class='text-danger'>Não</span>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <!-- Mensagem de Nenhum Participante -->
                    <div class="alert alert-warning text-center">
                        Nenhum participante inscrito.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Link para Voltar -->
        <div class="text-center mt-4">
            <a href="/Eventosfaculdade/src/views/inscricoes/listar_inscricoes.php" class="btn btn-secondary">Voltar</a>
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
