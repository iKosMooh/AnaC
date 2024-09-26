// Função para enviar o formulário via AJAX
function submitForm(operation, tabela, formID, inputIdentifier = null) {
    // Verifica se todos os campos obrigatórios estão preenchidos
    var isValid = true;
    $('#' + formID + ' [required]').each(function() {
        if (!$(this).val()) {
            isValid = false;  // Se algum campo obrigatório não estiver preenchido, marca como inválido
            $(this).addClass('input-error'); // Adiciona uma classe para destacar o campo (opcional)
        } else {
            $(this).removeClass('input-error'); // Remove a classe se o campo estiver preenchido
        }
    });

    if (!isValid && operation !== 'delete' && operation !== 'read') {
        alert('Por favor, preencha todos os campos obrigatórios marcados com *'); // Mensagem de erro
        return; // Interrompe a execução se houver campos não preenchidos
    }

    // Serializa os dados do formulário
    var formData = $('#' + formID).serialize() + '&tabela=' + tabela + '&operation=' + operation;

    // Adiciona o campo de chave primária (se presente) ao enviar os dados
    if (inputIdentifier !== null ) {
        formData += '&' + inputIdentifier + '=' + $('#' + inputIdentifier).val() + '&inputIdentifier=' + inputIdentifier;
    }

    $.ajax({
        url: '../php/formCrud.php',  // Arquivo PHP responsável pelo CRUD
        type: 'POST',  // Método POST
        data: formData,  // Dados do formulário
        success: function(response) {
            $('#response').html(response);  // Exibe a resposta do PHP na página
        },
        error: function(xhr, status, error) {
            $('#response').html('Erro: ' + error);  // Exibe erro, se houver
        }
    });
}

function addRequiredAsterisksToAllForms() {
    // Seleciona todos os inputs obrigatórios em todos os formulários na página
    $('form [required]').each(function() {
        // Cria um novo elemento de span para o asterisco
        var asterisk = $('<span style="color: red; margin-right: 4px;">*</span>');
        // Adiciona o asterisco antes da label do input correspondente
        $(this).prev('label').before(asterisk);
    });
}



$(document).ready(function() {
    addRequiredAsterisksToAllForms(); // Passa o ID do seu formulário
});