<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o parâmetro evento_id foi passado
if (!isset($_GET['evento_id'])) {
    die("Evento não especificado.");
}

$eventoId = $_GET['evento_id'];

// Buscar alunos inscritos no evento
$sql = "SELECT p.ParticipanteId, p.NomeParticipante, i.Compareceu
        FROM Inscricoes i
        JOIN Participantes p ON i.ParticipanteId = p.ParticipanteId
        WHERE i.EventoId = :evento_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':evento_id' => $eventoId]);
$inscritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Presença</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Validar Presença - Evento</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        <form method="POST" action="/Eventosfaculdade/src/controllers/SalvarPresencaController.php">
            <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($eventoId); ?>">

            <!-- Tabela de Participantes -->
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nome do Participante</th>
                        <th>Presente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inscritos)): ?>
                        <?php foreach ($inscritos as $inscrito): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inscrito['NomeParticipante']); ?></td>
                                <td class="text-center">
                                    <input type="checkbox" name="presenca[<?php echo $inscrito['ParticipanteId']; ?>]" <?php echo $inscrito['Compareceu'] ? 'checked' : ''; ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">Nenhum participante inscrito neste evento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Botão para Salvar Presenças -->
            <?php if (!empty($inscritos)): ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Salvar Presenças</button>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5 fixed-bottom">
        <p class="m-0">&copy; 2024 Sistema de Eventos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
