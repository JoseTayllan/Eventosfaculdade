<?php
require_once '../../config/database.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? null;

    // Upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid('banner_', true) . '.' . $extensao;
        $caminhoImagem = '../../public/uploads/' . $nomeImagem;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
            $imagem = '/Eventosfaculdade/public/uploads/' . $nomeImagem;

            // Insere no banco de dados
            try {
                $sql = "INSERT INTO Banners (ImagemBanner, Titulo) VALUES (:imagem, :titulo)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':imagem' => $imagem,
                    ':titulo' => $titulo,
                ]);

                header("Location: /Eventosfaculdade/src/views/banners/gerenciar_banners.php?success=banner_cadastrado");
                exit;
            } catch (PDOException $e) {
                die("Erro ao cadastrar banner: " . $e->getMessage());
            }
        } else {
            die("Erro ao fazer o upload da imagem.");
        }
    } else {
        die("Imagem não enviada.");
    }
}
?>
