<?php
session_start();
require_once '../../../config/database.php';

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

// Buscar banners
try {
    $sql = "SELECT * FROM Banners ORDER BY DataCriacao DESC";
    $stmt = $pdo->query($sql);
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar banners: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Banners</title>
    <link rel="stylesheet" href="/Eventosfaculdade/public/stile/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>

<header class="bg-secondary text-white py-3 d-flex align-items-center">
    <img src="/Eventosfaculdade/public/uploads/Logo_FPM.png" alt="Logo" style="height: 70px;" class="ms-3">
    <h1 class="m-0 text-center w-100">Gerenciar Banners</h1>
</header>

<div class="container mt-5" style="padding-bottom: 80px;">
        <h2 class="mb-4">Banners Cadastrados</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Imagem</th>
                    <th>Título</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($banners)): ?>
                    <?php foreach ($banners as $banner): ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($banner['ImagemBanner']); ?>" alt="Banner" style="width: 100px;">
                            </td>
                            <td><?php echo htmlspecialchars($banner['Titulo'] ?? 'Sem título'); ?></td>
                            <td>
                                <form method="POST" action="/Eventosfaculdade/src/controllers/ExcluirBannerController.php" style="display:inline;">
                                    <input type="hidden" name="banner_id" value="<?php echo $banner['BannerId']; ?>">
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este banner?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Nenhum banner cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2 class="mt-5">Adicionar Novo Banner</h2>
        <form method="POST" action="/Eventosfaculdade/src/controllers/CadastrarBannerController.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem do Banner:</label>
                <input type="file" name="imagem" id="imagem" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" name="titulo" id="titulo" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Banner</button>
        </form>
    </div>

    <footer class="bg-secondary text-white text-center py-3 mt-5 fixed-bottom">
    <p class="m-0">&copy; 2024 Sistema de Eventos</p>
</footer>
</body>
</html>
