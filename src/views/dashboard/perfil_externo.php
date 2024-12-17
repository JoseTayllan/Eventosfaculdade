<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../../config/database.php';

// Variáveis de mensagens
$mensagemSucesso = '';
$mensagemErro = '';

// Verifica se o aluno externo está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Externo') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Obtém o ID do aluno da sessão
$alunoId = $_SESSION['user_id'] ?? null;

// Garante que o ID do aluno está definido
if (!$alunoId) {
    $mensagemErro = "Erro: usuário não autenticado. Faça login novamente.";
    exit;
}

// Função para buscar informações do aluno
function buscarDadosAluno($pdo, $alunoId) {
    $sqlAluno = "SELECT NomeParticipante, CPF, EmailParticipante, NumeroMatricula, TelefoneParticipante FROM Participantes WHERE ParticipanteId = :aluno_id";
    $stmtAluno = $pdo->prepare($sqlAluno);
    $stmtAluno->execute([':aluno_id' => $alunoId]);
    return $stmtAluno->fetch(PDO::FETCH_ASSOC);
}


// Busca inicial dos dados
$aluno = buscarDadosAluno($pdo, $alunoId);

if (!$aluno) {
    $mensagemErro = "Erro: usuário não encontrado.";
    exit;
}

$nome = htmlspecialchars($aluno['NomeParticipante'] ?? '');
$cpf = htmlspecialchars($aluno['CPF'] ?? '');
$email = htmlspecialchars($aluno['EmailParticipante'] ?? '');
$numeroMatricula = htmlspecialchars($aluno['NumeroMatricula'] ?? 'Não informado');

// Processa atualizações
if (isset($_POST['nome'], $_POST['cpf'], $_POST['email'], $_POST['telefone'])) {
    $novoNome = htmlspecialchars($_POST['nome']);
    $novoCpf = htmlspecialchars($_POST['cpf']);
    $novoEmail = htmlspecialchars($_POST['email']);
    $novoTelefone = htmlspecialchars($_POST['telefone']);

    $sqlUpdate = "UPDATE Participantes SET NomeParticipante = :nome, CPF = :cpf, EmailParticipante = :email, TelefoneParticipante = :telefone WHERE ParticipanteId = :aluno_id";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    if ($stmtUpdate->execute([
        ':nome' => $novoNome,
        ':cpf' => $novoCpf,
        ':email' => $novoEmail,
        ':telefone' => $novoTelefone,
        ':aluno_id' => $alunoId
    ])) {
        $mensagemSucesso = "Informações atualizadas com sucesso!";
        $aluno = buscarDadosAluno($pdo, $alunoId); // Refaz a consulta
    } else {
        $mensagemErro = "Erro ao atualizar informações.";
    }


    if (isset($_POST['nova_senha'], $_POST['confirma_senha'])) {
        $novaSenha = $_POST['nova_senha'];
        $confirmaSenha = $_POST['confirma_senha'];

        if ($novaSenha === $confirmaSenha) {
            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $sqlSenha = "UPDATE Participantes SET SenhaParticipante = :senha WHERE ParticipanteId = :aluno_id";
            $stmtSenha = $pdo->prepare($sqlSenha);

            if ($stmtSenha->execute([':senha' => $senhaHash, ':aluno_id' => $alunoId])) {
                $mensagemSucesso = "Senha atualizada com sucesso!";
            } else {
                $mensagemErro = "Erro ao atualizar senha.";
            }
        } else {
            $mensagemErro = "Erro: As senhas não correspondem!";
        }
    }

    // Excluir Perfil
    if (isset($_POST['delete_profile'])) {
        $sqlDelete = "DELETE FROM Participantes WHERE ParticipanteId = :aluno_id";
        $stmtDelete = $pdo->prepare($sqlDelete);

        if ($stmtDelete->execute([':aluno_id' => $alunoId])) {
            session_destroy();
            header("Location: /Eventosfaculdade/public/index.php");
            exit;
        } else {
            $mensagemErro = "Erro ao excluir o perfil.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
        <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body class="bg-light">
<header class="custom-ocean text-white py-3">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="/Eventosfaculdade">
            <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;">
        </a>
        <div class="text-center flex-grow-1">
            <h1 class="m-0">Bem-vindo, <?php echo $nome; ?>!</h1>
            <p class="m-0"><strong>Número de Matrícula:</strong> <?php echo $numeroMatricula; ?></p>
        </div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-ocean">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm me-2" href="/Eventosfaculdade/src/views/dashboard/externo.php">Meus Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm" href="/Eventosfaculdade/public/index.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container mt-4">
    <?php if ($mensagemSucesso): ?>
        <div class="alert alert-success text-center shadow-sm"> <?php echo $mensagemSucesso; ?> </div>
    <?php elseif ($mensagemErro): ?>
        <div class="alert alert-danger text-center shadow-sm"> <?php echo $mensagemErro; ?> </div>
    <?php endif; ?>
</div>

<main class="container">
    <div class="row justify-content-center">
        <!-- Atualizar Informações -->
        <div class="col-md-6">
            <div class="card shadow-lg mb-4">
                <div class="card-body">
                    <h2 class="text-center mb-4 text-primary">Atualizar Informações</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control" value="<?php echo $cpf; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($aluno['TelefoneParticipante'] ?? ''); ?>" required>
                        </div>

                        <button class="btn custom-ocean w-100">Atualizar Dados</button>
                    </form>
                </div>
            </div>

            <!-- Atualizar Senha -->
            <div class="card shadow-lg mb-4">
                <div class="card-body">
                    <h2 class="text-center mb-4 text-primary">Atualizar Senha</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input type="password" name="nova_senha" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Senha</label>
                            <input type="password" name="confirma_senha" class="form-control" required>
                        </div>
                        <button class="btn custom-ocean w-100">Atualizar Senha</button>
                    </form>
                </div>
            </div>

            <!-- Excluir Perfil -->
            <div class="card shadow-lg">
                <div class="card-body">
                    <h2 class="text-center mb-4 text-danger">Excluir Perfil</h2>
                    <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.');">
                        <button class="btn btn-danger w-100" name="delete_profile">Excluir Perfil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="custom-ocean text-white text-center py-3 mt-5">
    <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
</footer>
<script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
