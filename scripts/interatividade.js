// scripts/interatividade.js
// Funcionalidades de interação adicionais, executadas exclusivamente no browser
// (Exercício 1c e Exercício 2 do Guião W3)

document.addEventListener('DOMContentLoaded', function () {

    // ═════════════════════════════════════════════
    // Exercício 1c — Adicionar ao Carrinho (produto.php)
    // Valida a quantidade e atualiza o contador no cabeçalho (DOM)
    // ═════════════════════════════════════════════
    let botaoAdicionar = document.getElementById('botao-adicionar');

    if (botaoAdicionar) {
        let totalCarrinho = 0; // variável local, guarda o total adicionado nesta página

        botaoAdicionar.addEventListener('click', function () {
            let inputQtd = document.getElementById('quantidade');
            let quantidade = parseInt(inputQtd.value);
            let stockMaximo = parseInt(inputQtd.max);

            if (isNaN(quantidade) || quantidade < 1) {
                alert('Introduz uma quantidade válida.');
                return;
            }

            if (quantidade > stockMaximo) {
                alert('Não é possível adicionar mais do que o stock disponível (' +
                      stockMaximo + ' unidades).');
                return;
            }

            // Atualiza o contador do carrinho no cabeçalho
            totalCarrinho += quantidade;
            let contador = document.getElementById('contador-carrinho');
            if (contador) {
                contador.textContent = totalCarrinho;
            }

            alert(quantidade + ' unidade(s) adicionada(s) ao carrinho!');
        });
    }


    // ═════════════════════════════════════════════
    // Exercício 2b — Mostrar/Esconder "Sobre o Projeto" (index.html)
    // Operação sobre objetos DOM: adiciona/remove uma classe para
    // mostrar ou esconder um elemento por interação do utilizador
    // ═════════════════════════════════════════════
    let botaoSobre = document.getElementById('botao-sobre');
    let caixaSobre = document.getElementById('caixa-sobre');

    if (botaoSobre && caixaSobre) {
        botaoSobre.addEventListener('click', function () {
            caixaSobre.classList.toggle('escondido');

            if (caixaSobre.classList.contains('escondido')) {
                botaoSobre.textContent = 'Sobre o Projeto';
            } else {
                botaoSobre.textContent = 'Fechar';
            }
        });
    }

});
