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
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
        <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Painel do Administrador</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <h2 class="text-center mb-4">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>

        <!-- Exibição de mensagens de feedback -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'evento_excluido'): ?>
            <div class="alert alert-success text-center">
                Evento excluído com sucesso!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'evento_com_inscricoes'): ?>
            <div class="alert alert-danger text-center">
                Erro: Este evento possui inscrições vinculadas e não pode ser excluído.
            </div>
        <?php endif; ?>

        <!-- Navegação -->
        <nav class="mb-4">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/eventos/cadastrar.php" class="text-decoration-none">Cadastrar Evento</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/eventos/listar.php" class="text-decoration-none">Gerenciar Eventos</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/departamentos/cadastrar.php" class="text-decoration-none">Cadastrar Departamento</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/departamentos/excluir.php" class="text-decoration-none">Excluir Departamento</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/presenca/listar_eventos.php" class="text-decoration-none">Validar Presenças</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/inscricoes/listar_inscricoes.php" class="text-decoration-none">Acompanhar Inscrições</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/presenca/resumo_presencas.php" class="text-decoration-none">Resumo de Presenças</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/inscricoes/filtrar_inscricoes.php" class="text-decoration-none">Filtrar Inscrições</a>
                </li>
                <li class="list-group-item">
                    <a href="/Eventosfaculdade/src/views/banners/gerenciar_banners.php" class="text-decoration-none">Gerenciar Banners</a> <!-- Novo Link -->
                </li>
            </ul>
        </nav>

        <!-- Botão de Logout -->
        <div class="text-center">
            <a href="/Eventosfaculdade/public/index.php" class="btn btn-danger">Sair</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="custom-ocean text-white text-center py-3 mt-5 fixed-bottom">
    <p class="m-0">&copy; 2024 Sistema de Eventos</p>
</footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
