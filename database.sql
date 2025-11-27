-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS blog_db;
USE blog_db;

-- Tabela de Posts (Tabela Principal)
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    autor VARCHAR(100) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Comentários (Tabela Auxiliar - Relacionada)
CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    texto TEXT NOT NULL,
    data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir dados de exemplo (opcional)
INSERT INTO posts (titulo, conteudo, autor) VALUES
('Bem-vindo ao Blog!', 'Este é o primeiro post do nosso blog. Aqui você pode criar, ler, atualizar e deletar posts. O blog demonstra as operações CRUD (Create, Read, Update, Delete) usando PHP e MySQL.', 'Administrador'),
('Entendendo CRUD', 'CRUD significa Create (Criar), Read (Ler), Update (Atualizar) e Delete (Deletar). Essas são as operações básicas em qualquer aplicação que trabalha com dados persistentes em um banco de dados.', 'Professor'),
('Introdução ao PHP', 'PHP é uma linguagem de script do lado do servidor que permite criar páginas web dinâmicas. Com PHP, você pode processar formulários, gerenciar sessões e interagir com bancos de dados como MySQL.', 'Desenvolvedor');

INSERT INTO comentarios (post_id, nome, email, texto) VALUES
(1, 'João Silva', 'joao@example.com', 'Excelente post! Muito útil para iniciantes.'),
(1, 'Maria Santos', 'maria@example.com', 'Concordo, o blog ficou muito bom!'),
(2, 'Pedro Costa', 'pedro@example.com', 'Ótima explicação sobre CRUD.'),
(3, 'Ana Oliveira', 'ana@example.com', 'PHP é realmente poderoso para desenvolvimento web.');
