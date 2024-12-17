<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Administrador</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
        <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Cadastrar Administrador</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Exibição de mensagens de feedback -->
                <?php if (isset($_GET['status'])): ?>
                    <div class="alert text-center <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                        <?php
                        if ($_GET['status'] === 'success') {
                            echo "Administrador cadastrado com sucesso!";
                        } elseif ($_GET['status'] === 'email_ja_existe') {
                            echo "O e-mail informado já está em uso.";
                        } else {
                            echo "Erro ao cadastrar administrador. Tente novamente.";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Card do Formulário -->
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Cadastro de Administrador</h2>

                        <!-- Formulário -->
                        <form method="POST" action="/Eventosfaculdade/src/controllers/CadastrarAdminController.php">
                            <!-- Nome Completo -->
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome completo" required>
                            </div>

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

                            <!-- Botão de Cadastro -->
                            <div class="d-grid">
                                <button type="submit" class="btn custom-ocean">Cadastrar</button>
                            </div>
                        </form>

                        <!-- Link para Login -->
                        <p class="text-center mt-3">
                            Já é administrador? <a href="/Eventosfaculdade/src/views/admin/login.php" class="text-decoration-none">Clique aqui para acessar o login</a>.
                        </p>
                    </div>
                </div>
            </div>
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