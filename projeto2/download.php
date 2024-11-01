<?php
// Verifica se o parâmetro 'file' está presente na URL
if (isset($_GET['file'])) {
    $file = $_GET['file'];

    // Verifica se o arquivo existe
    if (file_exists($file)) {
        // Define os cabeçalhos para forçar o download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        
        // Limpa o buffer de saída
        ob_clean();
        flush();

        // Lê o arquivo e faz o download
        readfile($file);
        exit;
    } else {
        echo "Arquivo não encontrado.";
    }
} else {
    echo "Parâmetro de arquivo ausente.";
}
?>
