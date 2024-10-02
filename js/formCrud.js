// Função para enviar o formulário via AJAX
function submitForm(operation, tabela, formID = null, inputIdentifier = null) {
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
        return Promise.reject('Campos obrigatórios não preenchidos'); // Retorna um erro
    }

    // Serializa os dados do formulário
    var formData = $('#' + formID).serialize() + '&tabela=' + tabela + '&operation=' + operation;

    // Adiciona o campo de chave primária (se presente) ao enviar os dados
    if (inputIdentifier !== null) {
        formData += '&' + inputIdentifier + '=' + $('#' + inputIdentifier).val() + '&inputIdentifier=' + inputIdentifier;
    }

    // Retorna uma Promise para o AJAX
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../php/formCrud.php',  // Arquivo PHP responsável pelo CRUD
            type: 'POST',  // Método POST
            data: formData,  // Dados do formulário
            success: function(response) {
                $('#response').html(response);  // Exibe a resposta do PHP na página
                console.log(response);
                resolve(response); // Resolve a Promise com a resposta
            },
            error: function(xhr, status, error) {
                $('#response').html('Erro: ' + error);  // Exibe erro, se houver
                console.log(error);
                reject(error); // Rejeita a Promise em caso de erro
            }
        });
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
    addRequiredAsterisksToAllForms(); // Adiciona asteriscos aos campos obrigatórios
});
