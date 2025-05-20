<?php
require_once __DIR__ . '/autoload.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $filmeRepository = new FilmeRepository();
    if ($filmeRepository->excluir($_POST['id'])) {
        $_SESSION['mensagem'] = 'Filme excluído com sucesso!';
    } else {
        $_SESSION['mensagem'] = 'Erro ao excluir o filme.';
    }
}

header('Location: index.php');
exit;