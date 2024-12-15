<?php
require_once '../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $dataInicio = $_POST['data_inicio'];
    $dataFim = $_POST['data_fim'];
    $horarioInicio = $_POST['horario_inicio'];
    $horarioTermino = $_POST['horario_termino'];
    $local = $_POST['local'];
    $departamento = $_POST['departamento'];
    $palestranteId = $_POST['palestrante']; // ID do palestrante existente
    $palestranteManual = $_POST['palestrante_manual']; // Nome do palestrante inserido manualmente
    $cargaHoraria = $_POST['carga_horaria'];
    $descricao = $_POST['descricao'];
    $vagasDisponiveis = $_POST['vagas_disponiveis'] ?? null;

    // Validação das vagas
    if ($vagasDisponiveis === null || !is_numeric($vagasDisponiveis)) {
        die("Erro: O campo 'Vagas Disponíveis' é obrigatório e deve conter um valor numérico.");
    }

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

    try {
        // Define o palestrante
        if (empty($palestranteId) && !empty($palestranteManual)) {
            $palestranteFinal = $palestranteManual;
        } elseif (!empty($palestranteId)) {
            $stmt = $pdo->prepare("SELECT NomeParticipante FROM Participantes WHERE ParticipanteId = :id");
            $stmt->execute([':id' => $palestranteId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $palestranteFinal = $result['NomeParticipante'] ?? 'Palestrante Não Informado';
        } else {
            $palestranteFinal = 'Palestrante Não Informado';
        }

        // Inserção no banco de dados
        $sql = "INSERT INTO Eventos 
                (NomeEvento, DataInicioEvento, DataFimEvento, HorarioInicio, HorarioTermino, LocalEvento, 
                 DepartamentoEventoId, Palestrante, CargaHoraria, DescricaoEvento, ImagemEvento, VagasDisponiveis) 
                VALUES 
                (:nome, :dataInicio, :dataFim, :horarioInicio, :horarioTermino, :local, 
                 :departamento, :palestrante, :cargaHoraria, :descricao, :imagem, :vagasDisponiveis)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':dataInicio' => $dataInicio,
            ':dataFim' => $dataFim,
            ':horarioInicio' => $horarioInicio,
            ':horarioTermino' => $horarioTermino,
            ':local' => $local,
            ':departamento' => $departamento,
            ':palestrante' => $palestranteFinal,
            ':cargaHoraria' => $cargaHoraria,
            ':descricao' => $descricao,
            ':imagem' => $imagem,
            ':vagasDisponiveis' => $vagasDisponiveis,
        ]);

        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?success=evento_cadastrado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao cadastrar evento: " . $e->getMessage());
    }
}
