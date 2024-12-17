<?php
require_once '../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    try {
        // Verifica se o e-mail já existe
        $sqlCheck = "SELECT COUNT(*) FROM Participantes WHERE EmailParticipante = :email";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([':email' => $email]);
        $emailExiste = $stmtCheck->fetchColumn();

        if ($emailExiste > 0) {
            header("Location: /Eventosfaculdade/src/views/admin/cadastrar.php?status=email_ja_existe");
            exit;
        }

        // Insere o novo administrador no banco de dados
        $sqlInsert = "INSERT INTO Participantes (NomeParticipante, EmailParticipante, SenhaParticipante, TipoParticipante) 
                      VALUES (:nome, :email, :senha, 'Admin')";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
        ]);

        header("Location: /Eventosfaculdade/src/views/admin/cadastrar.php?status=success");
        exit;

    } catch (PDOException $e) {
        header("Location: /Eventosfaculdade/src/views/admin/cadastrar.php?status=error");
        exit;
    }
}
?>