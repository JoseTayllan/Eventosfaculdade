<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o usuário é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/admin/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeDepartamento = $_POST['nome_departamento'];

    try {
        $sql = "INSERT INTO Departamentos (NomeDepartamento) VALUES (:nome)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nome' => $nomeDepartamento]);
        $mensagem = "Departamento cadastrado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar departamento: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Departamento</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Cadastrar Departamento</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Card do Formulário -->
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Novo Departamento</h2>

                        <!-- Mensagem de Feedback -->
                        <?php if (isset($mensagem)): ?>
                            <div class="alert alert-<?php echo strpos($mensagem, 'sucesso') !== false ? 'success' : 'danger'; ?> text-center">
                                <?php echo htmlspecialchars($mensagem); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulário -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nome_departamento" class="form-label">Nome do Departamento</label>
                                <input type="text" name="nome_departamento" id="nome_departamento" class="form-control" placeholder="Digite o nome do departamento" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn custom-ocean">Cadastrar</button>
                            </div>
                        </form>
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
