<?php
include 'includes/conexao.php';
include 'includes/funcoes.php';

// Inicializa um array para mensagens
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file']) && isset($_POST['layout'])) {
    $idMigTabela = $_POST['layout']; // Supondo que o layout contém o idMigTabela
    $file = $_FILES['csv_file']['tmp_name'];

    if (file_exists($file)) {
        // Processa o arquivo CSV
        $messages = processCsvFile($file, $idMigTabela, $conn);
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
            <?php foreach ($messages as $message): ?>
                <?php if (isset($message['line']) && isset($message['errors'])): ?>
                    Erros na linha <?= $message['line'] ?>: <?= implode(', ', $message['errors']); ?><br>
                <?php else: ?>
                    <?= $message; ?><br>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
