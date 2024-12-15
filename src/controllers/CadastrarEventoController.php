<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do formulário
    $nome = $_POST['nome'];
    $dataInicio = $_POST['data_inicio'];
    $dataFim = $_POST['data_fim'];
    $horarioInicio = $_POST['horario_inicio'];
    $horarioTermino = $_POST['horario_termino'];
    $local = $_POST['local'];
    $departamento = $_POST['departamento'];
    $palestrante = $_POST['palestrante'];
    $cargaHoraria = $_POST['carga_horaria'];
    $descricao = $_POST['descricao'];
    $vagas = $_POST['vagas']; // Adicionado

    // Upload da imagem
    $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid('evento_', true) . '.' . $extensao;
        $caminhoImagem = '../../public/uploads/' . $nomeImagem;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
            $imagem = '/Eventosfaculdade/public/uploads/' . $nomeImagem;
        } else {
            die("Erro ao fazer o upload da imagem.");
        }
    }

    // Inserção no banco de dados
    try {
        $sql = "INSERT INTO Eventos 
                (NomeEvento, DataInicioEvento, DataFimEvento, HorarioInicio, HorarioTermino, LocalEvento, DepartamentoEventoId, 
                 PalestranteId, CargaHoraria, DescricaoEvento, ImagemEvento, VagasDisponiveis) 
                VALUES 
                (:nome, :dataInicio, :dataFim, :horarioInicio, :horarioTermino, :local, :departamento, :palestrante, 
                 :cargaHoraria, :descricao, :imagem, :vagas)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':dataInicio' => $dataInicio,
            ':dataFim' => $dataFim,
            ':horarioInicio' => $horarioInicio,
            ':horarioTermino' => $horarioTermino,
            ':local' => $local,
            ':departamento' => $departamento,
            ':palestrante' => $palestrante,
            ':cargaHoraria' => $cargaHoraria,
            ':descricao' => $descricao,
            ':imagem' => $imagem,
            ':vagas' => $vagas,
        ]);

        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?success=evento_cadastrado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao cadastrar evento: " . $e->getMessage());
    }
}
?>
