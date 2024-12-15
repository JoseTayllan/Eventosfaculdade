<?php
require_once '../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id'])) {
    $eventoId = $_POST['evento_id'];
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
    $vagasDisponiveis = $_POST['vagas_disponiveis'];

    try {
        // Inicializa o valor da imagem como null
        $novaImagem = null;

        // Verifica se uma nova imagem foi enviada
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nomeImagem = uniqid('evento_', true) . '.' . $extensao;
            $caminhoImagem = '../../public/uploads/' . $nomeImagem;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
                $novaImagem = '/Eventosfaculdade/public/uploads/' . $nomeImagem;
            } else {
                die("Erro ao fazer o upload da nova imagem.");
            }
        }

        // Atualiza os dados do evento
        $sql = "UPDATE Eventos 
                SET NomeEvento = :nome, 
                    DataInicioEvento = :dataInicio, 
                    DataFimEvento = :dataFim, 
                    HorarioInicio = :horarioInicio, 
                    HorarioTermino = :horarioTermino, 
                    LocalEvento = :local, 
                    DepartamentoEventoId = :departamento, 
                    PalestranteId = :palestrante, 
                    CargaHoraria = :cargaHoraria, 
                    DescricaoEvento = :descricao, 
                    VagasDisponiveis = :vagasDisponiveis";

        // Se uma nova imagem foi enviada, inclui no SQL
        if ($novaImagem) {
            $sql .= ", ImagemEvento = :novaImagem";
        }

        $sql .= " WHERE EventoId = :evento_id";

        $params = [
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
            ':vagasDisponiveis' => $vagasDisponiveis,
            ':evento_id' => $eventoId,
        ];

        if ($novaImagem) {
            $params[':novaImagem'] = $novaImagem;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?success=evento_editado");
        exit;
    } catch (PDOException $e) {
        die("Erro ao editar evento: " . $e->getMessage());
    }
}
?>
