<?php
require_once realpath(dirname(__DIR__) . '/config/database.php');



// Buscar eventos com palestrante
$sql = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, e.HorarioInicio, e.HorarioTermino, e.LocalEvento, e.TipoEvento, d.NomeDepartamento, p.NomeParticipante AS Palestrante
        FROM Eventos e
        JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
        JOIN Participantes p ON e.PalestranteId = p.ParticipanteId";
$stmt = $pdo->query($sql);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Ofertados</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <header>
        <h1>Sistema de Eventos Acadêmicos</h1>
        <nav>
            <ul>
                <li><a href="/Eventosfaculdade/src/views/usuarios/login.php">Login</a></li>
                <li><a href="/Eventosfaculdade/src/views/usuarios/cadastrar.php">Cadastrar-se</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Eventos Disponíveis</h2>
        <ul>
            <?php if (!empty($eventos)): ?>
                <?php foreach ($eventos as $evento): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($evento['NomeEvento']); ?></h3>
                        <p><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                        <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                        <p><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($evento['TipoEvento']); ?></p>
                        <p><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante']); ?></p>
                        <a href="/Eventosfaculdade/src/views/usuarios/login.php?redirect=inscricao&evento_id=<?php echo $evento['EventoId']; ?>">Inscrever-se</a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum evento disponível no momento.</p>
            <?php endif; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>
</body>
</html>
