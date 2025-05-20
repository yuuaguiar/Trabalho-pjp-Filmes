<?php

class FilmeRepository {
    private $filmes = [];
    
    public function __construct() {
        if (!isset($_SESSION['filmes'])) {
            $_SESSION['filmes'] = [];
        }
        $this->filmes = &$_SESSION['filmes'];
        $this->validarDadosArmazenados();
    }
    
    /**
     * Valida e corrige os dados armazenados na sessão
     */
    private function validarDadosArmazenados() {
        foreach ($this->filmes as $key => $filme) {
            // Se for uma string serializada, tenta converter para objeto
            if (is_string($filme)) {
                try {
                    $filme = unserialize($filme);
                    if ($filme instanceof Filme) {
                        $this->filmes[$key] = $filme;
                        continue;
                    }
                } catch (Exception $e) {
                    // Ignora erros de desserialização
                }
            }
            
            // Remove itens inválidos
            if (!($filme instanceof Filme)) {
                unset($this->filmes[$key]);
            }
        }
    }

    public function processarUploadImagem($arquivoImagem) {
    if (!$arquivoImagem || $arquivoImagem['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensao = pathinfo($arquivoImagem['name'], PATHINFO_EXTENSION);
    $nomeUnico = uniqid() . '.' . $extensao;
    $diretorioImagens = __DIR__ . '/../assets/img/filmes/';

    if (!is_dir($diretorioImagens)) {
        mkdir($diretorioImagens, 0777, true);
    }

    $caminhoCompleto = $diretorioImagens . $nomeUnico;

    if (move_uploaded_file($arquivoImagem['tmp_name'], $caminhoCompleto)) {
        return 'assets/img/filmes/' . $nomeUnico;
    }

    return null;
    }
    
    /**
     * Salva ou atualiza um filme no repositório
     */
   public function salvar(Filme $filme) {
    if (!$filme->getId()) {
        $filme->setId($this->gerarIdUnico($filme));
    }
    
    // Se for upload de nova imagem
    if ($filme->getImagem() === null && isset($this->filmes[$filme->getId()])) {
        // Mantém a imagem existente se não for enviada nova
        $filme->setImagem($this->filmes[$filme->getId()]->getImagem());
    }
    
    $this->filmes[$filme->getId()] = $filme;
    return $filme;
    }
    
    /**
     * Gera um ID único para o filme
     */
    private function gerarIdUnico(Filme $filme) {
        return md5($filme->getNome() . $filme->getAno() . microtime(true) . rand(1000, 9999));
    }
    
    /**
     * Retorna todos os filmes válidos
     */
    public function listarTodos() {
        return array_filter($this->filmes, function($filme) {
            return $filme instanceof Filme;
        });
    }
    
    /**
     * Calcula a média das notas dos filmes
     */
    public function calcularMedia() {
        $filmes = $this->listarTodos();
        if (empty($filmes)) {
            return 0;
        }
        
        $total = array_reduce($filmes, function($carry, $filme) {
            return $carry + $filme->getNota();
        }, 0);
        
        return round($total / count($filmes), 1);
    }
    
    /**
     * Filtra filmes por gênero
     */
    public function filtrarPorGenero($genero) {
        return array_filter($this->listarTodos(), function($filme) use ($genero) {
            return $filme->getGenero() === $genero;
        });
    }
    
    /**
     * Limpa todos os filmes (apenas para desenvolvimento)
     */
    public function limparDados() {
        $this->filmes = [];
        $_SESSION['filmes'] = [];
    }

    // Dentro da classe FilmeRepository

/**
 * Remove um filme pelo ID
 */
public function excluir($id) {
    if (isset($this->filmes[$id])) {
        unset($this->filmes[$id]);
        return true;
    }
    return false;
}

/**
 * Busca um filme pelo ID
 */
public function buscarPorId($id) {
    return $this->filmes[$id] ?? null;
}

/**
 * Atualiza um filme existente
 */
public function atualizar(Filme $filmeAtualizado) {
    if (isset($this->filmes[$filmeAtualizado->getId()])) {
        $this->filmes[$filmeAtualizado->getId()] = $filmeAtualizado;
        return true;
    }
    return false;
}

private function getImagemPadraoPorGenero($genero) {
    $cores = [
        'Ação' => 'ff0000',
        'Comédia' => 'ffff00',
        'Drama' => '0000ff',
        'Ficção Científica' => '00ff00',
        'Terror' => '000000'
    ];
    
    $cor = $cores[$genero] ?? 'cccccc';
    return null; // Ou retorne um caminho para imagem padrão
}




}