<?php
require_once '../../../config/database.php';

try {
    // Buscar departamentos
    $sqlDepartamentos = "SELECT DepartamentoId, NomeDepartamento FROM Departamentos";
    $stmtDepartamentos = $pdo->query($sqlDepartamentos);
    $departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

    // Buscar palestrantes
    $sqlPalestrantes = "SELECT ParticipanteId, NomeParticipante FROM Participantes WHERE TipoParticipante = 'Admin'";
    $stmtPalestrantes = $pdo->query($sqlPalestrantes);
    $palestrantes = $stmtPalestrantes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Evento</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Cadastro de Evento</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Card do Formulário -->
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Preencha os Detalhes do Evento</h2>

                        <!-- Formulário -->
                        <form method="POST" action="/Eventosfaculdade/src/controllers/CadastrarEventoController.php" enctype="multipart/form-data">
                            <!-- Nome do Evento -->
                            <div class="mb-3">
                                <label for="nomeEvento" class="form-label">Nome do Evento</label>
                                <input type="text" id="nomeEvento" name="nome" class="form-control" required>
                            </div>

                            <!-- Data e Horários -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dataInicio" class="form-label">Data de Início</label>
                                    <input type="date" id="dataInicio" name="data_inicio" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dataFim" class="form-label">Data de Término</label>
                                    <input type="date" id="dataFim" name="data_fim" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="horarioInicio" class="form-label">Horário de Início</label>
                                    <input type="time" id="horarioInicio" name="horario_inicio" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="horarioTermino" class="form-label">Horário de Término</label>
                                    <input type="time" id="horarioTermino" name="horario_termino" class="form-control" required>
                                </div>
                            </div>

                            <!-- Local -->
                            <div class="mb-3">
                                <label for="local" class="form-label">Local</label>
                                <input type="text" id="local" name="local" class="form-control" required>
                            </div>

                            <!-- Departamento -->
                            <div class="mb-3">
                                <label for="departamento" class="form-label">Departamento</label>
                                <select id="departamento" name="departamento" class="form-select" required>
                                    <option value="">Selecione o Departamento</option>
                                    <?php foreach ($departamentos as $departamento): ?>
                                        <option value="<?php echo $departamento['DepartamentoId']; ?>">
                                            <?php echo htmlspecialchars($departamento['NomeDepartamento']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Palestrante -->
                            <div class="mb-3">
                                <label for="palestrante" class="form-label">Palestrante</label>
                                <select id="palestrante" name="palestrante" class="form-select">
                                    <option value="">Selecione um Palestrante Existente (Opcional)</option>
                                    <?php foreach ($palestrantes as $palestrante): ?>
                                        <option value="<?php echo $palestrante['ParticipanteId']; ?>">
                                            <?php echo htmlspecialchars($palestrante['NomeParticipante']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="palestranteManual" class="form-label">Ou Insira o Nome do Palestrante Manualmente</label>
                                <input type="text" id="palestranteManual" name="palestrante_manual" class="form-control">
                            </div>

                            <!-- Carga Horária -->
                            <div class="mb-3">
                                <label for="cargaHoraria" class="form-label">Carga Horária</label>
                                <input type="number" id="cargaHoraria" name="carga_horaria" class="form-control" required>
                            </div>

                            <!-- Descrição -->
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea id="descricao" name="descricao" class="form-control" rows="3" required></textarea>
                            </div>

                            <!-- Imagem Representativa -->
                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem Representativa</label>
                                <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
                            </div>

                            <!-- Vagas -->
                            <div class="mb-3">
                                <label for="vagas" class="form-label">Número de Vagas Disponíveis</label>
                                <input type="number" id="vagas" name="vagas_disponiveis" class="form-control" min="1" required>
                            </div>

                            <!-- Botão de Cadastro -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cadastrar Evento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5">
        <p class="m-0">&copy; 2024 Sistema de Eventos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
