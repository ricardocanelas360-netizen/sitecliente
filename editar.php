<?php
/**
 * Página para Editar Post
 * Formulário para editar um post existente
 */

include '../includes/conexao.php';

// Obter ID do post da URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id <= 0) {
    header('Location: index.php');
    exit;
}

// Buscar o post específico
$sql = "SELECT id, titulo, conteudo, autor FROM posts WHERE id = ?";
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

$mensagem = '';

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_post'])) {
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $conteudo = isset($_POST['conteudo']) ? trim($_POST['conteudo']) : '';
    $autor = isset($_POST['autor']) ? trim($_POST['autor']) : '';

    // Validação
    if (empty($titulo) || empty($conteudo) || empty($autor)) {
        $mensagem = '<div class="alert alert-error">Todos os campos são obrigatórios.</div>';
    } elseif (strlen($titulo) < 5) {
        $mensagem = '<div class="alert alert-error">O título deve ter pelo menos 5 caracteres.</div>';
    } elseif (strlen($conteudo) < 20) {
        $mensagem = '<div class="alert alert-error">O conteúdo deve ter pelo menos 20 caracteres.</div>';
    } else {
        // Atualizar post no banco de dados
        $sql_update = "UPDATE posts SET titulo = ?, conteudo = ?, autor = ? WHERE id = ?";
        $stmt_update = $conexao->prepare($sql_update);
        $stmt_update->bind_param("sssi", $titulo, $conteudo, $autor, $post_id);

        if ($stmt_update->execute()) {
            $mensagem = '<div class="alert alert-success">✓ Post atualizado com sucesso! <a href="../post.php?id=' . $post_id . '">Ver post</a></div>';
            
            // Atualizar dados do post
            $post['titulo'] = $titulo;
            $post['conteudo'] = $conteudo;
            $post['autor'] = $autor;
        } else {
            $mensagem = '<div class="alert alert-error">Erro ao atualizar post: ' . $conexao->error . '</div>';
        }
        $stmt_update->close();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Post - Blog Simples</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- HEADER E NAVEGAÇÃO -->
    <header>
        <div class="container">
            <h1>📝 Blog Simples - Editar Post</h1>
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
            <!-- BOTÃO VOLTAR -->
            <a href="index.php" class="btn" style="margin-bottom: 2rem;">← Voltar para Admin</a>

            <!-- FORMULÁRIO DE EDIÇÃO -->
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); max-width: 800px;">
                <h2>✏️ Editar Post #<?php echo $post_id; ?></h2>

                <!-- MENSAGEM DE FEEDBACK -->
                <?php echo $mensagem; ?>

                <form method="POST" id="formEditarPost">
                    <div class="form-group">
                        <label for="titulo">Título do Post *</label>
                        <input 
                            type="text" 
                            id="titulo" 
                            name="titulo" 
                            placeholder="Digite o título do post" 
                            value="<?php echo htmlspecialchars($post['titulo']); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="autor">Autor *</label>
                        <input 
                            type="text" 
                            id="autor" 
                            name="autor" 
                            placeholder="Digite o nome do autor" 
                            value="<?php echo htmlspecialchars($post['autor']); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="conteudo">Conteúdo do Post *</label>
                        <textarea 
                            id="conteudo" 
                            name="conteudo" 
                            placeholder="Digite o conteúdo completo do post aqui..." 
                            required
                        ><?php echo htmlspecialchars($post['conteudo']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="atualizar_post" class="btn btn-success" onclick="return validarFormulario('formEditarPost');">✓ Atualizar Post</button>
                        <a href="index.php" class="btn btn-secondary" style="text-align: center;">Cancelar</a>
                        <a href="deletar.php?id=<?php echo $post_id; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja deletar este post?');">🗑️ Deletar</a>
                    </div>
                </form>
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
