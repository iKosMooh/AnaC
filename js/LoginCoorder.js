document.addEventListener('DOMContentLoaded', function() {
    const formCoordenador = document.getElementById('loginFormCoordenador');

    formCoordenador.addEventListener('submit', function(event) {
        event.preventDefault();

        // Simulação de login bem-sucedido
        const email = document.getElementById('usuarioCoordenador').value;
        const senha = document.getElementById('senhaCoordenador').value;

        // Verifica se os campos estão preenchidos com qualquer valor
        if (email.trim() !== '' && senha.trim() !== '') {
            // Redireciona para o HeaderC.html após o login do coordenador
            window.location.href = '../pages/HeaderC.html'; // Verifique o caminho correto para HeaderC.html
        } else {
            alert('Por favor, preencha os campos de e-mail e senha.');
        }
    });
});
