<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de CSV</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 20px;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        h1 {
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-top: 20px;
        }
        input[type="file"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Upload de Arquivo CSV</h1>
    <p>Escolha o layout do arquivo que você deseja enviar</p>
</div>

<div class="container">
    <form action="upload.php" method="POST" enctype="multipart/form-data" id="uploadForm">
        <label for="layout">Selecione o Layout:</label>
        <select name="layout" id="layout" required>
            <option value="">Selecione um layout</option>
            <?php
            include 'includes/conexao.php';
            $query = "SELECT idMigTabela, nome FROM idMigTabela"; // Ajuste a consulta conforme necessário
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['idMigTabela']}'>{$row['nome']}</option>";
            }
            ?>
        </select>

        <label for="csv_file">Escolha um arquivo CSV:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        
        <input type="submit" value="Enviar">
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
