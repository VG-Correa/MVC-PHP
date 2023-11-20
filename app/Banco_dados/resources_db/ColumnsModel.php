<?php

class Column{

    public String $nome;
    public String $tipo;
    public $tamanho;
    public bool $required;
    public bool $unique;
    public bool $isPK;
    public bool $isAutoIncrement;

    public function __construct(String $nome, String $tipo, int $tamanho, bool $required=false, bool $unique=false, bool $isPk=false, bool $isAutoIncrement=false) {
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->tamanho = $tamanho;
        $this->required = $required;
        $this->unique = $unique;
        $this->isPK = $isPk;
        $this->isAutoIncrement = $isAutoIncrement;
    }

    public function getIsRequired(): String {

        if ($this->required) {
            return "NOT NULL";
        } else {
            return "NULL";
        }

    }

    public function getIsAutoIncrement(): String {
        if ($this->isAutoIncrement) {
            return "AUTO_INCREMENT";
        } else {
            return "";
        }
    }

    public function getTamanho(): String {
        if ($this->tipo == "int" or $this->tipo == "INT" or $this->tipo == "DATE" or $this->tipo == "date") {
            return "";
        } else {
            if ($this->tipo == "TINYINT" or $this->tipo == "tinyint") {
                if ($this->tamanho > 1) {
                    return "(1)";
                } else if ($this-> tamanho < 0) {
                    return "(0)";
                }
            }
            return "($this->tamanho)";
        }
    }

    public function __toString() {
        $nome = $this->nome;

        if ($this->tipo == "BOOLEAN" or $this->tipo == "boolean" or $this->tipo == "bool" or $this->tipo == "BOOL") {
            $this->tipo = "TINYINT"; 
        }

        
        $tipo = $this->tipo;
        $this->tamanho = $this->getTamanho();
        $required = $this->getIsRequired();
        $isAutoIncrement = $this->getIsAutoIncrement();

        $unique = $this->unique ? "UNIQUE" : "";

        $query = "$this->nome $tipo $this->tamanho $required $unique $isAutoIncrement";
        
        if ($this->isPK) {
            $query .= ", PRIMARY KEY ($nome)";
        }

        return $query;
    }

}

class Relacionamento {

    public Column $coluna_tabela;
    public String $tabela_estrangeira;

    public function __construct(Column $coluna_tabela, String $tabela_estrangeira) {
        $this->coluna_tabela = $coluna_tabela;
        $this->tabela_estrangeira = $tabela_estrangeira;
    }

    public function __toString() {
        $FK_column = $this->coluna_tabela->nome;
        $nome_estrangeiro = $this->tabela_estrangeira;

        return "FOREIGN KEY ($FK_column) REFERENCES $nome_estrangeiro (id)";
        
    }
}

?>