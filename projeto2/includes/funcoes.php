<?php
function getValidationRules($idMigTabela, $conn) {
    $rules = [];
    
    $query = "SELECT campoSenior, tipo, tamanho, permiteNull, naoZero 
              FROM idMigLayout 
              WHERE idMigTabela = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idMigTabela);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $rules[$row['campoSenior']] = [
            'tipo' => $row['tipo'],
            'tamanho' => $row['tamanho'],
            'permiteNull' => $row['permiteNull'],
            'naoZero' => $row['naoZero']
        ];
    }

    return $rules; // Retorna array associativo com as regras
}
function processCsvFile($file, $idMigTabela, $conn, $errorDir) {
    $messages = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $validationRules = getValidationRules($idMigTabela, $conn);

    foreach ($lines as $lineNumber => $line) {
        $data = str_getcsv($line, ';'); // Supondo ';' como delimitador
        $lineErrors = [];

        foreach ($validationRules as $campo => $rule) {
            $fieldIndex = array_search($campo, array_keys($validationRules));
            if ($fieldIndex === false || !isset($data[$fieldIndex])) {
                continue;
            }

            $fieldValue = trim($data[$fieldIndex]);

            // Verifica se o campo permite nulo e está vazio
            if ($rule['permiteNull'] == 0 && empty($fieldValue)) {
                $lineErrors[] = "Campo '$campo' não pode ser vazio.";
                continue;
            }

            // Validação de tipo e tamanho apenas se o campo tiver um valor
            if (!empty($fieldValue)) {
                if ($rule['tipo'] === 'Numero') {
                    if (!is_numeric($fieldValue)) {
                        $lineErrors[] = "Campo '$campo' deve ser numérico.";
                    } elseif (strlen($fieldValue) > $rule['tamanho']) {
                        $lineErrors[] = "Campo '$campo' deve ter no máximo {$rule['tamanho']} dígitos.";
                    }
                }

                if ($rule['tipo'] === 'Texto' && strlen($fieldValue) > $rule['tamanho']) {
                    $lineErrors[] = "Campo '$campo' excede o limite de {$rule['tamanho']} caracteres.";
                }

                if ($rule['naoZero'] && $fieldValue == '0') {
                    $lineErrors[] = "Campo '$campo' não pode ser zero.";
                }
            }
        }

        if (!empty($lineErrors)) {
            $messages[] = "Linha " . ($lineNumber + 1) . ": " . implode(", ", $lineErrors);
        }
    }

    if (!empty($messages)) {
        $errorFileName = "erros_upload_" . date('Ymd_His') . ".txt";
        $errorFilePath = "$errorDir/$errorFileName";
        file_put_contents($errorFilePath, implode(PHP_EOL, $messages));
        return ['errors' => $messages, 'errorFile' => $errorFilePath];
    }

    return ['success' => true];
}


?>
