<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../../public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-secondary text-white py-3 d-flex align-items-center">
    <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
    <h1 class="m-0 text-center w-100">Sistema de Eventos</h1>
    </header>
    
    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Login</h2>
                
                <!-- Mensagem de erro -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Formulário -->
                <form method="POST" action="/Eventosfaculdade/src/controllers/LoginController.php" class="p-4 border rounded bg-light shadow">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
                
                <!-- Link de registro -->
                <p class="text-center mt-3">
                    Não tem uma conta? <a href="register.php" class="text-decoration-none">Registre-se aqui</a>.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-secondary text-white text-center py-3 mt-5 fixed-bottom">
    <p class="m-0">&copy; 2024 Sistema de Eventos</p>
</footer>
    
    <!-- Bootstrap JS -->
    <script src="../../../public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
