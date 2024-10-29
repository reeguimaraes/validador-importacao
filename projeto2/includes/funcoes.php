<?php
function getValidationRules($idMigTabela, $conn) {
    $rules = [];
    
    $query = "SELECT * FROM idMigLayout WHERE idMigTabela = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idMigTabela);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $rules[] = $row;
    }

    return $rules;
}

function processCsvFile($file, $idMigTabela, $conn, $errorDir) {
    $messages = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $validationRules = getValidationRules($idMigTabela, $conn);
    $processedCodes = [];
    
    foreach ($lines as $lineNumber => $line) {
        $data = explode(';', $line);
        $lineErrors = [];

        foreach ($validationRules as $rule) {
            $fieldIndex = $rule['sequencia'] - 1; // Ajuste para índice zero
            $fieldValue = $data[$fieldIndex] ?? '';

            // Validações
            if ($rule['permiteNull'] == 0 && empty($fieldValue)) {
                $lineErrors[] = "Campo '{$rule['campoSenior']}' não pode ser vazio.";
            }

            if ($rule['tipo'] === 'Numero' && !preg_match('/^\d{1,' . $rule['tamanho'] . '}$/', $fieldValue)) {
                $lineErrors[] = "Campo '{$rule['campoSenior']}' deve ser um número de até {$rule['tamanho']} dígitos.";
            }

            if ($rule['campoSenior'] === 'CONTRIBUI ICMS' && !in_array($fieldValue, ['S', 'N'])) {
                $lineErrors[] = "Campo 'CONTRIBUI ICMS' deve ser 'S' ou 'N'.";
            }
        }

        // Validação de chave primária
        if (in_array($data[0], $processedCodes)) {
            $lineErrors[] = "Campo 'CODIGO CLIENTE' não pode ser repetido.";
        } else {
            $processedCodes[] = $data[0];
        }

        // Se houver erros, adicione à lista de mensagens
        if (!empty($lineErrors)) {
            $messages[] = [
                'line' => $lineNumber + 1,
                'errors' => $lineErrors
            ];
        }
    }

    // Salvar erros em um arquivo
    if (!empty($messages)) {
        $errorFileName = 'erros_upload_' . date('Ymd_His') . '.txt';
        $errorFilePath = $errorDir . '/' . $errorFileName;
        $errorMessages = "";

        foreach ($messages as $message) {
            $errorMessages .= "Erros na linha {$message['line']}: " . implode(', ', $message['errors']) . "\n";
        }

        file_put_contents($errorFilePath, $errorMessages);
    }

    return !empty($messages); // Retorna verdadeiro se houver erros
}
?>
