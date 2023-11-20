<?php

include_once ("DAO_DataBase.php");
include_once ("resources_db/ColumnsModel.php");


class DAO_Table {

    private static DAO_DataBase $db;
    public $colunas = [];
    public $relacionamentos = [];
    public $nome_tabela;

    public function __construct(String $nome_tabela) {
        $this->nome_tabela = $nome_tabela;

        DAO_Table::$db = new DAO_DataBase();  
    }

    public function SetColumn(Column $col) {
        $this->colunas[] = $col;
    }

    public function SetRelacionamentos(Relacionamento $relacionamento): bool {
        if ($this->TableExist($relacionamento->tabela_estrangeira)) {
            Registro("DAO_Table","SetRelacionamentos","Tabela estrangeira encontrada, executando relacionamento!");
            
            Registro("DAO_Table","SetRelacionamentos","Relacionamento criado!<br>Coluna: " .$relacionamento->coluna_tabela->nome . "<br>Chave Estrangeira: ". $relacionamento->tabela_estrangeira);
            $this->relacionamentos[] = $relacionamento;
            return true;
        } else {
            Registro("DAO_Table","SetRelacionamentos","Erro ao criar relacionamento!<br>Tabela ". $relacionamento->tabela_estrangeira . " não existe!!");
            return false;
        }
    }

    public function CreateRelacionamentos() {
        if (count($this->relacionamentos) > 0) {
            
            foreach ($this->relacionamentos as $key => $relacionamento) {
                $sql = "ALTER TABLE $this->nome_tabela ADD CONSTRAINT fk_id_$relacionamento->tabela_estrangeira $relacionamento";
                if (DAO_Table::$db->Query($sql)) {
                    Registro("DAO_Table","CreateRelacionamentos","Relacionamento criado<br>Query: $sql");
                    return true;
                } else {
                    Registro("DAO_Table","CreateRelacionamentos","Erro ao criar relacionamento<br>Query: $sql");
                    return false;
                }
            }

        }
    }

    public function Query(string $sql) {
        return DAO_Table::$db->Query($sql);
    }

    public function initTable() {
        Registro("DAO_Table","Construtor","Construtor da tabela de $this->nome_tabela iniciado...");
        $result = DAO_Table::$db->createTable($this->nome_tabela, $this->colunas, $this->relacionamentos);
        
        if ($result) {
            Registro("DAO_Table","initTable","Tabela $this->nome_tabela iniciada com sucesso!!");
            
        } else {
            Registro("DAO_Table","initTable","Erro ao iniciar tabela $this->nome_tabela!!");
        }

    }

    public function ValueExist(String $coluna, String $value): Array | false {
        $result = DAO_Table::$db->select($this->nome_tabela, [$coluna],"=",[$value]);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function GetColumnsKeys(): Array {
        $colums = [];
        foreach ($this->colunas as $key => $coluna) {
            if ($coluna->nome != 'id') {
                $colums[] = $coluna->nome;
            }
        }
        return $colums;
    }

    public function ValuesIsCompatibletoSave(Array $value) {
        $colunas = array_values($this->GetColumnsKeys());
        $colunas_value = array_keys($value);

        if ($colunas === $colunas_value) {
            Registro("DAO_Table","ValuesIsCompatibletoSave","Os campos são compatíveis com as colunas do banco!");
            return true;
        } else {
            Registro("DAO_Table","ValuesIsCompatibletoSave","Os campos não são compatíveis com as colunas do banco!");

            return false;
        }
    }

    public function Insert(Array $new_value): bool {

        if (!$this->ValuesIsCompatibletoSave($new_value)) {
            return false;
        }

        $result = DAO_Table::$db->insert($this->nome_tabela,$new_value);
        
        if ($result) {
            Registro("DAO_Table","newUser","Valor inserido com sucesso!!<br>Tabela: $this->nome_tabela");
            return true;
        } else {
            Registro("DAO_Table","newUser","Erro ao inserir Valor!!");
            return false;
        }
        
    }

    public function GetById($id): Array | false {
        $result = DAO_Table::$db->selectById($this->nome_tabela,$id);
        if($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function Select(Array $columns, String $criterio, Array $valores): Array | false {
        return DAO_Table::$db->select($this->nome_tabela,$columns, $criterio, $valores);
    }

    public function Update(Array $new_value): bool {
        if (!$this->ValuesIsCompatibletoSave($new_value)) {
            return false;
        } else {
            $result = DAO_Table::$db->update($this->nome_tabela,$new_value);
            if ($result) {
                return true;
            } else {
                return false;
            }
        }
    }


    public function GetColumn(String $nome_coluna) {
        foreach ($this->colunas as $key => $col) {
            if ($col == $nome_coluna) {
                return $col;
            }
        }
        return false;
        
    }

    public function Delete(int $id): bool {
        $result = DAO_Table::$db->deleteById($this->nome_tabela, $id);
        return $result;
    }


    public function TableExist(String $nome_tabela) {
        return DAO_Table::$db->tableExists($nome_tabela);
    }
}


?>