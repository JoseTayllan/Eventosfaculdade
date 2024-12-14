<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Administrador</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
</head>
<body>
    <h1>Cadastrar Administrador</h1>
    <form method="POST" action="/Eventosfaculdade/src/controllers/CadastrarAdminController.php">
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
    </form>
    <p>É administrador? <a href="/Eventosfaculdade/src/views/admin/login.php">Clique aqui para acessar o login de administrador</a>.</p>
</body>
</html>
