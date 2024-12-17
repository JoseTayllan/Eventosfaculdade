<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="/Eventosfaculdade/public/stile/stile.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Ofertados</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="/Eventosfaculdade/public/uploads/fpm.ico">
</head>
<body>
    <!-- Header -->
    <header class="custom-ocean text-white text-center py-3">
        <h1>Sistema de Eventos Acadêmicos</h1>
        <nav class="navbar navbar-expand-lg navbar-dark custom-ocean">
            <div class="container">
                <!-- Logo -->
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" class="me-2" style="height: 70px;"> 
                    
                </a>
                <!-- Navbar Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm mx-1" href="/Eventosfaculdade/src/views/usuarios/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm mx-1" href="/Eventosfaculdade/src/views/usuarios/register.php">Cadastrar-se</a>
                        </li>
                        <li class="nav-item">
                            <!-- Botão Admin -->
                            <a class="btn btn-outline-light btn-sm mx-1" href="/Eventosfaculdade/src/views/admin/login.php">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <main>
        <?php include 'render.php'; ?>
    </main>

    <!-- Footer -->
    <footer class="custom-ocean text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Coluna Esquerda: Informações -->
            <div class="col-md-6 text-start">
                <p><i class="bi bi-geo-alt-fill"></i> Faculdade da Polícia Militar - Campus São Nicolau</p>
                <p><i class="bi bi-geo-alt"></i> Rua 10, nº 923 - Setor Oeste, Goiânia - Goiás</p>
                <p><i class="bi bi-mailbox"></i> CEP: 74120-020</p>
                <p><i class="bi bi-telephone-fill"></i> Telefone: (62) 3286-5895</p>
                <p><i class="bi bi-whatsapp"></i> WhatsApp: 
                    <a href="https://wa.me/5562999908377" class="text-white text-decoration-none" target="_blank">
                        (62) 9 9990-8377
                    </a>
                </p>
                <p><i class="bi bi-clock-fill"></i> Horário de atendimento: das 8h às 20h</p>
            </div>

            <!-- Coluna Direita: Redes Sociais -->
            <div class="col-md-6 text-center">
                <h5>Siga-nos</h5>
                <a href="https://www.facebook.com/faculdadepm/" target="_blank" class="text-white me-3 fs-3"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/faculdadepm/" target="_blank" class="text-white me-3 fs-3"><i class="bi bi-instagram"></i></a>
                <a href="https://www.linkedin.com/company/faculdadedapoliciamilitar/" target="_blank" class="text-white me-3 fs-3"><i class="bi bi-linkedin"></i></a>
                <a href="https://twitter.com" target="_blank" class="text-white fs-3"><i class="bi bi-twitter"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-4">
                <p>&copy; <?php echo date('Y'); ?> Sistema de Eventos Acadêmicos</p>
            </div>
        </div>
    </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
