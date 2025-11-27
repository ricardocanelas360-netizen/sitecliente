<?php
/**
 * Página para Deletar Post
 * Deleta um post e todos os seus comentários associados
 */

include '../includes/conexao.php';

// Obter ID do post da URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id <= 0) {
    header('Location: index.php');
    exit;
}

// Buscar o post específico
$sql = "SELECT id, titulo FROM posts WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$post = $resultado->fetch_assoc();
$stmt->close();

// Processar confirmação de exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_delecao'])) {
    // Deletar post (comentários serão deletados automaticamente pela constraint ON DELETE CASCADE)
    $sql_delete = "DELETE FROM posts WHERE id = ?";
    $stmt_delete = $conexao->prepare($sql_delete);
    $stmt_delete->bind_param("i", $post_id);

    if ($stmt_delete->execute()) {
        $stmt_delete->close();
        header('Location: index.php?mensagem=Post deletado com sucesso');
        exit;
    } else {
        $erro = "Erro ao deletar post: " . $conexao->error;
    }
    $stmt_delete->close();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Post - Blog Simples</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- HEADER E NAVEGAÇÃO -->
    <header>
        <div class="container">
            <h1>📝 Blog Simples - Deletar Post</h1>
            <nav>
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="index.php">Painel Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- CONTEÚDO PRINCIPAL -->
    <main>
        <div class="container">
            <!-- CONFIRMAÇÃO DE EXCLUSÃO -->
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); max-width: 600px;">
                <h2>⚠️ Confirmar Exclusão</h2>

                <?php if (isset($erro)): ?>
                    <div class="alert alert-error"><?php echo $erro; ?></div>
                <?php endif; ?>

                <div style="background: #fff3cd; padding: 1.5rem; border-radius: 5px; margin: 1.5rem 0; border-left: 4px solid #ffc107;">
                    <p><strong>Você está prestes a deletar o seguinte post:</strong></p>
                    <p style="font-size: 1.2rem; margin: 1rem 0; color: #333;">
                        <strong><?php echo htmlspecialchars($post['titulo']); ?></strong>
                    </p>
                    <p style="color: #666;">
                        <strong>Aviso:</strong> Esta ação é irreversível e também deletará todos os comentários associados a este post.
                    </p>
                </div>

                <form method="POST" id="formDelecao">
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" name="confirmar_delecao" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja deletar permanentemente este post e todos os seus comentários?');">
                            🗑️ Sim, Deletar Permanentemente
                        </button>
                        <a href="index.php" class="btn btn-secondary" style="text-align: center;">Cancelar</a>
                    </div>
                </form>
            </div>

            <!-- INFORMAÇÕES ADICIONAIS -->
            <div style="background: #e8f4f8; padding: 1.5rem; border-radius: 5px; margin-top: 2rem; border-left: 4px solid #3498db;">
                <h4>ℹ️ Informações sobre exclusão:</h4>
                <ul style="margin-left: 1.5rem;">
                    <li>O post será permanentemente removido do banco de dados</li>
                    <li>Todos os comentários associados também serão deletados</li>
                    <li>Esta ação não pode ser desfeita</li>
                    <li>Se deletar por engano, você precisará recriar o post manualmente</li>
                </ul>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 Blog Simples. Todos os direitos reservados.</p>
        <p>Desenvolvido com HTML5, CSS3, JavaScript e PHP com MySQL.</p>
    </footer>

    <!-- SCRIPTS -->
    <script src="../js/script.js"></script>
</body>
</html>
