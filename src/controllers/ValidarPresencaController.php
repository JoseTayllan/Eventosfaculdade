<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica se os parâmetros necessários estão presentes
    if (!isset($_GET['evento_id']) || !isset($_GET['curso_id'])) {
        die("Evento ou curso não especificado.");
    }

    $eventoId = $_GET['evento_id'];
    $cursoId = $_GET['curso_id'];

    // Buscar inscritos no curso e evento
    $sql = "SELECT p.ParticipanteId, p.NomeParticipante, i.Compareceu
            FROM Inscricoes i
            JOIN Participantes p ON i.ParticipanteId = p.ParticipanteId
            WHERE i.EventoId = :evento_id AND p.CursoId = :curso_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':evento_id' => $eventoId, ':curso_id' => $cursoId]);
    $inscritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os inscritos como um array
    echo json_encode($inscritos);
}
