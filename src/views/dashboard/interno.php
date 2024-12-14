<?php
session_start();

// Verifica se o usuário está autenticado e se é do tipo Interno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Interno') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Configuração para expirar sessão após 15 minutos de inatividade
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Interno</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>Este é o painel de gerenciamento para usuários internos.</p>
    <a href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a>
</body>
</html>
