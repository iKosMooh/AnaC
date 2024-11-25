window.onload = function() {
    var justificativaSelect = document.getElementById('justificativa');
    var licencaMedicaSelect = document.getElementById('opcao_licenca_medica');
    var faltaJustificadaSelect = document.getElementById('opcao_falta_justificada');
    var faltaInjustificadaSelect = document.getElementById('opcao_falta_injustificada');
    var legislacaoSelect = document.getElementById('opcao_legislacao');
    var docsInput = document.getElementById('docs');

    function toggleVisibility(element, visible) {
        element.style.display = visible ? 'block' : 'none';
    }

    function toggleOptions(selectElement, visible) {
        var options = selectElement.options;
        for (var i = 0; i < options.length; i++) {
            options[i].disabled = !visible;
        }
    }

    function updateOptions() {
        var selectedJustificativa = justificativaSelect.value;

        toggleVisibility(licencaMedicaSelect.parentElement, selectedJustificativa === 'Licença Médica');
        toggleVisibility(faltaJustificadaSelect.parentElement, selectedJustificativa === 'Falta Justificada');
        toggleVisibility(faltaInjustificadaSelect.parentElement, selectedJustificativa === 'Falta Injustificada');
        toggleVisibility(legislacaoSelect.parentElement, selectedJustificativa === 'Faltas previstas na Legislação');
        toggleVisibility(docsInput.parentElement, selectedJustificativa === 'Licença Médica');

        toggleOptions(licencaMedicaSelect, selectedJustificativa === 'Licença Médica');
        toggleOptions(faltaJustificadaSelect, selectedJustificativa === 'Falta Justificada');
        toggleOptions(faltaInjustificadaSelect, selectedJustificativa === 'Falta Injustificada');
        toggleOptions(legislacaoSelect, selectedJustificativa === 'Faltas previstas na Legislação');
        
        if (selectedJustificativa !== 'Licença Médica') {
            docsInput.value = ''; // Limpar o campo de upload se não estiver visível
        }
    }

    justificativaSelect.addEventListener('change', updateOptions);
    updateOptions(); // Chamada inicial para configurar o estado inicial do formulário
}
