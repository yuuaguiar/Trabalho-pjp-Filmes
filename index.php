<?php
require_once __DIR__ . '/autoload.php';
session_start();

$filmeRepository = new FilmeRepository();
$filmes = $filmeRepository->listarTodos();
$media = $filmeRepository->calcularMedia();

// Filtro por gênero
$generoFiltro = $_GET['genero'] ?? null;
if ($generoFiltro && $generoFiltro !== 'Todos') {
    $filmes = $filmeRepository->filtrarPorGenero($generoFiltro);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Filmes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Meus Filmes Assistidos</h1>
        <p>Média geral: <?= number_format($media, 1) ?> ⭐</p>
    </header>

    <div class="filtros">
        <label for="genero">Filtrar por gênero:</label>
        <select id="genero" onchange="filtrarPorGenero()">
            <option value="Todos" <?= ($generoFiltro === null || $generoFiltro === 'Todos') ? 'selected' : '' ?>>Todos</option>
            <option value="Ação" <?= $generoFiltro === 'Ação' ? 'selected' : '' ?>>Ação</option>
            <option value="Comédia" <?= $generoFiltro === 'Comédia' ? 'selected' : '' ?>>Comédia</option>
            <option value="Drama" <?= $generoFiltro === 'Drama' ? 'selected' : '' ?>>Drama</option>
            <option value="Ficção Científica" <?= $generoFiltro === 'Ficção Científica' ? 'selected' : '' ?>>Ficção Científica</option>
            <option value="Terror" <?= $generoFiltro === 'Terror' ? 'selected' : '' ?>>Terror</option>
        </select>
    </div>

    <div class="filmes-container">
        <?php foreach ($filmes as $filme): ?>
        <div class="filme-card">
            <div class="filme-capa">
                <?php if ($filme->getImagem()): ?>
                    <img src="<?= $filme->getImagem() ?>" alt="<?= htmlspecialchars($filme->getNome()) ?>">
                <?php else: ?>
                    <div class="capa-padrao" style="background-color: #<?= substr(md5($filme->getGenero()), 0, 6) ?>">
                        <?= substr(htmlspecialchars($filme->getNome()), 0, 2) ?>
                    </div>
                <?php endif; ?>
            </div>
            <h3><?= htmlspecialchars($filme->getNome()) ?></h3>
            <p>Ano: <?= htmlspecialchars($filme->getAno()) ?></p>
            <p>Gênero: <?= htmlspecialchars($filme->getGenero()) ?></p>
            <div class="estrelas">
                <?= str_repeat('⭐', $filme->getNota()) ?>
            </div>
            <div class="acoes">
                <a href="editar.php?id=<?= $filme->getId() ?>" class="botao-editar">Editar</a>
                <form method="POST" action="excluir.php" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $filme->getId() ?>">
                    <button type="submit" class="botao-excluir" onclick="return confirm('Tem certeza que deseja excluir este filme?')">Excluir</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <a href="cadastrar.php" class="botao">Adicionar Filme</a>

    <script src="assets/js/script.js"></script>
</body>
</html>