<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o aluno externo está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Externo') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

$alunoId = $_SESSION['user_id'] ?? null;
$mensagemSucesso = "";
$mensagemErro = "";

// Processa atualização de dados ou exclusão de perfil
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
    } elseif (isset($_POST['nome']) && isset($_POST['cpf']) && isset($_POST['email'])) {
        $novoNome = $_POST['nome'];
        $novoCPF = $_POST['cpf'];
        $novoEmail = $_POST['email'];

        if (preg_match('/^\d{11}$/', $novoCPF)) {
            $sqlAtualizaDados = "UPDATE participantes SET NomeParticipante = :nome, CPF = :cpf, EmailParticipante = :email WHERE ParticipanteId = :id";
            $stmtAtualizaDados = $pdo->prepare($sqlAtualizaDados);
            $stmtAtualizaDados->execute([
                ':nome' => $novoNome,
                ':cpf' => $novoCPF,
                ':email' => $novoEmail,
                ':id' => $alunoId
            ]);

            $mensagemSucesso = "Dados atualizados com sucesso!";
        } else {
            $mensagemErro = "O CPF deve conter exatamente 11 números.";
        }
    } elseif (isset($_POST['delete_profile'])) {
        // Exclui o perfil do aluno externo
        $sqlDeletaPerfil = "DELETE FROM participantes WHERE ParticipanteId = :id";
        $stmtDeletaPerfil = $pdo->prepare($sqlDeletaPerfil);
        $stmtDeletaPerfil->execute([':id' => $alunoId]);

        session_destroy();
        header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
        exit;
    }
}

// Buscar informações do aluno
$sqlAluno = "SELECT NomeParticipante, CPF, EmailParticipante FROM participantes WHERE ParticipanteId = :aluno_id";
$stmtAluno = $pdo->prepare($sqlAluno);
$stmtAluno->execute([':aluno_id' => $alunoId]);
$aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    echo "Usuário não encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <header>
        <h1>Bem-vindo, <?php echo htmlspecialchars($aluno['NomeParticipante'] ?? 'Usuário'); ?>!</h1>
        <nav>
            <ul>
                <li><a href="/Eventosfaculdade/src/views/dashboard/externo.php">Dashboard</a></li>
                <li><a href="/Eventosfaculdade/src/controllers/LogoutController.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Mensagem de sucesso ou erro -->
        <?php if (!empty($mensagemSucesso)): ?>
            <p style="color: green;"> <?php echo $mensagemSucesso; ?> </p>
        <?php endif; ?>
        <?php if (!empty($mensagemErro)): ?>
            <p style="color: red;"> <?php echo $mensagemErro; ?> </p>
        <?php endif; ?>

        <h2>Atualizar Informações</h2>
        <form method="POST" action="">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($aluno['NomeParticipante']); ?>" required>
            <br>
            <label for="cpf">CPF (somente números):</label>
            <input type="text" id="cpf" name="cpf" maxlength="11" value="<?php echo htmlspecialchars($aluno['CPF']); ?>" required>
            <br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($aluno['EmailParticipante']); ?>" required>
            <br>
            <button type="submit">Atualizar Dados</button>
        </form>

        <h2>Atualizar Senha</h2>
        <form method="POST" action="">
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha">
            <br>
            <label for="confirma_senha">Confirmar Nova Senha:</label>
            <input type="password" id="confirma_senha" name="confirma_senha">
            <br>
            <button type="submit">Atualizar Senha</button>
        </form>

        <h2>Excluir Perfil</h2>
        <form method="POST" action="" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.');">
            <button type="submit" name="delete_profile" style="color: red;">Excluir Perfil</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
    </footer>
</body>
</html>
