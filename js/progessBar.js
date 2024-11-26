$(document).ready(function () {
    let niveis = [25, 50, 75, 100]; // 4 Níveis
    let nivelAtual = 0;

    function atualizarBarra() {
        if (nivelAtual < niveis.length) {
            // Atualiza a largura da barra de progresso
            $('#barra').css('width', niveis[nivelAtual] + '%');
            $('#progresso-texto').text('Nível ' + (nivelAtual + 1) + ': ' + niveis[nivelAtual] + '%');
            nivelAtual++;

            // Se não chegou ao último nível, continue o processo
            if (nivelAtual < niveis.length) {
                setTimeout(atualizarBarra, 1000);  // Espera 1 segundo antes de passar para o próximo nível
            }
        }
    }

    // Começa a animação da barra ao carregar
    atualizarBarra();
});
