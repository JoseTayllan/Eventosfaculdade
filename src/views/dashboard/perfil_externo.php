<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o aluno externo está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Externo') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Obtém o ID do aluno da sessão
$alunoId = $_SESSION['user_id'] ?? null;

// Garante que o ID do aluno está definido
if (!$alunoId) {
    echo "<p>Erro: usuário não autenticado. Faça login novamente.</p>";
    exit;
}

// Buscar informações do aluno no banco de dados
$sqlAluno = "SELECT NomeParticipante, CPF, EmailParticipante FROM participantes WHERE ParticipanteId = :aluno_id";
$stmtAluno = $pdo->prepare($sqlAluno);
$stmtAluno->execute([':aluno_id' => $alunoId]);
$aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);

// Inicializa as variáveis com valores padrão
$nome = htmlspecialchars($aluno['NomeParticipante'] ?? '');
$cpf = htmlspecialchars($aluno['CPF'] ?? '');
$email = htmlspecialchars($aluno['EmailParticipante'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="bg-secondary text-white text-center py-3">
        <h1>Bem-vindo, <?php echo $nome; ?>!</h1>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/Eventosfaculdade/src/views/dashboard/externo.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mt-5">
        <!-- Atualizar Informações -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-center text-primary mb-4">Atualizar Informações</h2>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $nome; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF (somente números)</label>
                                <input type="text" id="cpf" name="cpf" class="form-control" maxlength="11" value="<?php echo $cpf; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Atualizar Dados</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atualizar Senha -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-center text-primary mb-4">Atualizar Senha</h2>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nova_senha" class="form-label">Nova Senha</label>
                                <input type="password" id="nova_senha" name="nova_senha" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" id="confirma_senha" name="confirma_senha" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Atualizar Senha</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Excluir Perfil -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-center text-danger mb-4">Excluir Perfil</h2>
                        <form method="POST" action="" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.');">
                            <button type="submit" name="delete_profile" class="btn btn-danger w-100">Excluir Perfil</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>

    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
