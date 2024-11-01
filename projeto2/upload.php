<?php
include 'includes/conexao.php';

// Obter os layouts disponíveis para seleção
$query = "SELECT idMigTabela, nome FROM idMigTabela";
$result = $conn->query($query);

if (!$result) {
    die("Erro ao buscar layouts: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivo</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container my-5">
    <!-- Botão Voltar -->
    <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Voltar</a>
    
    <h1 class="text-center mb-4">Upload de Arquivo</h1>
    
    <!-- Área para exibir mensagens de resposta -->
    <div id="responseMessage" class="mb-4"></div>

    <form id="uploadForm" action="processa.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="layout">Selecione o Layout:</label>
            <select class="form-control" name="layout" id="layout" required>
                <option value="">Escolha um layout</option>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($layout = $result->fetch_assoc()): ?>
                        <option value="<?= $layout['idMigTabela']; ?>">
                            <?= htmlspecialchars($layout['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option disabled>Nenhum layout disponível</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="csv_file">Escolha o arquivo CSV:</label>
            <input type="file" class="form-control-file" name="csv_file" id="csv_file" required>
        </div>
        <button type="submit" class="btn btn-primary">Fazer Upload</button>
    </form>

    <!-- Barra de progresso do upload -->
    <div class="progress mt-4">
        <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const responseMessage = document.getElementById('responseMessage');
    const progressBar = document.querySelector('.progress-bar');
    progressBar.style.width = '0%';

    fetch('processa.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            responseMessage.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sucesso!</strong> ${data.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
        } else if (data.status === 'error') {
            // Exibir erros de forma amigável
            responseMessage.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Foram encontrados alguns erros!</h4>
                    <p>Verifique os detalhes abaixo para corrigir os erros:</p>
                    <hr>
                    <ul class="list-group mb-3">
                        ${data.errors.map(error => `<li class="list-group-item list-group-item-danger"><i class="fas fa-exclamation-circle"></i> ${error}</li>`).join('')}
                    </ul>
                    <a href="${data.errorFile}" class="btn btn-warning mt-2"><i class="fas fa-download"></i> Baixar Relatório de Erros</a>
                </div>`;
        }
    })
    .catch(error => {
        responseMessage.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro!</strong> Ocorreu um problema ao processar o upload.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
    });
});
</script>

</body>
</html>
