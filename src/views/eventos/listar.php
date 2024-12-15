<?php
require_once '../../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Buscar todos os eventos
try {
    $sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino,
                   e.LocalEvento, e.CargaHoraria, e.DescricaoEvento, e.ImagemEvento, e.VagasDisponiveis, d.NomeDepartamento, 
                   COALESCE(e.Palestrante, p.NomeParticipante) AS Palestrante
            FROM Eventos e
            LEFT JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
            LEFT JOIN Participantes p ON e.PalestranteId = p.ParticipanteId";
    $stmt = $pdo->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar eventos: " . $e->getMessage());
}

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Eventos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Eventos Cadastrados</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <?php if (!empty($eventos)): ?>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Imagem</th>
                        <th>Nome do Evento</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Local</th>
                        <th>Departamento</th>
                        <th>Palestrante</th>
                        <th>Carga Horária</th>
                        <th>Descrição</th>
                        <th>Vagas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                        <tr>
                            <td>
                                <?php if (!empty($evento['ImagemEvento'])): ?>
                                    <img src="<?php echo htmlspecialchars($evento['ImagemEvento'] ?? ''); ?>" alt="Imagem do Evento" class="img-thumbnail" style="width: 100px; height: auto;">
                                <?php else: ?>
                                    <span class="text-muted">Sem imagem</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($evento['NomeEvento'] ?? ''); ?></td>
                            <td><?php echo formatarData($evento['DataInicioEvento']) . ' a ' . formatarData($evento['DataFimEvento']); ?></td>
                            <td><?php echo htmlspecialchars($evento['HorarioInicio'] ?? '') . ' - ' . htmlspecialchars($evento['HorarioTermino'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($evento['LocalEvento'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($evento['NomeDepartamento'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></td>
                            <td><?php echo htmlspecialchars($evento['CargaHoraria'] ?? '') . ' horas'; ?></td>
                            <td><?php echo htmlspecialchars($evento['DescricaoEvento'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($evento['VagasDisponiveis'] ?? ''); ?></td>
                            <td>
                                <a href="/Eventosfaculdade/src/views/eventos/editar.php?evento_id=<?php echo $evento['EventoId']; ?>" class="btn btn-warning btn-sm mb-1">Editar</a>
                                <form method="POST" action="/Eventosfaculdade/src/controllers/ExcluirEventoController.php" style="display:inline;">
                                    <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este evento?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Nenhum evento cadastrado.
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5">
        <p class="m-0">&copy; 2024 Sistema de Eventos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
