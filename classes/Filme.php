<?php

class Filme {
    private $id;
    private $nome;
    private $ano;
    private $genero;
    private $nota;
    private $imagem;
    
    public function __construct($nome, $ano, $genero, $nota, $imagem = null) {
        $this->nome = $nome;
        $this->ano = $ano;
        $this->genero = $genero;
        $this->nota = $nota;
        $this->imagem = $imagem;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }
    
    public function getAno() {
        return $this->ano;
    }
    
    public function getGenero() {
        return $this->genero;
    }
    
    public function getNota() {
        return $this->nota;
    }

    public function getImagem() {
        return $this->imagem;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }
}