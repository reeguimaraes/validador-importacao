<?php
$host = 'localhost'; // Endereço do servidor
$db = 'projeto'; // Nome do banco de dados
$user = 'root'; // Usuário do banco
$pass = ''; // Senha do banco

$conn = new mysqli($host, $user, $pass, $db);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
