<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o evento_id foi enviado
    if (!isset($_POST['evento_id'])) {
        die("Evento não especificado.");
    }

    $eventoId = $_POST['evento_id'];
    $presencas = $_POST['presenca'] ?? [];

    try {
        // Atualizar presença para os participantes marcados
        $sql = "UPDATE Inscricoes SET Compareceu = :compareceu WHERE EventoId = :evento_id AND ParticipanteId = :participante_id";
        $stmt = $pdo->prepare($sql);

        foreach ($presencas as $participanteId => $value) {
            $stmt->execute([
                ':compareceu' => 1, // Presente
                ':evento_id' => $eventoId,
                ':participante_id' => $participanteId
            ]);
        }

        // Marcar ausentes os que não foram selecionados
        $sqlAusentes = "UPDATE Inscricoes SET Compareceu = 0 WHERE EventoId = :evento_id AND ParticipanteId NOT IN (" . implode(',', array_keys($presencas)) . ")";
        $stmtAusentes = $pdo->prepare($sqlAusentes);
        $stmtAusentes->execute([':evento_id' => $eventoId]);

        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar presenças: " . $e->getMessage());
    }
}
?>
