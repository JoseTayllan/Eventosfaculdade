<?php
require_once '../../config/database.php';
session_start();

// Verifica se o aluno está logado como Interno ou Externo
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['Interno', 'Externo'])) {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscricao_id'])) {
    $inscricaoId = $_POST['inscricao_id'];
    $alunoId = $_SESSION['user_id'];

    try {
        // Verifica se a inscrição pertence ao aluno logado
        $sqlCheck = "SELECT EventoId FROM Inscricoes WHERE InscricaoId = :inscricao_id AND ParticipanteId = :aluno_id";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([':inscricao_id' => $inscricaoId, ':aluno_id' => $alunoId]);

        if ($stmtCheck->rowCount() === 0) {
            $dashboard = ($_SESSION['user_type'] === 'Interno') ? 'interno.php' : 'externo.php';
            header("Location: /Eventosfaculdade/src/views/dashboard/$dashboard?status=error_permission");
            exit;
        }

        // Obtém o EventoId da inscrição
        $eventoId = $stmtCheck->fetchColumn();

        // Cancela a inscrição
        $sqlDelete = "DELETE FROM Inscricoes WHERE InscricaoId = :inscricao_id";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute([':inscricao_id' => $inscricaoId]);

        // Aumenta o número de vagas disponíveis para o evento
        $sqlAumentarVagas = "UPDATE Eventos SET VagasDisponiveis = VagasDisponiveis + 1 WHERE EventoId = :evento_id";
        $stmtAumentarVagas = $pdo->prepare($sqlAumentarVagas);
        $stmtAumentarVagas->execute([':evento_id' => $eventoId]);

        // Redireciona com mensagem de sucesso
        $dashboard = ($_SESSION['user_type'] === 'Interno') ? 'interno.php' : 'externo.php';
        header("Location: /Eventosfaculdade/src/views/dashboard/$dashboard?status=cancel_success");
        exit;

    } catch (PDOException $e) {
        // Redireciona com mensagem de erro
        $dashboard = ($_SESSION['user_type'] === 'Interno') ? 'interno.php' : 'externo.php';
        header("Location: /Eventosfaculdade/src/views/dashboard/$dashboard?status=error_db");
        exit;
    }
}
?>
