<?php
require_once '../../config/database.php';
session_start();

// Configura o fuso horário para garantir consistência
date_default_timezone_set('America/Sao_Paulo');

// Verifica se o aluno está logado
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'Interno' && $_SESSION['user_type'] !== 'Externo')) {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id'])) {
    $eventoId = $_POST['evento_id'];
    $alunoId = $_SESSION['user_id'];

    try {
        // Utiliza a conexão definida no database.php
        global $pdo;

        // Verifica o evento
        $stmt = $pdo->prepare("SELECT DataInicioEvento, HorarioInicio, VagasDisponiveis FROM Eventos WHERE EventoId = ?");
        $stmt->execute([$eventoId]);
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$evento) {
            echo "Evento não encontrado.";
            exit;
        }

        // Verifica se há vagas disponíveis
        if ($evento['VagasDisponiveis'] <= 0) {
            header("Location: /Eventosfaculdade/src/views/dashboard/eventos_disponiveis.php?status=full");
            exit;

        }

        // Extrai e valida os dados de data e hora
        $dataInicio = $evento['DataInicioEvento'];
        $horaInicio = $evento['HorarioInicio'];

        if (empty($dataInicio) || empty($horaInicio)) {
            echo "Data ou hora do evento não estão definidas corretamente.";
            exit;
        }

        // Combina data e hora de forma explícita
        $dataHoraInicio = new DateTime($dataInicio, new DateTimeZone('America/Sao_Paulo'));
        $horaInicioParts = explode(':', $horaInicio);
        $dataHoraInicio->setTime((int)$horaInicioParts[0], (int)$horaInicioParts[1], (int)$horaInicioParts[2]);

        $dataHoraAtual = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $intervaloSegundos = $dataHoraInicio->getTimestamp() - $dataHoraAtual->getTimestamp();

        // Verifica se faltam menos de 5 minutos para o evento
        if ($intervaloSegundos <= 0 || $intervaloSegundos < 300) {
            header("Location: /Eventosfaculdade/src/views/dashboard/eventos_disponiveis.php?status=closed");
            exit;
        }

        // Verifica se o aluno já está inscrito
        $stmt = $pdo->prepare("SELECT * FROM Inscricoes WHERE EventoId = ? AND ParticipanteId = ?");
        $stmt->execute([$eventoId, $alunoId]);
        $inscricao = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($inscricao) {
            echo "Você já está inscrito neste evento.";
            exit;
        }

        // Realiza a inscrição
        $stmt = $pdo->prepare("INSERT INTO Inscricoes (EventoId, ParticipanteId) VALUES (?, ?)");
        $stmt->execute([$eventoId, $alunoId]);

        // Reduz o número de vagas disponíveis
        $stmt = $pdo->prepare("UPDATE Eventos SET VagasDisponiveis = VagasDisponiveis - 1 WHERE EventoId = ?");
        $stmt->execute([$eventoId]);

        header("Location: /Eventosfaculdade/src/views/dashboard/eventos_disponiveis.php?status=success");
        exit;

    } catch (Exception $e) {
        header("Location: /Eventosfaculdade/src/views/dashboard/eventos_disponiveis.php?status=cancel_success");
        exit;

    }
} else {
    echo "Requisição inválida.";
}
?>
