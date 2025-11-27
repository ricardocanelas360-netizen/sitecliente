# Blog Simples - Sistema de Blog com PHP e MySQL

Um projeto acadêmico de blog funcional desenvolvido com **HTML5**, **CSS3**, **JavaScript** e **PHP**, integrando um banco de dados **MySQL** para operações CRUD de posts e comentários.

## 📋 Requisitos do Projeto

Este projeto atende aos seguintes requisitos técnicos:

- ✅ **HTML5**: Estrutura semântica com header, main, footer, etc.
- ✅ **CSS3**: Layout responsivo e estilização personalizada
- ✅ **JavaScript**: Validação de formulários e interatividade visual
- ✅ **PHP**: Processamento de requisições e lógica de servidor
- ✅ **MySQL**: Banco de dados com tabelas relacionadas
- ✅ **CRUD Completo**: Create, Read, Update, Delete de posts e comentários
- ✅ **Interface Administrativa**: Painel para gerenciar posts
- ✅ **Mínimo 3 Páginas**: Home, Post Individual, Painel Admin

## 🗂️ Estrutura de Pastas

```
blog_php/
├── index.php                    # Página inicial (listagem de posts)
├── post.php                     # Página de post individual com comentários
├── css/
│   └── style.css               # Estilos CSS responsivos
├── js/
│   └── script.js               # JavaScript para validação e interatividade
├── includes/
│   └── conexao.php             # Arquivo de conexão com o banco de dados
├── admin/
│   ├── index.php               # Painel administrativo (listagem)
│   ├── criar.php               # Criar novo post
│   ├── editar.php              # Editar post existente
│   └── deletar.php             # Deletar post
├── uploads/                     # Pasta para uploads (futuro)
├── database.sql                # Script SQL para criar banco de dados
└── README.md                   # Este arquivo
```

## 🚀 Instalação e Configuração

### 1. Pré-requisitos

- **PHP 7.4+** (com extensão MySQLi)
- **MySQL 5.7+** ou **MariaDB**
- **Servidor Web** (Apache, Nginx, etc.)

### 2. Criar o Banco de Dados

1. Abra o **phpMyAdmin** ou acesse o MySQL via terminal
2. Execute o script SQL fornecido em `database.sql`:

```sql
-- Copie e execute o conteúdo do arquivo database.sql
```

Ou via terminal:

```bash
mysql -u root -p < database.sql
```

### 3. Configurar Conexão com o Banco de Dados

Edite o arquivo `includes/conexao.php` e ajuste as credenciais:

```php
define('DB_HOST', 'localhost');    // Host do banco (geralmente localhost)
define('DB_USER', 'root');         // Usuário MySQL
define('DB_PASS', '');             // Senha MySQL (deixe vazio se não houver)
define('DB_NAME', 'blog_db');      // Nome do banco de dados
```

### 4. Colocar em um Servidor Web

