<?php
// src/controllers/LoginController.php
require_once '../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Buscar usuário pelo e-mail
        $sql = "SELECT * FROM Participantes WHERE EmailParticipante = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['SenhaParticipante'])) {
            // Autenticação bem-sucedida
            $_SESSION['user_id'] = $usuario['ParticipanteId'];
            $_SESSION['user_name'] = $usuario['NomeParticipante'];
            $_SESSION['user_type'] = $usuario['TipoParticipante'];

            // Redirecionar dependendo do tipo de usuário
            if ($usuario['TipoParticipante'] === 'Interno') {
                header("Location: /Eventosfaculdade/src/views/dashboard/interno.php");
            } else {
                header("Location: /Eventosfaculdade/src/views/dashboard/externo.php");
            }
            exit;
        } else {
            header("Location: /Eventosfaculdade/src/views/usuarios/login.php?error=Credenciais inválidas.");
            exit;
        }
    } catch (PDOException $e) {
        die("Erro ao processar login: " . $e->getMessage());
    }
}
?>
