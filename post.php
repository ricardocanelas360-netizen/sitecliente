<?php
/**
 * Página de Post Individual
 * Exibe o conteúdo completo de um post e seus comentários
 */

include 'includes/conexao.php';

// Obter ID do post da URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id <= 0) {
    header('Location: index.php');
    exit;
}

// Buscar o post específico
$sql = "SELECT id, titulo, conteudo, autor, data_criacao, data_atualizacao FROM posts WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$post = $resultado->fetch_assoc();

// Processar novo comentário
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_comentario'])) {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $texto = isset($_POST['texto']) ? trim($_POST['texto']) : '';

    // Validação básica
    if (empty($nome) || empty($texto)) {
        $mensagem = '<div class="alert alert-error">Nome e comentário são obrigatórios.</div>';
    } elseif (strlen($texto) < 5) {
        $mensagem = '<div class="alert alert-error">O comentário deve ter pelo menos 5 caracteres.</div>';
    } else {
        // Inserir comentário no banco de dados
        $sql_insert = "INSERT INTO comentarios (post_id, nome, email, texto) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conexao->prepare($sql_insert);
        $stmt_insert->bind_param("isss", $post_id, $nome, $email, $texto);

        if ($stmt_insert->execute()) {
            $mensagem = '<div class="alert alert-success">✓ Comentário adicionado com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-error">Erro ao adicionar comentário: ' . $conexao->error . '</div>';
        }
        $stmt_insert->close();
    }
}

// Buscar comentários do post
$sql_comentarios = "SELECT id, nome, email, texto, data_comentario FROM comentarios WHERE post_id = ? ORDER BY data_comentario DESC";
$stmt_comentarios = $conexao->prepare($sql_comentarios);
$stmt_comentarios->bind_param("i", $post_id);
$stmt_comentarios->execute();
$resultado_comentarios = $stmt_comentarios->get_result();

// Formatar datas
$data_criacao = new DateTime($post['data_criacao']);
$data_criacao_formatada = $data_criacao->format('d/m/Y H:i');

$data_atualizacao = new DateTime($post['data_atualizacao']);
$data_atualizacao_formatada = $data_atualizacao->format('d/m/Y H:i');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['titulo']); ?> - Blog Simples</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- HEADER E NAVEGAÇÃO -->
    <header>
        <div class="container">
            <h1>📝 Blog Simples</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="admin/index.php">Painel Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- CONTEÚDO PRINCIPAL -->
    <main>
        <div class="container">
            <!-- BOTÃO VOLTAR -->
            <a href="index.php" class="btn" style="margin-bottom: 2rem;">← Voltar para Home</a>

            <!-- POST INDIVIDUAL -->
            <article class="post-single">
                <h1><?php echo htmlspecialchars($post['titulo']); ?></h1>
                <div class="post-single-meta">
                    <span>✍️ Autor: <?php echo htmlspecialchars($post['autor']); ?></span> | 
                    <span>📅 Publicado em: <?php echo $data_criacao_formatada; ?></span>
                    <?php if ($post['data_criacao'] !== $post['data_atualizacao']): ?>
                        | <span>✏️ Atualizado em: <?php echo $data_atualizacao_formatada; ?></span>
                    <?php endif; ?>
                </div>

                <div class="post-single-content">
                    <?php echo nl2br(htmlspecialchars($post['conteudo'])); ?>
                </div>

                <!-- AÇÕES ADMINISTRATIVAS -->
                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #eee;">
                    <a href="admin/editar.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">✏️ Editar Post</a>
                    <a href="admin/deletar.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja deletar este post?');">🗑️ Deletar Post</a>
                </div>
            </article>

            <!-- SEÇÃO DE COMENTÁRIOS -->
            <section class="comments-section">
                <h3>💬 Comentários (<?php echo $resultado_comentarios->num_rows; ?>)</h3>

                <!-- MENSAGEM DE FEEDBACK -->
                <?php echo $mensagem; ?>

                <!-- FORMULÁRIO DE NOVO COMENTÁRIO -->
                <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem;">
                    <h4>Deixe seu comentário:</h4>
                    <form method="POST" id="formComentario">
                        <div class="form-group">
                            <label for="nome">Nome *</label>
                            <input type="text" id="nome" name="nome" placeholder="Seu nome" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email (opcional)</label>
                            <input type="email" id="email" name="email" placeholder="seu@email.com">
                        </div>

                        <div class="form-group">
                            <label for="texto">Comentário *</label>
                            <textarea id="texto" name="texto" placeholder="Escreva seu comentário aqui..." required></textarea>
                        </div>

                        <button type="submit" name="enviar_comentario" class="btn btn-success" onclick="return validarFormulario('formComentario');">Enviar Comentário</button>
                    </form>
                </div>

                <!-- LISTAGEM DE COMENTÁRIOS -->
                <?php
                if ($resultado_comentarios->num_rows > 0) {
                    while ($comentario = $resultado_comentarios->fetch_assoc()) {
                        $data_comentario = new DateTime($comentario['data_comentario']);
                        $data_comentario_formatada = $data_comentario->format('d/m/Y H:i');
                        ?>
                        <div class="comment">
                            <strong class="comment-author"><?php echo htmlspecialchars($comentario['nome']); ?></strong>
                            <span class="comment-date"><?php echo $data_comentario_formatada; ?></span>
                            <p class="comment-text"><?php echo nl2br(htmlspecialchars($comentario['texto'])); ?></p>
                            <small style="color: #999;">
                                <?php if (!empty($comentario['email'])): ?>
                                    Email: <?php echo htmlspecialchars($comentario['email']); ?>
                                <?php endif; ?>
                            </small>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p style="text-align: center; color: #999; padding: 2rem;">Nenhum comentário ainda. Seja o primeiro a comentar!</p>';
                }
                ?>
            </section>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 Blog Simples. Todos os direitos reservados.</p>
        <p>Desenvolvido com HTML5, CSS3, JavaScript e PHP com MySQL.</p>
    </footer>

    <!-- SCRIPTS -->
    <script src="js/script.js"></script>
</body>
</html>
