<?php
require_once __DIR__ . '/autoload.php';
session_start();

$filmeRepository = new FilmeRepository();
$filme = null;
$mensagem = '';

// Busca o filme para edição
if (isset($_GET['id'])) {
    $filme = $filmeRepository->buscarPorId($_GET['id']);
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = htmlspecialchars($_POST['nome']);
    $ano = intval($_POST['ano']);
    $genero = htmlspecialchars($_POST['genero']);
    $nota = intval($_POST['nota']);

    $imagem = $filmeRepository->processarUploadImagem($_FILES['imagem'] ?? null);
    
    $filmeAtualizado = new Filme($nome, $ano, $genero, $nota, $imagem);
    $filmeAtualizado->setId($id);
    
    if ($filmeRepository->atualizar($filmeAtualizado)) {
        $mensagem = 'Filme atualizado com sucesso!';
        header('Refresh: 2; url=index.php');
    } else {
        $mensagem = 'Erro ao atualizar o filme.';
    }
}

if (!$filme) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Filme</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Editar Filme</h1>
    
    <?php if ($mensagem): ?>
    <div class="mensagem"><?= $mensagem ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
        <input type="hidden" name="id" value="<?= $filme->getId() ?>">
        
        <div class="form-group">
            <label for="nome">Nome do Filme:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($filme->getNome()) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="ano">Ano de Lançamento:</label>
            <input type="number" id="ano" name="ano" min="1900" max="<?= date('Y') ?>" 
                   value="<?= $filme->getAno() ?>" required>
        </div>
        
        <div class="form-group">
            <label for="genero">Gênero:</label>
            <select id="genero" name="genero" required>
                <option value="Ação" <?= $filme->getGenero() === 'Ação' ? 'selected' : '' ?>>Ação</option>
                <option value="Comédia" <?= $filme->getGenero() === 'Comédia' ? 'selected' : '' ?>>Comédia</option>
                <option value="Drama" <?= $filme->getGenero() === 'Drama' ? 'selected' : '' ?>>Drama</option>
                <option value="Ficção Científica" <?= $filme->getGenero() === 'Ficção Científica' ? 'selected' : '' ?>>Ficção Científica</option>
                <option value="Terror" <?= $filme->getGenero() === 'Terror' ? 'selected' : '' ?>>Terror</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Nota:</label>
            <div class="estrelas">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <input type="radio" id="nota<?= $i ?>" name="nota" value="<?= $i ?>" 
                           <?= $filme->getNota() === $i ? 'checked' : '' ?> required>
                    <label for="nota<?= $i ?>">⭐</label>
                <?php endfor; ?>
            </div>
        </div>
        
        <button type="submit">Salvar Alterações</button>
        <a href="index.php" class="botao">Cancelar</a>
    </form>
</body>
</html>