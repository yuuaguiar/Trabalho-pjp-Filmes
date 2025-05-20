<?php
require_once __DIR__ . '/autoload.php';
session_start();

$filmeRepository = new FilmeRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagem = $filmeRepository->processarUploadImagem($_FILES['imagem'] ?? null);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
    $nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);

    if ($nome && $ano && $genero && $nota) {
        $filme = new Filme($nome, $ano, $genero, $nota, $imagem);
        $filmeRepository->salvar($filme);
        
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Filme</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Cadastrar Novo Filme</h1>
    
    <form method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
        <div class="form-group">
            <label for="nome">Nome do Filme:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        
        <div class="form-group">
            <label for="ano">Ano de Lançamento:</label>
            <input type="number" id="ano" name="ano" min="1900" max="<?= date('Y') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="genero">Gênero:</label>
            <select id="genero" name="genero" required>
                <option value="">Selecione...</option>
                <option value="Ação">Ação</option>
                <option value="Comédia">Comédia</option>
                <option value="Drama">Drama</option>
                <option value="Ficção Científica">Ficção Científica</option>
                <option value="Terror">Terror</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Nota:</label>
            <div class="estrelas">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <input type="radio" id="nota<?= $i ?>" name="nota" value="<?= $i ?>" required>
                    <label for="nota<?= $i ?>">⭐</label>
                <?php endfor; ?>
            </div>
        </div>

        <div class="form-group">
           <label for="imagem">Capa do Filme:</label>
           <input type="file" id="imagem" name="imagem" accept="image/*">
        </div>
        
        <button type="submit">Salvar</button>
    </form>
    
    <a href="index.php" class="botao">Voltar</a>
    

    <script>
function validarFormulario() {
    // Obtém os elementos do formulário
    const nome = document.getElementById('nome');
    const nota = document.querySelector('input[name="nota"]:checked');
    const campoImagem = document.getElementById('imagem');
    const imagem = campoImagem ? campoImagem.files[0] : null;
    
    // Validação do nome
    if (!nome || !nome.value.trim()) {
        alert('Por favor, informe o nome do filme.');
        nome.focus();
        return false;
    }
    
    // Validação da nota
    if (!nota) {
        alert('Por favor, selecione uma nota para o filme.');
        return false;
    }
    
    // Validação da imagem (se o campo existir e tiver arquivo)
    if (campoImagem && imagem) {
        // Tamanho máximo (2MB)
        const tamanhoMaximo = 2 * 1024 * 1024;
        
        // Tipos permitidos
        const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        
        // Verifica tamanho
        if (imagem.size > tamanhoMaximo) {
            alert('A imagem deve ter no máximo 2MB');
            return false;
        }
        
        // Verifica tipo
        if (!tiposPermitidos.includes(imagem.type)) {
            alert('Apenas imagens nos formatos JPEG, PNG ou GIF são permitidas');
            return false;
        }
    }
    
    return true;
}
</script>

</body>
</html>