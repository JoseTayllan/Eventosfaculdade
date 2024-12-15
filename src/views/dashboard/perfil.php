<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o aluno interno está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Interno') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

$alunoId = $_SESSION['user_id'] ?? null;
$mensagemSucesso = "";
$mensagemErro = "";

// Verifica se o ID do aluno está definido
if (!$alunoId) {
    echo "<p>Usuário não autenticado. Faça login novamente.</p>";
    exit;
}

// Processa atualização de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nova_senha']) && isset($_POST['confirma_senha'])) {
        $novaSenha = $_POST['nova_senha'];
        $confirmaSenha = $_POST['confirma_senha'];

        if ($novaSenha === $confirmaSenha) {
            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            $sqlAtualizaSenha = "UPDATE participantes SET SenhaParticipante = :senha WHERE ParticipanteId = :id";
            $stmtAtualizaSenha = $pdo->prepare($sqlAtualizaSenha);
            $stmtAtualizaSenha->execute([':senha' => $senhaHash, ':id' => $alunoId]);

            $mensagemSucesso = "Senha atualizada com sucesso!";
        } else {
            $mensagemErro = "As senhas não correspondem.";
        }
    } elseif (isset($_POST['nome']) && isset($_POST['matricula']) && isset($_POST['email'])) {
        $novoNome = $_POST['nome'];
        $novaMatricula = $_POST['matricula'];
        $novoEmail = $_POST['email'];

        $sqlAtualizaDados = "UPDATE participantes SET NomeParticipante = :nome, NumeroMatricula = :matricula, EmailParticipante = :email WHERE ParticipanteId = :id";
        $stmtAtualizaDados = $pdo->prepare($sqlAtualizaDados);
        $stmtAtualizaDados->execute([
            ':nome' => $novoNome,
            ':matricula' => $novaMatricula,
            ':email' => $novoEmail,
            ':id' => $alunoId
        ]);

        $mensagemSucesso = "Dados atualizados com sucesso!";
    } elseif (isset($_POST['delete_profile'])) {
        // Exclui o perfil do aluno interno
        $sqlDeletaPerfil = "DELETE FROM participantes WHERE ParticipanteId = :id";
        $stmtDeletaPerfil = $pdo->prepare($sqlDeletaPerfil);
        $stmtDeletaPerfil->execute([':id' => $alunoId]);

        session_destroy();
        header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
        exit;
    }
}

// Buscar informações do aluno
$sqlAluno = "SELECT NomeParticipante, NumeroMatricula, EmailParticipante FROM participantes WHERE ParticipanteId = :aluno_id";
$stmtAluno = $pdo->prepare($sqlAluno);
$stmtAluno->execute([':aluno_id' => $alunoId]);
$aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    echo "<p>Usuário não encontrado. Faça login novamente.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="bg-secondary text-white py-3">
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Logo no lado esquerdo -->
        <a href="/Eventosfaculdade">
            <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;">
        </a>
        <!-- Título e informações -->
        <div class="text-center flex-grow-1">
            <h1 class="m-0">Bem-vindo, <?php echo htmlspecialchars($aluno['NomeParticipante'] ?? 'Usuário'); ?>!</h1>
            <p class="m-0"><strong>Número de Matrícula:</strong> <?php echo htmlspecialchars($aluno['NumeroMatricula'] ?? 'Não informado'); ?></p>
        </div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-secondary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm me-2" href="/Eventosfaculdade/src/views/dashboard/interno.php">Meus cursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm" href="/Eventosfaculdade/public/index.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


    <!-- Conteúdo Principal -->
    <main class="container mt-5" style="padding-bottom: 80px;">
        <!-- Mensagem de Sucesso ou Erro -->
        <?php if (!empty($mensagemSucesso)): ?>
            <div class="alert alert-success text-center"><?php echo $mensagemSucesso; ?></div>
        <?php endif; ?>
        <?php if (!empty($mensagemErro)): ?>
            <div class="alert alert-danger text-center"><?php echo $mensagemErro; ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg mb-4">
                    <div class="card-body">
                        <h2 class="text-center mb-4 text-primary">Atualizar Informações</h2>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($aluno['NomeParticipante']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="matricula" class="form-label">Número de Matrícula</label>
                                <input type="text" id="matricula" name="matricula" class="form-control" value="<?php echo htmlspecialchars($aluno['NumeroMatricula']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($aluno['EmailParticipante']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Atualizar Dados</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-lg mb-4">
                    <div class="card-body">
                        <h2 class="text-center mb-4 text-primary">Atualizar Senha</h2>
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

                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="text-center mb-4 text-danger">Excluir Perfil</h2>
                        <form method="POST" action="" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.');">
                            <button type="submit" name="delete_profile" class="btn btn-danger w-100">Excluir Perfil</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5">
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
