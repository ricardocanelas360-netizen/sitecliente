<?php
/**
 * Painel Administrativo - Listagem de Posts
 * Exibe todos os posts e permite criar, editar e deletar
 */

include '../includes/conexao.php';

// Buscar todos os posts
$sql = "SELECT id, titulo, autor, data_criacao, data_atualizacao FROM posts ORDER BY data_criacao DESC";
$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Blog Simples</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- HEADER E NAVEGAÇÃO -->
    <header>
        <div class="container">
            <h1>📝 Blog Simples - Painel Admin</h1>
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
            <!-- CABEÇALHO DO PAINEL -->
            <div class="admin-header">
                <h2>🛠️ Gerenciamento de Posts</h2>
                <p>Aqui você pode criar, editar e deletar posts do blog.</p>
            </div>

            <!-- BOTÃO CRIAR NOVO POST -->
            <div style="margin-bottom: 2rem;">
                <a href="criar.php" class="btn btn-success">➕ Criar Novo Post</a>
            </div>

            <!-- TABELA DE POSTS -->
            <?php
            if ($resultado->num_rows > 0) {
                ?>
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Data de Criação</th>
                            <th>Última Atualização</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($post = $resultado->fetch_assoc()) {
                            $data_criacao = new DateTime($post['data_criacao']);
                            $data_criacao_formatada = $data_criacao->format('d/m/Y H:i');

                            $data_atualizacao = new DateTime($post['data_atualizacao']);
                            $data_atualizacao_formatada = $data_atualizacao->format('d/m/Y H:i');
                            ?>
                            <tr>
                                <td><?php echo $post['id']; ?></td>
                                <td><?php echo htmlspecialchars($post['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($post['autor']); ?></td>
                                <td><?php echo $data_criacao_formatada; ?></td>
                                <td><?php echo $data_atualizacao_formatada; ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="../post.php?id=<?php echo $post['id']; ?>" class="btn" style="background: #3498db;">👁️ Ver</a>
                                        <a href="editar.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">✏️ Editar</a>
                                        <a href="deletar.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja deletar este post?');">🗑️ Deletar</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <div class="alert alert-info">
                    Nenhum post encontrado. <a href="criar.php">Crie o primeiro post agora!</a>
                </div>
                <?php
            }
            ?>
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
