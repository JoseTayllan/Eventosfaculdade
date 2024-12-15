<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Login de Administrador</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Mensagens de Feedback -->
                <?php if (isset($_GET['status'])): ?>
                    <div class="alert <?php echo ($_GET['status'] === 'login_failed') ? 'alert-danger' : 'alert-success'; ?> text-center">
                        <?php
                        if ($_GET['status'] === 'login_failed') {
                            echo "Erro: E-mail ou senha inválidos.";
                        } elseif ($_GET['status'] === 'logout_success') {
                            echo "Você saiu do sistema com sucesso.";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Card do Formulário -->
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Acessar Sistema</h2>

                        <!-- Formulário -->
                        <form method="POST" action="/Eventosfaculdade/src/controllers/LoginAdminController.php">
                            <!-- E-mail -->
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required>
                            </div>

                            <!-- Senha -->
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
                            </div>

                            <!-- Botão de Login -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>

                        <!-- Link para Cadastro -->
                        <p class="text-center mt-3">
                            Ainda não é administrador? <a href="/Eventosfaculdade/src/views/admin/cadastrar.php" class="text-decoration-none">Clique aqui para se cadastrar</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5 fixed-bottom">
        <p class="m-0">&copy; 2024 Sistema de Eventos</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
