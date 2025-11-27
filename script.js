/**
 * Script JavaScript para validação de formulários e interatividade do blog
 */

// ===================================
// VALIDAÇÃO DE FORMULÁRIOS
// ===================================

/**
 * Valida um formulário antes do envio
 * @param {string} formId - ID do formulário a validar
 * @returns {boolean} - true se válido, false caso contrário
 */
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    
    if (!form) {
        console.error(`Formulário com ID "${formId}" não encontrado.`);
        return false;
    }

    // Limpar mensagens de erro anteriores
    limparErros(formId);

    let isValid = true;
    const campos = form.querySelectorAll('input, textarea, select');

    campos.forEach(campo => {
        const valor = campo.value.trim();
        const tipo = campo.type;
        const nome = campo.name;

        // Validação de campo vazio
        if (valor === '') {
            mostrarErro(campo, `${nome} é obrigatório.`);
            isValid = false;
        }
        // Validação de email
        else if (tipo === 'email' && !validarEmail(valor)) {
            mostrarErro(campo, 'Email inválido.');
            isValid = false;
        }
        // Validação de comprimento mínimo
        else if (tipo === 'text' && valor.length < 3) {
            mostrarErro(campo, `${nome} deve ter pelo menos 3 caracteres.`);
            isValid = false;
        }
        // Validação de textarea (mínimo de caracteres)
        else if (campo.tagName === 'TEXTAREA' && valor.length < 10) {
            mostrarErro(campo, `${nome} deve ter pelo menos 10 caracteres.`);
            isValid = false;
        }
    });

    return isValid;
}

/**
 * Valida um endereço de email
 * @param {string} email - Email a validar
 * @returns {boolean} - true se válido, false caso contrário
 */
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Mostra uma mensagem de erro para um campo
 * @param {HTMLElement} campo - Campo do formulário
 * @param {string} mensagem - Mensagem de erro
 */
function mostrarErro(campo, mensagem) {
    campo.classList.add('is-invalid');
    
    // Criar elemento de erro se não existir
    let errorDiv = campo.nextElementSibling;
    if (!errorDiv || !errorDiv.classList.contains('error-message')) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        campo.parentNode.insertBefore(errorDiv, campo.nextSibling);
    }
    
    errorDiv.textContent = mensagem;
    errorDiv.style.display = 'block';
}

/**
 * Limpa as mensagens de erro de um formulário
 * @param {string} formId - ID do formulário
 */
function limparErros(formId) {
    const form = document.getElementById(formId);
    const campos = form.querySelectorAll('input, textarea, select');
    const erros = form.querySelectorAll('.error-message');

    campos.forEach(campo => {
        campo.classList.remove('is-invalid');
    });

    erros.forEach(erro => {
        erro.style.display = 'none';
    });
}

// ===================================
// MANIPULAÇÃO DE MODAIS
// ===================================

/**
 * Abre um modal
 * @param {string} modalId - ID do modal
 */
function abrirModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Fecha um modal
 * @param {string} modalId - ID do modal
 */
function fecharModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

/**
 * Fecha modal ao clicar fora do conteúdo
 */
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        fecharModal(event.target.id);
    }
});

/**
 * Fecha modal ao pressionar ESC
 */
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modais = document.querySelectorAll('.modal');
        modais.forEach(modal => {
            if (modal.style.display === 'flex') {
                fecharModal(modal.id);
            }
        });
    }
});

// ===================================
// CONFIRMAÇÃO DE EXCLUSÃO
// ===================================

/**
 * Confirma exclusão de um item
 * @param {string} mensagem - Mensagem de confirmação
 * @returns {boolean} - true se confirmado, false caso contrário
 */
function confirmarExclusao(mensagem = 'Tem certeza que deseja excluir este item?') {
    return confirm(mensagem);
}

// ===================================
// MANIPULAÇÃO DE MENSAGENS
// ===================================

/**
 * Mostra uma mensagem temporária
 * @param {string} mensagem - Texto da mensagem
 * @param {string} tipo - Tipo da mensagem ('success', 'error', 'info')
 * @param {number} duracao - Duração em milissegundos (padrão: 3000)
 */
function mostrarMensagem(mensagem, tipo = 'info', duracao = 3000) {
    const div = document.createElement('div');
    div.className = `alert alert-${tipo}`;
    div.textContent = mensagem;
    div.style.position = 'fixed';
    div.style.top = '20px';
    div.style.right = '20px';
    div.style.zIndex = '9999';
    div.style.maxWidth = '400px';

    document.body.appendChild(div);

    // Remover mensagem após a duração especificada
    setTimeout(() => {
        div.remove();
    }, duracao);
}

// ===================================
// FORMATAÇÃO DE DATAS
// ===================================

/**
 * Formata uma data para formato legível
 * @param {string} dataString - Data em formato ISO (YYYY-MM-DD HH:MM:SS)
 * @returns {string} - Data formatada (ex: "27 de novembro de 2025")
 */
function formatarData(dataString) {
    const opcoes = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR', opcoes);
}

// ===================================
// BUSCA E FILTROS
// ===================================

/**
 * Filtra posts por termo de busca
 * @param {string} termoBusca - Termo a buscar
 */
function filtrarPosts(termoBusca) {
    const posts = document.querySelectorAll('.post-card');
    const termo = termoBusca.toLowerCase();

    posts.forEach(post => {
        const titulo = post.querySelector('h2').textContent.toLowerCase();
        const conteudo = post.querySelector('.post-excerpt').textContent.toLowerCase();

        if (titulo.includes(termo) || conteudo.includes(termo)) {
            post.style.display = 'block';
        } else {
            post.style.display = 'none';
        }
    });
}

// ===================================
// EVENTOS DE CARREGAMENTO
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // Adicionar estilos de erro ao CSS dinamicamente
    const style = document.createElement('style');
    style.textContent = `
        input.is-invalid,
        textarea.is-invalid,
        select.is-invalid {
            border-color: #e74c3c !important;
            background-color: #fdeaea;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: none;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }

        .modal-header h2 {
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }

        .close-btn:hover {
            color: #333;
        }
    `;
    document.head.appendChild(style);

    // Inicializar busca em tempo real (se houver campo de busca)
    const campoBusca = document.getElementById('busca');
    if (campoBusca) {
        campoBusca.addEventListener('keyup', function() {
            filtrarPosts(this.value);
        });
    }
});

// ===================================
// FUNÇÕES AUXILIARES
// ===================================

/**
 * Trunca um texto para um número máximo de caracteres
 * @param {string} texto - Texto a truncar
 * @param {number} maxChars - Número máximo de caracteres
 * @returns {string} - Texto truncado com "..."
 */
function truncarTexto(texto, maxChars = 150) {
    if (texto.length > maxChars) {
        return texto.substring(0, maxChars) + '...';
    }
    return texto;
}

/**
 * Copia um texto para a área de transferência
 * @param {string} texto - Texto a copiar
 */
function copiarParaAreaTransferencia(texto) {
    navigator.clipboard.writeText(texto).then(() => {
        mostrarMensagem('Copiado para a área de transferência!', 'success', 2000);
    }).catch(() => {
        mostrarMensagem('Erro ao copiar para a área de transferência.', 'error');
    });
}
