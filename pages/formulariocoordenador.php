<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Faltas de Professores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            display: block;
            margin: 0 auto;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #4cae4c;
        }

        .hidden {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .search-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .search-container input {
            width: 200px; /* Largura da caixa de busca */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
    <script>
        function toggleProposta(select) {
            const propostaField = document.getElementById('proposta_reposicao');
            propostaField.style.display = select.value === "Fazer Nova Proposta" ? 'block' : 'none';
        }

        function showProfessorId(select) {
            const idField = document.getElementById('id_professor');
            const idFieldContainer = document.getElementById('id_professor_field');
            const dataFaltaField = document.getElementById('data_falta_field');
            const propostaField = document.getElementById('propostasTable');
            const selectedIndex = select.selectedIndex;

            if (selectedIndex > 0) {
                idField.value = select.options[selectedIndex].getAttribute('data-id');
                idFieldContainer.classList.remove('hidden');
                dataFaltaField.classList.remove('hidden');
                propostaField.classList.remove('hidden');
            } else {
                idField.value = '';
                idFieldContainer.classList.add('hidden');
                dataFaltaField.classList.add('hidden');
                propostaField.classList.add('hidden');
            }
        }
    </script>
</head>
<body>

    <form id="faltaForm" method="POST">
        <label for="professor_id">Professor:</label>
        <select id="professor_id" name="professor_id" onchange="showProfessorId(this)" required>
            <option value="">Selecione um Professor</option>
            <option value="1" data-id="1">Professor A</option>
            <option value="2" data-id="2">Professor B</option>
            <option value="3" data-id="3">Professor C</option>
        </select>

        <div id="id_professor_field" class="hidden">
            <label for="id_professor">ID Professor:</label>
            <input type="text" id="id_professor" name="id_professor" class="hidden" readonly>
        </div>

        <div id="data_falta_field" class="hidden">
            <div>
                <label>Data da Falta:</label>
                <input type="text" id="data_falta" name="data_falta" value="29/02/24" readonly>
            </div>
            <div>
                <label>Quantidade de Aulas Perdidas:</label>
                <input type="text" id="quantidade_aulas" name="quantidade_aulas" value="2" readonly>
            </div>
        </div>

        <div class="hidden" id="propostasTable">
            <h3>Proposta do Professor</h3>
            <table>
                <thead>
                    <tr>
                        <th>Data da Proposta</th>
                        <th>Hora de Início</th>
                        <th>Hora de Fim</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>30/02/24</td>
                        <td>14:00</td>
                        <td>16:00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <label for="status_proposta">Status da Proposta:</label>
        <select id="status_proposta" name="status_proposta" onchange="toggleProposta(this)">
            <option value="">Selecione</option>
            <option value="Fazer Nova Proposta">Fazer Nova Proposta</option>
            <option value="Aula Reposta">Aula Reposta</option>
            <option value="Aula Não Reposta">Aula Não Reposta</option>
        </select>
        
        <div id="proposta_reposicao" class="hidden">
            <label for="nova_proposta_data">Nova Proposta de Reposição (Data):</label>
            <input type="date" id="nova_proposta_data" name="nova_proposta_data">

            <div>
                <label for="nova_proposta_hora_inicio">Hora de Início:</label>
                <input type="time" id="nova_proposta_hora_inicio" name="nova_proposta_hora_inicio">
            </div>
            <div>
                <label for="nova_proposta_hora_fim">Hora de Fim:</label>
                <input type="time" id="nova_proposta_hora_fim" name="nova_proposta_hora_fim">
            </div>

            <label for="motivo_proposta">Motivo da Proposta:</label>
            <textarea id="motivo_proposta" name="motivo_proposta" placeholder="Digite o motivo aqui..." required></textarea>
        </div>

        <button type="submit">Enviar</button>
    </form>

    <h2>Faltas Registradas</h2>

    <div class="search-container">
        <input type="text" placeholder="Buscar por ID do Professor" />
    </div>

    <table>
        <thead>
            <tr>
                <th>Professor</th>
                <th>ID Professor</th>
                <th>Data da Falta</th>
                <th>Quantidade de Aulas Perdidas</th>
                <th>Proposta do Professor</th>
                <th>Status da Reposição</th>
            </tr>
        </thead>
        <tbody>
            <!-- Exemplo de linha de faltas registradas -->
            <tr>
                <td>Professor A</td>
                <td>1</td>
                <td>29/02/24</td>
                <td>2</td>
                <td>Data: 30/02/24, Hora: 14:00 - 16:00</td>
                <td>Aguardando Confirmação</td>
            </tr>
            <!-- Mais linhas podem ser adicionadas aqui a partir do banco de dados -->
        </tbody>
    </table>

</body>
</html>
