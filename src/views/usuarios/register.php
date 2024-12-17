<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/css/style.css">
    <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>

<body>
    <!-- Header -->
    <header class="custom-ocean text-white py-3 d-flex align-items-center">
        <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
        <h1 class="m-0 text-center w-100">Registro de Usuário</h1>
    </header>

    <!-- Conteúdo Principal -->
    <div class="container mt-5" style="padding-bottom: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="p-4 border rounded bg-light shadow">
                    <h2 class="text-center mb-4">Preencha o Formulário</h2>

                    <!-- Formulário -->
                    <form method="POST" action="/Eventosfaculdade/src/controllers/RegisterController.php">
                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome completo" required>
                        </div>

                        <!-- E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required>
                        </div>
                        <!-- Telefone -->
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" name="telefone" id="telefone" class="form-control"
                                placeholder="Ex: (62) 99628-0333"
                                pattern="^\(\d{2}\)\s\d{4,5}-\d{4}$"
                                title="Formato esperado: (XX) XXXXX-XXXX" required>
                        </div>


                        <!-- Tipo de Usuário -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Usuário</label>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="">Selecione o tipo</option>
                                <option value="Interno">Interno</option>
                                <option value="Externo">Externo</option>
                            </select>
                        </div>

                        <!-- Campos Dinâmicos -->
                        <div id="campos-interno" class="mb-3" style="display: none;">
                            <label for="numero_matricula" class="form-label">Número de Matrícula</label>
                            <input type="text" name="numero_matricula" id="numero_matricula" class="form-control" placeholder="Digite seu número de matrícula">
                        </div>
                        <div id="campos-externo" class="mb-3" style="display: none;">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" name="cpf" id="cpf" class="form-control" placeholder="Digite seu CPF">
                        </div>

                        <!-- Senha -->
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
                        </div>

                        <!-- Botão de Registro -->
                        <div class="d-grid">
                            <button type="submit" class="btn custom-ocean">Registrar</button>
                        </div>
                    </form>
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
    <script>
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                e.target.value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else {
                e.target.value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            }
        });
    </script>

    <!-- Script Dinâmico -->
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


