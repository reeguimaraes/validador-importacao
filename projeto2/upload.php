<?php
include 'includes/conexao.php';
include 'includes/funcoes.php';

// Inicializa um array para mensagens
$messages = [];

// Cria o diretório de erros se não existir
$errorDir = 'erros_upload';
if (!is_dir($errorDir)) {
    mkdir($errorDir);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file']) && isset($_POST['layout'])) {
    $idMigTabela = $_POST['layout']; // Supondo que o layout contém o idMigTabela
    $file = $_FILES['csv_file']['tmp_name'];

    if (file_exists($file)) {
        // Processa o arquivo CSV e verifica se houve erros
        $hasErrors = processCsvFile($file, $idMigTabela, $conn, $errorDir);

        // Mensagem de retorno
        if ($hasErrors) {
            $messages[] = "Foram encontrados erros no layout.";
        } else {
            $messages[] = "Upload realizado com sucesso!";
        }
    } else {
        $messages[] = "Nenhum arquivo foi enviado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Upload</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="text-center">Resultado do Upload</h1>

    <?php if (!empty($messages)): ?>
        <div class="alert alert-danger">
            <?= $messages[0]; // Exibe a mensagem de erro ?>
        </div>
        <?php if ($hasErrors): ?>
            <a href="<?= $errorDir . '/' . 'erros_upload_' . date('Ymd_His') . '.txt' ?>" class="btn btn-warning">Baixar arquivo de erros</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
