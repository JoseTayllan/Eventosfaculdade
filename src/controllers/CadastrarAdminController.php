<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $tipo = 'Admin';

    try {
        // Verifica duplicação de e-mail
        $sql = "SELECT COUNT(*) FROM Participantes WHERE EmailParticipante = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        if ($stmt->fetchColumn() > 0) {
            die("Já existe um administrador cadastrado com este e-mail.");
        }

        // Cadastra o administrador
        $sql = "INSERT INTO Participantes (NomeParticipante, EmailParticipante, TipoParticipante, SenhaParticipante)
                VALUES (:nome, :email, :tipo, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':tipo' => $tipo,
            ':senha' => $senha
        ]);

        echo "Administrador cadastrado com sucesso!";
    } catch (PDOException $e) {
        die("Erro ao cadastrar administrador: " . $e->getMessage());
    }
}
?>
