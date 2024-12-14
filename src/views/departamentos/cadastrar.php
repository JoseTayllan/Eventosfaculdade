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
        echo "Departamento cadastrado com sucesso!";
    } catch (PDOException $e) {
        die("Erro ao cadastrar departamento: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Departamento</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Cadastrar Departamento</h1>
    <form method="POST" action="">
        <input type="text" name="nome_departamento" placeholder="Nome do Departamento" required>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
