<?php
require_once '../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['banner_id'])) {
    $bannerId = $_POST['banner_id'];

    try {
        // Obtém o caminho da imagem para exclusão
        $sqlImagem = "SELECT ImagemBanner FROM Banners WHERE BannerId = :banner_id";
        $stmtImagem = $pdo->prepare($sqlImagem);
        $stmtImagem->execute([':banner_id' => $bannerId]);
        $banner = $stmtImagem->fetch(PDO::FETCH_ASSOC);

        if ($banner && !empty($banner['ImagemBanner'])) {
            // Remove o arquivo de imagem do servidor
            $caminhoImagem = realpath(dirname(__DIR__) . '/../../') . $banner['ImagemBanner'];
            if (file_exists($caminhoImagem)) {
                unlink($caminhoImagem);
            }
        }

        // Exclui o banner do banco de dados
        $sql = "DELETE FROM Banners WHERE BannerId = :banner_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':banner_id' => $bannerId]);

        header("Location: /Eventosfaculdade/src/views/banners/gerenciar_banners.php?success=banner_excluido");
        exit;
    } catch (PDOException $e) {
        die("Erro ao excluir banner: " . $e->getMessage());
    }
}
?>
