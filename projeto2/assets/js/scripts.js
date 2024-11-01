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
            responseMessage.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        } else if (data.status === 'error') {
            responseMessage.innerHTML = `<div class="alert alert-danger"><p>Erros encontrados:</p><ul>${data.errors.map(error => `<li>${error}</li>`).join('')}</ul><a href="${data.errorFile}" class="btn btn-warning mt-2">Baixar Relatório de Erros</a></div>`;
        }
    })
    .catch(error => {
        responseMessage.innerHTML = `<div class="alert alert-danger">Erro ao processar o upload.</div>`;
    });
});


