<?php
session_start();
require_once '../../../config/database.php';

// Inicializa a variável para evitar erro no foreach
$departamentos = []; 

try {
    // Configurar a conexão com o banco de dados
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se há uma solicitação de exclusão
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
        $id = intval($_POST['excluir_id']); // Garante que o ID é numérico

        // Query para deletar o departamento
        $query = "DELETE FROM departamentos WHERE DepartamentoId = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Departamento excluído com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao excluir o departamento.";
        }

        // Redireciona para evitar reenvio de formulário
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Busca os departamentos no banco de dados
    $query = "SELECT * FROM departamentos";
    $stmt = $db->query($query);
    $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Departamentos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-danger {
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #b02a37;
            border-color: #a71d2a;
        }
        th {
            background-color: #343a40;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Administração de Departamentos</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Card da Tabela -->
                <div class="card shadow-lg">
                    <div class="card-body">
                        <!-- Mensagens de Feedback -->
                        <?php if (!empty($_SESSION['erro'])): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['erro']); ?></div>
                            <?php unset($_SESSION['erro']); ?>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['sucesso'])): ?>
                            <div class="alert alert-success text-center"><?= htmlspecialchars($_SESSION['sucesso']); ?></div>
                            <?php unset($_SESSION['sucesso']); ?>
                        <?php endif; ?>

                        <!-- Título da Tabela -->
                        <h2 class="text-center mb-4">Lista de Departamentos</h2>

                        <!-- Tabela -->
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Departamento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($departamentos)): ?>
                                    <?php foreach ($departamentos as $departamento): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($departamento['DepartamentoId']); ?></td>
                                            <td><?= htmlspecialchars($departamento['NomeDepartamento']); ?></td>
                                            <td>
                                                <form method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="excluir_id" value="<?= $departamento['DepartamentoId']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este departamento?');">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhum departamento encontrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        
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
