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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white text-center py-3">
        <h1>Painel do Administrador</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5">
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
            <a href="/Eventosfaculdade/src/controllers/LogoutController.php" class="btn btn-danger">Sair</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5">
        <p class="m-0">&copy; 2024 Sistema de Eventos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
