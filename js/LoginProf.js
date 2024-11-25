document.addEventListener('DOMContentLoaded', function() {
    const formProfessor = document.getElementById('loginFormProfessor');

    formProfessor.addEventListener('submit', function(event) {
        event.preventDefault();

        // Simulação de login bem-sucedido
        const email = document.getElementById('usuarioProfessor').value;
        const senha = document.getElementById('senhaProfessor').value;

        // Verifica se os campos estão preenchidos com qualquer valor
        if (email.trim() !== '' && senha.trim() !== '') {
            // Redireciona para o HeaderP.html após o login do professor
            window.location.href = '../pages/HeaderP.html'; // Verifique o caminho correto para HeaderP.html
        } else {
            alert('Por favor, preencha os campos de e-mail e senha.');
        }
    });
});
