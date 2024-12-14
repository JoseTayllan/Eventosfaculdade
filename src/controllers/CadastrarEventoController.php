<?php
require_once '../../config/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $dataInicio = $_POST['data_inicio'];
    $dataFim = $_POST['data_fim'];
    $horarioInicio = $_POST['horario_inicio'];
    $horarioTermino = $_POST['horario_termino'];
    $local = $_POST['local'];
    $tipo = $_POST['tipo'];
    $departamentoId = $_POST['departamento'];
    $cargaHoraria = $_POST['carga_horaria'];
    $descricao = $_POST['descricao'];
    $palestranteId = $_POST['palestrante'];

    try {
        $sql = "INSERT INTO Eventos (NomeEvento, DataInicioEvento, DataFimEvento, HorarioInicio, HorarioTermino, LocalEvento, TipoEvento, DepartamentoEventoId, CargaHoraria, DescricaoEvento, PalestranteId)
                VALUES (:nome, :data_inicio, :data_fim, :horario_inicio, :horario_termino, :local, :tipo, :departamento, :carga_horaria, :descricao, :palestrante)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':data_inicio' => $dataInicio,
            ':data_fim' => $dataFim,
            ':horario_inicio' => $horarioInicio,
            ':horario_termino' => $horarioTermino,
            ':local' => $local,
            ':tipo' => $tipo,
            ':departamento' => $departamentoId,
            ':carga_horaria' => $cargaHoraria,
            ':descricao' => $descricao,
            ':palestrante' => $palestranteId
        ]);
        header("Location: /Eventosfaculdade/src/views/eventos/cadastro_sucesso.php");
        exit;        
    } catch (PDOException $e) {
        die("Erro ao cadastrar evento: " . $e->getMessage());
    }
}
?>
