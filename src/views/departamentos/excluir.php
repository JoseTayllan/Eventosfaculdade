<?php
session_start();

require_once '../../../config/database.php';

try {
    // Configurar a conexão com o banco de dados
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se há uma solicitação de exclusão
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
        $id = intval($_POST['excluir_id']); // Garante que o ID é numérico

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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Departamentos</title>
    <link rel="stylesheet" href="/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin - Departamentos</h1>

        <?php
        // Mensagens de feedback
        if (!empty($_SESSION['erro'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro']) . '</div>';
            unset($_SESSION['erro']);
        }
        if (!empty($_SESSION['sucesso'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['sucesso']) . '</div>';
            unset($_SESSION['sucesso']);
        }
        ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departamentos as $departamento): ?>
                    <tr>
                        <td><?= htmlspecialchars($departamento['DepartamentoId']); ?></td>
                        <td><?= htmlspecialchars($departamento['NomeDepartamento']); ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="excluir_id" value="<?= $departamento['DepartamentoId']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este departamento?');">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
