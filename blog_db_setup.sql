-- Script SQL para criação das tabelas do Blog

-- Tabela Principal: posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    autor VARCHAR(100) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela Auxiliar: comentarios
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    texto TEXT NOT NULL,
    data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Chave estrangeira que relaciona o comentário ao post
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Inserção de dados de exemplo (opcional, mas útil para testes)
INSERT INTO posts (titulo, conteudo, autor) VALUES
('Primeiro Post do Blog', 'Este é o conteúdo do meu primeiro post. Ele demonstra a funcionalidade de leitura (Read) do nosso sistema.', 'Manus'),
('A Importância do CRUD', 'O CRUD é a base de qualquer aplicação web dinâmica. Ele permite a persistência dos dados.', 'Professor');

INSERT INTO comentarios (post_id, nome, texto) VALUES
(1, 'Visitante 1', 'Ótimo post! Aprendi muito sobre a estrutura do banco de dados.'),
(1, 'Visitante 2', 'Concordo, o CRUD é essencial.'),
(2, 'Aluno Curioso', 'Qual a diferença entre VARCHAR e TEXT no MySQL?');
