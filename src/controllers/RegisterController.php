<?php
// src/controllers/RegisterController.php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace('/[^\d]/', '', $_POST['telefone']); // Remove parênteses, espaços e hífens
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $tipo = $_POST['tipo']; // Interno ou Externo
    $numeroMatricula = $tipo === 'Interno' ? $_POST['numero_matricula'] : null;
    $cpf = $tipo === 'Externo' ? $_POST['cpf'] : null;

    try {
        // Verificar duplicação de usuário (e-mail ou CPF já existe)
        $sql = "SELECT COUNT(*) FROM Participantes WHERE EmailParticipante = :email OR CPF = :cpf";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email, ':cpf' => $cpf]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: /Eventosfaculdade/src/views/usuarios/register_error.php?error=usuario_existente");
            exit;
        }

        // Inserir novo participante com o campo telefone
        $sql = "INSERT INTO Participantes (NomeParticipante, EmailParticipante, TelefoneParticipante, TipoParticipante, NumeroMatricula, CPF, SenhaParticipante)
                VALUES (:nome, :email, :telefone, :tipo, :numero_matricula, :cpf, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone, // Novo campo
            ':tipo' => $tipo,
            ':numero_matricula' => $numeroMatricula,
            ':cpf' => $cpf,
            ':senha' => $senha
        ]);

        // Redirecionar para a página de sucesso
        header("Location: /Eventosfaculdade/src/views/usuarios/register_success.php");
        exit;

    } catch (PDOException $e) {
        // Redirecionar para a página de erro com mensagem
        header("Location: /Eventosfaculdade/src/views/usuarios/register_error.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}


// Inscrição em evento (exemplo separado)
function verificarConflitoHorario($participanteId, $inicioNovoEvento, $fimNovoEvento) {
    global $pdo;
    $sql = "SELECT COUNT(*) FROM Inscricoes i
            JOIN Eventos e ON i.EventoId = e.EventoId
            WHERE i.ParticipanteId = :participanteId
            AND e.HorarioInicio < :fimNovoEvento
            AND e.HorarioTermino > :inicioNovoEvento";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':participanteId' => $participanteId,
        ':fimNovoEvento' => $fimNovoEvento,
        ':inicioNovoEvento' => $inicioNovoEvento
    ]);
    if ($stmt->fetchColumn() > 0) {
        die("Conflito de horário detectado! Não é possível se inscrever em dois eventos no mesmo horário.");
    }
}
?>
