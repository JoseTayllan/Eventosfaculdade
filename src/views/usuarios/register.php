<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <h1>Registro de Usuário</h1>
    <form method="POST" action="/Eventosfaculdade/src/controllers/RegisterController.php">
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <select name="tipo" id="tipo" required>
            <option value="">Selecione o tipo</option>
            <option value="Interno">Interno</option>
            <option value="Externo">Externo</option>
        </select>
        <div id="campos-interno" style="display: none;">
            <input type="text" name="numero_matricula" placeholder="Número de Matrícula">
        </div>
        <div id="campos-externo" style="display: none;">
            <input type="text" name="cpf" placeholder="CPF">
        </div>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Registrar</button>
    </form>

    <script>
        const tipoSelect = document.getElementById('tipo');
        const camposInterno = document.getElementById('campos-interno');
        const camposExterno = document.getElementById('campos-externo');

        tipoSelect.addEventListener('change', () => {
            if (tipoSelect.value === 'Interno') {
                camposInterno.style.display = 'block';
                camposExterno.style.display = 'none';
            } else if (tipoSelect.value === 'Externo') {
                camposInterno.style.display = 'none';
                camposExterno.style.display = 'block';
            } else {
                camposInterno.style.display = 'none';
                camposExterno.style.display = 'none';
            }
        });
    </script>
</body>
</html>
