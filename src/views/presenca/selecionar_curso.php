<?php
session_start();
require_once '../../../config/database.php';

// Verifica o parâmetro evento_id
if (!isset($_GET['evento_id'])) {
    die("Evento não especificado.");
}

$eventoId = $_GET['evento_id'];

// Buscar cursos associados ao evento
$sqlCursos = "SELECT c.CursoId, c.NomeCurso
              FROM Cursos c
              JOIN CursosDepartamentos cd ON c.CursoId = cd.CursoId
              JOIN Eventos e ON cd.DepartamentoId = e.DepartamentoEventoId
              WHERE e.EventoId = :evento_id";
$stmtCursos = $pdo->prepare($sqlCursos);
$stmtCursos->execute([':evento_id' => $eventoId]);
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Curso</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
        <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body>
    <h1>Selecionar Curso</h1>
    <ul>
        <?php foreach ($cursos as $curso): ?>
            <li>
                <?php echo htmlspecialchars($curso['NomeCurso']); ?>
                <a href="/Eventosfaculdade/src/views/presenca/validar.php?evento_id=<?php echo $eventoId; ?>&curso_id=<?php echo $curso['CursoId']; ?>">Validar Presenças</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
