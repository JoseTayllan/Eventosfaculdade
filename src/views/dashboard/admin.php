<?php
session_start();

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

// Configuração para expirar sessão após 15 minutos de inatividade
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <nav>
        <ul>
            <li><a href="/Eventosfaculdade/src/views/eventos/cadastrar.php">Cadastrar Evento</a></li>
            <li><a href="/Eventosfaculdade/src/views/eventos/listar.php">Gerenciar Eventos</a></li>
            <li><a href="/Eventosfaculdade/src/views/departamentos/cadastrar.php">Cadastrar Departamento</a></li>
            <li><a href="/Eventosfaculdade/src/views/presenca/listar_eventos.php">Validar Presenças</a></li>

        </ul>
    </nav>
    <a href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a>
</body>
</html>
