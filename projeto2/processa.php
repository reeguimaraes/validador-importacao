<?php 
// processa.php

include 'includes/conexao.php';
include 'includes/funcoes.php';

$idMigTabela = $_POST['layout'] ?? null;
$file = $_FILES['csv_file']['tmp_name'];
$errorDir = 'erros_upload';

$response = processCsvFile($file, $idMigTabela, $conn, $errorDir);

header('Content-Type: application/json');

if (isset($response['errors'])) {
    echo json_encode(['status' => 'error', 'errors' => $response['errors'], 'errorFile' => $response['errorFile']]);
} else {
    echo json_encode(['status' => 'success', 'message' => 'Upload concluído com sucesso!']);
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
