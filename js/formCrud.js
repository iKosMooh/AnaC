let tableKeys = []; // Array para armazenar as chaves das colunas

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
                console.log(formData);
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

function generateTable(data,tableID) /*ESTE TABLE ID NÃO É O NOME DA TABELA NO BANCO SIM NO HTML*/{
    
        const $thead = $('#headerRow'); // tr ou cabeçalho
        const $tbody = $("#"+tableID+ " tbody");
        $tbody.empty(); // Limpa o conteúdo atual da tabela

        // Verifica se data é uma string e tenta parseá-la
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data); // Converte a string JSON para um objeto
            } catch (e) {
                console.error('Erro ao parsear JSON:', e);
                $tbody.append('<tr><td colspan="7" class="no-docs">Erro ao carregar dados.</td></tr>');
                return; // Sai da função se ocorrer um erro
            }
        }

        // Verifica se data é um array
        if (Array.isArray(data) && data.length > 0) {
            // Gera os cabeçalhos dinamicamente com base nas chaves do primeiro objeto
            const firstItem = data[0];
            const keys = Object.keys(firstItem);

            // Adiciona os cabeçalhos à tabela com base nas colunas // MDS VOU CHORAR AKI JÁ NÃO AGUENTO MAIS KKKK Lamentos a quem leu isso
            keys.forEach(key => {
                $thead.append(`<th>${key.replace(/_/g, ' ')}</th>`);
            });
            
            //$thead.append('<th>Ações</th>'); // ADICIONA A COLUNA EXTRA

            // Preenche os dados da tabela
            data.forEach(atestado => {
                const $row = $('<tr></tr>');

                // Gera as células dinamicamente com base nas chaves
                keys.forEach(key => {
                    let cellContent = atestado[key];
                    if (key === 'Docs') {
                        cellContent = `<a href="${cellContent}" target="_blank">Ver Documento</a>`;
                    }
                    $row.append(`<td>${cellContent}</td>`);
                });

                // Adiciona as ações de Aprovar/Negar /// AKI NESTA BOSTA É CRIADO O FORM COM OS INPUTS HIDDEN PQ O HTML NÃOOOO QUER GERAR O FORM NA ROW INTEIRA APENAS DENTRO DO TD NÃO SEI POR QUE, CORINGANDO AKI JÁ 👿 
                // DEVE SER PERSONALIZADO PARA CADA TABELA GERADA INFELIZMENTE
                /*$row.append(`
                    <td>
                        <form id='${atestado.ID_Atestado}'>
                            <input type='hidden' id='ID_Atestado' name='ID_Atestado' value='${atestado.ID_Atestado}'>
                            <input type='hidden' id='Status' name='Status' value=''>
                            <button type="button" class="btn btn-approve" onclick="setStatusAndSubmit('${atestado.ID_Atestado}', 'Aprovado')">Aprovar</button>
                            <button type="button" class="btn btn-deny" onclick="setStatusAndSubmit('${atestado.ID_Atestado}', 'Negado')">Negar</button>
                        </form>
                    </td>
                `);*/

                $tbody.append($row);
            });
        } else {
            $tbody.append('<tr><td colspan="7" class="no-docs">Nenhum dado para a tabela encontrado.</td></tr>');
        }
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


/* MONTE ESSE CARA ASSIM COM THEN E CATCH P N DAR ERRO INFELIZ DE DE JS ASYNC

submitForm("read", "atestado", null, null)
                .then(data => {
                    processResponse(data);
                })
                .catch(error => {
                    console.error('Erro ao carregar atestados:', error);
                    const $tbody = $('#atestadoTable tbody');
                    $tbody.empty(); // Limpa o conteúdo atual da tabela
                    $tbody.append('<tr><td colspan="7" class="no-docs">Erro ao carregar dados.</td></tr>');
                });

*/
