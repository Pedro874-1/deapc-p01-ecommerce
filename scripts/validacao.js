// scripts/validacao.js
// Validacao dos formularios da aplicacao ShopOnline
// Baseado no documento de apoio W3: usa addEventListener (event handlers),
// que é a abordagem recomendada em detrimento de eventos inline (onclick="...")

document.addEventListener('DOMContentLoaded', function () {

    // ───── Funções auxiliares (reutilizadas por todos os formulários) ─────

    // Marca o campo a vermelho e mostra a mensagem de erro por baixo
    function mostrarErro(campo, mensagem) {
        campo.classList.add('campo-erro');
        let span = campo.nextElementSibling;
        if (span && span.classList.contains('mensagem-erro')) {
            span.textContent = mensagem;
        }
    }

    // Remove o erro de um campo (quando o preenchimento está correto)
    function limparErro(campo) {
        campo.classList.remove('campo-erro');
        let span = campo.nextElementSibling;
        if (span && span.classList.contains('mensagem-erro')) {
            span.textContent = '';
        }
    }

    // Verifica se um campo de texto foi preenchido
    function campoPreenchido(campo, mensagem) {
        let valor = campo.value.trim();
        if (valor === '') {
            mostrarErro(campo, mensagem);
            return false;
        }
        limparErro(campo);
        return true;
    }

    // Verifica se o email tem um formato válido (expressão regular simples)
    function emailValido(campo) {
        let valor = campo.value.trim();
        let padrao = /^\S+@\S+\.\S+$/;
        if (!padrao.test(valor)) {
            mostrarErro(campo, 'Introduz um email válido (ex: nome@exemplo.com)');
            return false;
        }
        limparErro(campo);
        return true;
    }


    // ═════════════════════════════════════════════
    // FORMULÁRIO DE LOGIN (cliente.php)
    // ═════════════════════════════════════════════
    let formLogin = document.getElementById('form-login');
    if (formLogin) {
        formLogin.addEventListener('submit', function (evento) {
            let valido = true;

            let email = document.getElementById('email-login');
            let password = document.getElementById('password-login');

            if (!campoPreenchido(email, 'O email é obrigatório')) {
                valido = false;
            } else if (!emailValido(email)) {
                valido = false;
            }

            if (!campoPreenchido(password, 'A password é obrigatória')) {
                valido = false;
            }

            // Se algum campo for inválido, impede o envio do formulário
            if (!valido) {
                evento.preventDefault();
            }
        });
    }


    // ═════════════════════════════════════════════
    // FORMULÁRIO DE REGISTO (cliente.php)
    // ═════════════════════════════════════════════
    let formRegisto = document.getElementById('form-registo');
    if (formRegisto) {
        formRegisto.addEventListener('submit', function (evento) {
            let valido = true;

            let nome = document.getElementById('nome-registo');
            let email = document.getElementById('email-registo');
            let password = document.getElementById('pass-registo');
            let confirmar = document.getElementById('pass-confirmar');

            if (!campoPreenchido(nome, 'O nome é obrigatório')) {
                valido = false;
            }

            if (!campoPreenchido(email, 'O email é obrigatório')) {
                valido = false;
            } else if (!emailValido(email)) {
                valido = false;
            }

            if (!campoPreenchido(password, 'A password é obrigatória')) {
                valido = false;
            } else if (password.value.length < 6) {
                mostrarErro(password, 'A password deve ter pelo menos 6 caracteres');
                valido = false;
            }

            if (!campoPreenchido(confirmar, 'Confirma a password')) {
                valido = false;
            } else if (confirmar.value !== password.value) {
                mostrarErro(confirmar, 'As passwords não coincidem');
                valido = false;
            } else {
                limparErro(confirmar);
            }

            if (!valido) {
                evento.preventDefault();
            }
        });
    }


    // ═════════════════════════════════════════════
    // FORMULÁRIO DE MORADA / CHECKOUT (carrinho.html)
    // ═════════════════════════════════════════════
    let formCheckout = document.getElementById('form-checkout');
    if (formCheckout) {
        formCheckout.addEventListener('submit', function (evento) {
            let valido = true;

            let nome = document.getElementById('nome');
            let morada = document.getElementById('morada');
            let cidade = document.getElementById('cidade');
            let cp = document.getElementById('cp');
            let telemovel = document.getElementById('telemovel');

            if (!campoPreenchido(nome, 'O nome é obrigatório')) valido = false;
            if (!campoPreenchido(morada, 'A morada é obrigatória')) valido = false;
            if (!campoPreenchido(cidade, 'A cidade é obrigatória')) valido = false;

            if (!campoPreenchido(cp, 'O código postal é obrigatório')) {
                valido = false;
            } else {
                let padraoCp = /^\d{4}-\d{3}$/;
                if (!padraoCp.test(cp.value.trim())) {
                    mostrarErro(cp, 'Formato inválido. Usa 0000-000');
                    valido = false;
                } else {
                    limparErro(cp);
                }
            }

            if (!campoPreenchido(telemovel, 'O telemóvel é obrigatório')) {
                valido = false;
            } else {
                let padraoTlm = /^9\d{8}$/;
                if (!padraoTlm.test(telemovel.value.trim())) {
                    mostrarErro(telemovel, 'Introduz um número válido (9 dígitos, ex: 912345678)');
                    valido = false;
                } else {
                    limparErro(telemovel);
                }
            }

            if (!valido) {
                evento.preventDefault();
            }
        });
    }


    // ═════════════════════════════════════════════
    // FORMULÁRIO DE ADICIONAR PRODUTO (admin.html)
    // ═════════════════════════════════════════════
    let formProduto = document.getElementById('form-produto');
    if (formProduto) {
        formProduto.addEventListener('submit', function (evento) {
            let valido = true;

            let nome = document.getElementById('nome-produto');
            let categoria = document.getElementById('categoria-produto');
            let preco = document.getElementById('preco-produto');
            let stock = document.getElementById('stock-produto');

            if (!campoPreenchido(nome, 'O nome do produto é obrigatório')) valido = false;
            if (!campoPreenchido(categoria, 'Escolhe uma categoria')) valido = false;

            if (!campoPreenchido(preco, 'O preço é obrigatório')) {
                valido = false;
            } else if (isNaN(preco.value) || Number(preco.value) <= 0) {
                mostrarErro(preco, 'O preço deve ser um número maior que 0');
                valido = false;
            } else {
                limparErro(preco);
            }

            if (!campoPreenchido(stock, 'O stock é obrigatório')) {
                valido = false;
            } else if (isNaN(stock.value) || Number(stock.value) < 0) {
                mostrarErro(stock, 'O stock deve ser um número igual ou maior que 0');
                valido = false;
            } else {
                limparErro(stock);
            }

            // Este formulário ainda não está ligado a uma script PHP (ver Guião W2),
            // por isso por agora apenas validamos e mostramos confirmação
            evento.preventDefault();
            if (valido) {
                alert('Produto validado com sucesso! (Falta ligar este formulário à script PHP de inserção)');
                formProduto.reset();
            }
        });
    }

});
