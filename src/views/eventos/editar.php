<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Verifica se o evento existe
$eventoId = $_GET['evento_id'] ?? null;
if (!$eventoId) {
    header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=evento_nao_encontrado");
    exit;
}

try {
    $sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, 
                   e.LocalEvento, e.CargaHoraria, e.DescricaoEvento, e.ImagemEvento, e.VagasDisponiveis, 
                   e.Palestrante AS PalestranteManual, d.DepartamentoId, d.NomeDepartamento, p.ParticipanteId, p.NomeParticipante 
            FROM Eventos e
            LEFT JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
            LEFT JOIN Participantes p ON e.Palestrante = p.ParticipanteId
            WHERE e.EventoId = :evento_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':evento_id' => $eventoId]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=evento_nao_encontrado");
        exit;
    }

    // Buscar todos os departamentos e palestrantes
    $sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
    $stmtDepartamentos = $pdo->query($sqlDepartamentos);
    $departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

    $sqlPalestrantes = "SELECT ParticipanteId, NomeParticipante FROM Participantes WHERE TipoParticipante = 'Admin'";
    $stmtPalestrantes = $pdo->query($sqlPalestrantes);
    $palestrantes = $stmtPalestrantes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar dados do evento: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white text-center py-3">
        <h1>Editar Evento</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        <form method="POST" action="/Eventosfaculdade/src/controllers/EditarEventoController.php" enctype="multipart/form-data">
            <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($evento['EventoId']); ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nomeEvento" class="form-label">Nome do Evento:</label>
                    <input type="text" id="nomeEvento" name="nome" class="form-control" value="<?php echo htmlspecialchars($evento['NomeEvento']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Data de Início:</label>
                    <input type="date" id="dataInicio" name="data_inicio" class="form-control" value="<?php echo htmlspecialchars($evento['DataInicioEvento']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="dataFim" class="form-label">Data de Término:</label>
                    <input type="date" id="dataFim" name="data_fim" class="form-control" value="<?php echo htmlspecialchars($evento['DataFimEvento']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="horarioInicio" class="form-label">Horário de Início:</label>
                    <input type="time" id="horarioInicio" name="horario_inicio" class="form-control" value="<?php echo htmlspecialchars($evento['HorarioInicio']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="horarioTermino" class="form-label">Horário de Término:</label>
                    <input type="time" id="horarioTermino" name="horario_termino" class="form-control" value="<?php echo htmlspecialchars($evento['HorarioTermino']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="local" class="form-label">Local:</label>
                    <input type="text" id="local" name="local" class="form-control" value="<?php echo htmlspecialchars($evento['LocalEvento']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="vagasDisponiveis" class="form-label">Vagas Disponíveis:</label>
                    <input type="number" id="vagasDisponiveis" name="vagas_disponiveis" class="form-control" value="<?php echo htmlspecialchars($evento['VagasDisponiveis']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="departamento" class="form-label">Departamento:</label>
                    <select id="departamento" name="departamento" class="form-select" required>
                        <option value="">Selecione o Departamento</option>
                        <?php foreach ($departamentos as $departamento): ?>
                            <option value="<?php echo $departamento['DepartamentoId']; ?>" <?php echo $evento['DepartamentoId'] == $departamento['DepartamentoId'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="palestrante" class="form-label">Palestrante (Selecionar):</label>
                    <select id="palestrante" name="palestrante" class="form-select">
                        <option value="">Selecione o Palestrante</option>
                        <?php foreach ($palestrantes as $palestrante): ?>
                            <option value="<?php echo $palestrante['ParticipanteId']; ?>" <?php echo $evento['ParticipanteId'] == $palestrante['ParticipanteId'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($palestrante['NomeParticipante']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="palestrante_manual" class="form-label">Palestrante (Inserir Manualmente):</label>
                    <input type="text" id="palestrante_manual" name="palestrante_manual" class="form-control" placeholder="Digite o nome do palestrante" value="<?php echo htmlspecialchars($evento['PalestranteManual'] ?? ''); ?>">
                </div>

                <div class="col-md-12">
                    <label for="imagem" class="form-label">Imagem Atual:</label>
                    <?php if (!empty($evento['ImagemEvento'])): ?>
                        <img src="<?php echo htmlspecialchars($evento['ImagemEvento']); ?>" alt="Imagem Atual" class="img-thumbnail" style="width: 100px; height: auto;">
                    <?php endif; ?>
                    <input type="file" id="imagem" name="imagem" class="form-control mt-2">
                </div>
                <div class="col-md-12">
                    <label for="descricao" class="form-label">Descrição:</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="4" required><?php echo htmlspecialchars($evento['DescricaoEvento']); ?></textarea>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary mt-4">Salvar Alterações</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5">
        <p class="m-0">&copy; 2024 Sistema de Eventos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
