<?php 
// processa.php

include 'conexao.php';
include 'funcoes.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file']) && isset($_POST['layout'])) {
    $layout = $_POST['layout'];
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, 'r');

    if ($handle) {
        $row = 0; // Contador de linhas
        $messages = []; // Mensagens para feedback

        while (($linha = fgetcsv($handle, 1000, ';')) !== FALSE) {
            // Mapeando as colunas do CSV para um array associativo
            if ($layout === 'layout2') {
                $data = [
                    'CODIGO CLIENTE' => str_pad(trim($linha[0]), 9, '0', STR_PAD_LEFT), // 9 dígitos
                    'RAZAO SOCIAL' => trim($linha[1]),
                    'NOME FANTASIA' => trim($linha[2]),
                    'CPF/CNPJ' => trim($linha[3]),
                    'TIPO PESSOA' => strtoupper(trim($linha[4])),
                    'TIPO MERCADO' => trim($linha[5]),
                    'INSCRICAO ESTADUAL' => trim($linha[6]),
                    'INSCRICAO MUNICIPAL' => trim($linha[7]),
                    'FONE' => trim($linha[8]),
                    'FONE2' => trim($linha[9]),
                    'E-MAIL DOCUMENTOS' => trim($linha[10]),
                    'EMAIL' => trim($linha[11]),
                    'LOGRADOURO' => trim($linha[12]),
                    'NUMERO' => trim($linha[13]),
                    'COMPLEMENTO' => trim($linha[14]),
                    'BAIRRO' => trim($linha[15]),
                    'CEP' => trim($linha[16]),
                    'CIDADE' => trim($linha[17]),
                    'UF' => strtoupper(trim($linha[18])),
                    'SITUACAO' => strtoupper(trim($linha[19])),
                    'CONTRIBUI ICMS' => strtoupper(trim($linha[20])),
                    'CONTA CLIENTE' => str_pad(trim($linha[21]), 7, '0', STR_PAD_LEFT), // até 7 dígitos
                    'CONTA ADTO. CLIENTE' => str_pad(trim($linha[22]), 7, '0', STR_PAD_LEFT), // até 7 dígitos
                ];
            }

            // Validação dos dados
            $errors = validateData($data, $layout);
            if (!empty($errors)) {
                $messages[] = [
                    'type' => 'error',
                    'text' => "Erros na linha $row: " . implode(", ", $errors),
                ];
                $row++;
                continue;  // Pula para a próxima linha se houver erros
            }

            // Se não houver erros, você pode preparar a inserção
            $messages[] = [
                'type' => 'success',
                'text' => "Linha $row validada com sucesso!",
            ];

            // Incrementar o contador de linhas
            $row++;
        }
        fclose($handle);
    } else {
        echo "Erro ao abrir o arquivo.";
    }
} else {
    echo "Nenhum arquivo enviado.";
}

// Exibir mensagens
if (!empty($messages)): ?>
    <div class="container">
        <h1 class="text-center">Resultado do Upload</h1>
        <?php foreach ($messages as $message): ?>
            <div class="alert alert-<?= $message['type'] === 'error' ? 'danger' : 'success'; ?>">
                <?= $message['text']; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
