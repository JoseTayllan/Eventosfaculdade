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

    try {
        // Verifica se há inscrições vinculadas ao evento
        $sqlCheckInscricoes = "SELECT COUNT(*) FROM Inscricoes WHERE EventoId = :evento_id";
        $stmtCheck = $pdo->prepare($sqlCheckInscricoes);
        $stmtCheck->execute([':evento_id' => $eventoId]);
        $inscricoesCount = $stmtCheck->fetchColumn();

        if ($inscricoesCount > 0) {
            header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=evento_com_inscricoes");
            exit;
        }

        // Exclui o evento
        $sql = "DELETE FROM Eventos WHERE EventoId = :evento_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':evento_id' => $eventoId]);

        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?success=evento_excluido");
        exit;
    } catch (PDOException $e) {
        die("Erro ao excluir evento: " . $e->getMessage());
    }
}
?>
