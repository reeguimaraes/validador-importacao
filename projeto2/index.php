<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Sistema</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Sistema de Upload</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="upload.php">Upload</a></li>
        </ul>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Dashboard de Status</h1>
    <div class="row">
        <!-- Card de Informações de Erros -->
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Erros Recentes</div>
                <div class="card-body">
                    <h5 class="card-title">5 Erros Detectados</h5>
                    <p class="card-text">Veja os detalhes no relatório de erros para corrigir problemas nos uploads recentes.</p>
                    <a href="erros_upload/ultimo_relatorio.txt" class="btn btn-light">Baixar Relatório</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Ações Rápidas</div>
                <div class="card-body">
                    <h5 class="card-title">Iniciar Novo Upload</h5>
                    <a href="upload.php" class="btn btn-light">Fazer Upload</a>
                </div>
            </div>
        </div>

        <!-- Card de Informações Gerais -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Status Geral</div>
                <div class="card-body">
                    <h5 class="card-title">Uploads Hoje</h5>
                    <p class="card-text">10 uploads processados hoje com sucesso.</p>
                </div>
            </div>
        </div>

        <!-- Card de Links Úteis -->
       
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