1. Copie a pasta `blog_php` para a raiz do seu servidor web:
   - **Apache**: `/var/www/html/` ou `C:\xampp\htdocs\`
   - **Nginx**: `/var/www/`

2. Acesse via navegador:
   ```
   http://localhost/blog_php/
   ```

## 📖 Como Usar

### Página Inicial (Home)

- Exibe todos os posts em ordem decrescente de data
- Mostra um resumo de cada post
- Permite buscar posts por título ou conteúdo
- Clique em "Ler Mais" para acessar o post completo

### Página de Post Individual

- Exibe o conteúdo completo do post
- Mostra todos os comentários
- Permite adicionar novos comentários
- Oferece opções para editar ou deletar o post

### Painel Administrativo

#### Listagem de Posts (`admin/index.php`)
- Visualiza todos os posts em uma tabela
- Mostra ID, título, autor e datas
- Oferece ações: Ver, Editar, Deletar

#### Criar Post (`admin/criar.php`)
- Formulário para criar um novo post
- Campos: Título, Autor, Conteúdo
- Validação de campos obrigatórios e comprimento mínimo

#### Editar Post (`admin/editar.php`)
- Formulário pré-preenchido com dados do post
- Permite modificar título, autor e conteúdo
- Atualiza a data de modificação automaticamente

#### Deletar Post (`admin/deletar.php`)
- Página de confirmação antes de deletar
- Aviso sobre exclusão de comentários associados
- Deleta post e comentários via constraint `ON DELETE CASCADE`

## 🔧 Funcionalidades

### CRUD de Posts

| Operação | Descrição | Arquivo |
|----------|-----------|---------|
| **Create** | Criar novo post | `admin/criar.php` |
| **Read** | Listar e visualizar posts | `index.php`, `post.php` |
| **Update** | Editar post existente | `admin/editar.php` |
| **Delete** | Deletar post | `admin/deletar.php` |

### CRUD de Comentários

| Operação | Descrição | Arquivo |
|----------|-----------|---------|
| **Create** | Adicionar comentário em um post | `post.php` |
| **Read** | Listar comentários de um post | `post.php` |
| **Delete** | Deletar via constraint (automático) | - |

### Validações JavaScript

- ✅ Campos obrigatórios
- ✅ Validação de email
- ✅ Comprimento mínimo de texto
- ✅ Mensagens de erro dinâmicas
- ✅ Confirmação de exclusão

### Recursos de Interatividade

- 🔍 Busca em tempo real de posts
- 💬 Formulário de comentários com validação
- 📱 Design responsivo (mobile-first)
- 🎨 Estilos modernos com gradientes e sombras
- ⚡ Feedback visual (alertas, modais)

## 📊 Estrutura do Banco de Dados

### Tabela: `posts`

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT | ID único (chave primária) |
| `titulo` | VARCHAR(255) | Título do post |
| `conteudo` | TEXT | Conteúdo completo |
| `autor` | VARCHAR(100) | Nome do autor |
| `data_criacao` | TIMESTAMP | Data de criação |
| `data_atualizacao` | TIMESTAMP | Data da última atualização |

### Tabela: `comentarios`

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT | ID único (chave primária) |
| `post_id` | INT | ID do post (chave estrangeira) |
| `nome` | VARCHAR(100) | Nome do comentarista |
| `email` | VARCHAR(100) | Email do comentarista |
| `texto` | TEXT | Texto do comentário |
| `data_comentario` | TIMESTAMP | Data do comentário |

**Relacionamento**: Cada comentário está associado a um post via `post_id`. Quando um post é deletado, todos os seus comentários são deletados automaticamente (constraint `ON DELETE CASCADE`).

## 🎨 Design e Responsividade

- **Cores**: Gradiente roxo (#667eea → #764ba2)
- **Tipografia**: Segoe UI, Tahoma, Geneva, Verdana
- **Layout**: Grid responsivo para posts
- **Breakpoints**: 768px (tablet), 480px (mobile)
- **Acessibilidade**: Semântica HTML5, contraste adequado

## 🔒 Segurança

- ✅ Prepared Statements (proteção contra SQL Injection)
- ✅ htmlspecialchars() para escapar HTML
- ✅ Validação de entrada no servidor
- ✅ Validação de entrada no cliente (JavaScript)
- ✅ Confirmação antes de deletar

## 📝 Exemplos de Uso

### Criar um Post via PHP

```php
$titulo = "Meu Primeiro Post";
$conteudo = "Este é o conteúdo do meu post...";
$autor = "João Silva";

$sql = "INSERT INTO posts (titulo, conteudo, autor) VALUES (?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("sss", $titulo, $conteudo, $autor);
$stmt->execute();
```

### Buscar um Post

```php
$post_id = 1;
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$resultado = $stmt->get_result();
$post = $resultado->fetch_assoc();
```

### Validar Formulário em JavaScript

```javascript
if (validarFormulario('meuFormulario')) {
    // Formulário é válido, enviar
    document.getElementById('meuFormulario').submit();
}
```

## 🐛 Troubleshooting

### "Erro ao conectar ao banco de dados"
- Verifique se o MySQL está rodando
- Confirme as credenciais em `includes/conexao.php`
- Verifique se o banco `blog_db` existe

### "Nenhum post encontrado"
- Execute o script `database.sql` para criar as tabelas e dados de exemplo
- Verifique se os dados foram inseridos corretamente

### "Erro ao criar/editar post"
- Verifique se os campos estão preenchidos
- Confirme se o PHP tem permissão de escrita no banco de dados
- Verifique os logs de erro do servidor

## 📚 Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL 5.7+
- **Servidor**: Apache/Nginx

## 📄 Licença

Este projeto é fornecido como material educacional para fins acadêmicos.

## 👨‍💻 Autor

Desenvolvido como trabalho avaliativo de Desenvolvimento Web.

---

**Última atualização**: 27 de novembro de 2025
