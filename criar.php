<?php
/**
 * Página para Criar Novo Post
 * Formulário para adicionar um novo post ao blog
 */

include '../includes/conexao.php';

$mensagem = '';

// Processar formulário de criação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar_post'])) {
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
        // Inserir post no banco de dados
        $sql = "INSERT INTO posts (titulo, conteudo, autor) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sss", $titulo, $conteudo, $autor);

        if ($stmt->execute()) {
            $post_id = $conexao->insert_id;
            $mensagem = '<div class="alert alert-success">✓ Post criado com sucesso! <a href="../post.php?id=' . $post_id . '">Ver post</a></div>';
            
            // Limpar formulário
            $_POST = array();
        } else {
            $mensagem = '<div class="alert alert-error">Erro ao criar post: ' . $conexao->error . '</div>';
        }
        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Novo Post - Blog Simples</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- HEADER E NAVEGAÇÃO -->
    <header>
        <div class="container">
            <h1>📝 Blog Simples - Criar Post</h1>
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

            <!-- FORMULÁRIO DE CRIAÇÃO -->
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); max-width: 800px;">
                <h2>➕ Criar Novo Post</h2>

                <!-- MENSAGEM DE FEEDBACK -->
                <?php echo $mensagem; ?>

                <form method="POST" id="formCriarPost">
                    <div class="form-group">
                        <label for="titulo">Título do Post *</label>
                        <input 
                            type="text" 
                            id="titulo" 
                            name="titulo" 
                            placeholder="Digite o título do post" 
                            value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>"
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
                            value="<?php echo isset($_POST['autor']) ? htmlspecialchars($_POST['autor']) : ''; ?>"
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
                        ><?php echo isset($_POST['conteudo']) ? htmlspecialchars($_POST['conteudo']) : ''; ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="criar_post" class="btn btn-success" onclick="return validarFormulario('formCriarPost');">✓ Criar Post</button>
                        <a href="index.php" class="btn btn-secondary" style="text-align: center;">Cancelar</a>
                    </div>
                </form>
            </div>

            <!-- DICAS -->
            <div style="background: #e8f4f8; padding: 1.5rem; border-radius: 5px; margin-top: 2rem; border-left: 4px solid #3498db;">
                <h4>💡 Dicas para criar um bom post:</h4>
                <ul style="margin-left: 1.5rem;">
                    <li>Use um título descritivo e atrativo</li>
                    <li>Organize o conteúdo em parágrafos claros</li>
                    <li>Mantenha o texto legível e bem estruturado</li>
                    <li>Revise a ortografia e gramática antes de publicar</li>
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
