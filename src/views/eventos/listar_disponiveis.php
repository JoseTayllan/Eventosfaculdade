<?php
session_start();
require_once '../../../config/database.php';

$alunoId = $_SESSION['user_id'] ?? null;

if (isset($_GET['inscrever']) && $alunoId) {
    $eventoId = $_GET['inscrever'];

    try {
        $sql = "INSERT INTO Inscricoes (EventoId, ParticipanteId) VALUES (:evento_id, :aluno_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':evento_id' => $eventoId, ':aluno_id' => $alunoId]);

        header("Location: /Eventosfaculdade/src/views/dashboard/aluno.php?success=1");
        exit;
    } catch (PDOException $e) {
        $error = "Erro ao processar inscrição: " . $e->getMessage();
    }
}
