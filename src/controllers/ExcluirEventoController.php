<?php
require_once '../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id'], $_POST['senha_especial'])) {
    $eventoId = $_POST['evento_id'];
    $senhaEspecial = $_POST['senha_especial'];

    try {
        // Buscar a senha especial no banco
        $sqlSenhaEspecial = "SELECT Valor FROM Configuracoes WHERE Chave = 'senha_especial'";
        $stmtSenha = $pdo->query($sqlSenhaEspecial);
        $senhaEspecialHash = $stmtSenha->fetchColumn();

        // Verifica se a senha fornecida está correta
        if (!$senhaEspecialHash || !password_verify($senhaEspecial, $senhaEspecialHash)) {
            header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=senha_incorreta");
            exit;
        }

        // Verifica se há inscrições vinculadas ao evento
        $sqlCheckInscricoes = "SELECT COUNT(*) FROM Inscricoes WHERE EventoId = :evento_id";
        $stmtCheck = $pdo->prepare($sqlCheckInscricoes);
        $stmtCheck->execute([':evento_id' => $eventoId]);
        $inscricoesCount = $stmtCheck->fetchColumn();

        if ($inscricoesCount > 0) {
            // Exclui as inscrições vinculadas ao evento
            $sqlDeleteInscricoes = "DELETE FROM Inscricoes WHERE EventoId = :evento_id";
            $stmtDeleteInscricoes = $pdo->prepare($sqlDeleteInscricoes);
            $stmtDeleteInscricoes->execute([':evento_id' => $eventoId]);
        }

        // Exclui o evento
        $sqlDeleteEvento = "DELETE FROM Eventos WHERE EventoId = :evento_id";
        $stmtDeleteEvento = $pdo->prepare($sqlDeleteEvento);
        $stmtDeleteEvento->execute([':evento_id' => $eventoId]);

        // Redireciona com sucesso
        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?success=evento_excluido");
        exit;

    } catch (PDOException $e) {
        // Redireciona em caso de erro
        header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=erro_exclusao");
        exit;
    }
} else {
    header("Location: /Eventosfaculdade/src/views/dashboard/admin.php?error=dados_invalidos");
    exit;
}
?>
