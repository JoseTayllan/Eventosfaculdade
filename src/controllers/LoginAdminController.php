<?php
require_once '../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Consulta para verificar o administrador
        $sql = "SELECT * FROM Participantes WHERE EmailParticipante = :email AND TipoParticipante = 'Admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($senha, $admin['SenhaParticipante'])) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $admin['ParticipanteId'];
            $_SESSION['user_name'] = $admin['NomeParticipante'];
            $_SESSION['user_type'] = $admin['TipoParticipante'];
            header("Location: /Eventosfaculdade/src/views/dashboard/admin.php");
            exit;
        } else {
            // Credenciais inválidas
            header("Location: /Eventosfaculdade/src/views/admin/login.php?status=login_failed");
            exit;
        }
    } catch (PDOException $e) {
        // Erro no banco de dados
        header("Location: /Eventosfaculdade/src/views/admin/login.php?status=db_error");
        exit;
    }
}

// Redireciona caso o método não seja POST
header("Location: /Eventosfaculdade/src/views/admin/login.php");
exit;
?>
