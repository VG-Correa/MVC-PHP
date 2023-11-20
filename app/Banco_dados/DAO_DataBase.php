<?php


include_once("./app/log.php");



class DAO_DataBase {

    private static $dbuser;
    private static $dbpassword;
    private static $dbhost;
    private static $database;
    private static $conn;

    public function __construct() {
        DAO_DataBase::connect();
    }

    public static function setDB(
        $dbUser,
        $dbPass,
        $dbHost,
        $dbname
    ) {

        DAO_DataBase::$dbuser = $dbUser;
        DAO_DataBase::$dbpassword = $dbPass;
        DAO_DataBase::$dbhost = $dbHost;
        DAO_DataBase::$database = $dbname;

    }

    private static function connect() {
        if(DAO_DataBase::$conn == null) {
        
            DAO_DataBase::$conn = new mysqli(
                DAO_DataBase::$dbhost,
                DAO_DataBase::$dbuser,
                DAO_DataBase::$dbpassword,
                DAO_DataBase::$database,
                );

            
            Registro("DAO_DataBase", "connect", "Conexão feita com sucesso com Banco de dados: " . DAO_DataBase::$database);
            return DAO_DataBase::$conn;
        }
        
        if (DAO_DataBase::$conn->connect_error) {
            Registro("DAO_DataBase", "connect", DAO_DataBase::$conn->connect_error);
        } 
        

        if (isset(DAO_DataBase::$conn->stat)) {
            Registro("DAO_DataBase", "connect", "Conexão testada, está funcionando: " . DAO_DataBase::$database);
            return DAO_DataBase::$conn;
        } else {
            DAO_DataBase::$conn = new mysqli(
                DAO_DataBase::$dbhost,
                DAO_DataBase::$dbuser,
                DAO_DataBase::$dbpassword,
                DAO_DataBase::$database
                );
            Registro("DAO_DataBase", "connect", "Conexão feita com sucesso com Banco de dados: " . DAO_DataBase::$database);
            return DAO_DataBase::$conn;
        }
    }

    public static function close(): bool {
        if (DAO_DataBase::$conn->ping()) {
            DAO_DataBase::$conn->close();
            Registro("DAO_DataBase","close","Conexão fechada com sucesso");   
            return true;
        } else {
            Registro("DAO_DataBase","close","Conexão já está fechada");   
            return false;
        }
    }

    private function sanitizeValue($value): String {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    private function prepareData(array $data): array {
        foreach ($data as $key => $value) {
            
            $data[$key] = $this->sanitizeValue($value);

        }

        return $data;
    }

    public function insert(String $table, Array $data): bool {
        

        $columns = implode(", ", array_keys($data));
        
        $data = $this->prepareData($data);
        
        $values = "'". implode("', '", array_values($data)) ."'";
        
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        Registro("DAO_DataBase", "insert", "Iniciando query de seleção:<br>$sql");

        $result = DAO_DataBase::connect()->query($sql);

        $this->close();
        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }

    }

    public function deleteById(String $table, int $id): bool {
        $id = $this->sanitizeValue($id);
        $sql = "DELETE FROM $table WHERE id = '$id'";
        $result = DAO_DataBase::connect()->query($sql);
        $this->close();
        if ($result == true) {
            return true;
        } else {
            return false;
        } 
            
    }

    public function update(String $table, Array $data): bool {
        $data = $this->prepareData($data);
        $columns = "";

        foreach ($data as $column => $value) {
            if ($column != "id") {
                $columns .= "$column = '$value', ";
            }
        }
        $columns = rtrim($columns,", ");

        $id = $data["id"];

        $sql = "UPDATE $table SET $columns WHERE id = '$id'";
        $result = DAO_DataBase::connect()->query($sql);

        $this->close();
        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }

    }

    public function select(String $table, Array $columns,String $criterio, Array $values) {

        if (count($columns) !== count($values)) {
            throw new Exception("O número de colunas é diferente dos valores");
        } else {
            $sql = "SELECT * FROM $table WHERE ";

            foreach ($columns as $key => $value) {

                if ($key == 0) {
                    $sql .= "$value $criterio '".$values[$key]."'";
                } else{
                    $sql .= " AND $value $criterio '".$values[$key]."'";
                }
            }

        }

        $result = DAO_DataBase::connect()->query($sql);
       
        $this->close();

        if (isset($result->num_rows) && $result->num_rows > 0) {

            Registro("DAO_DataBase","select","Query retornou consulta<br>Query: $sql");
            return mysqli_fetch_all($result);
        } else if ($result) {
            Registro("DAO_DataBase","select","Consulta retornou vazio!<br>Query: $sql");
            return false;
        } else {
            Registro("DAO_DataBase","select","Erro de consulta!<br>Query: $sql");
            return false;
        }

    }

    public function Query(String $query) {

        $result = DAO_DataBase::connect()->query($query);
        if (isset($result->num_rows)) {
            return mysqli_fetch_all ($result);

        } else {
            return false;
        }

    }

    public function selectById(String $table,int $id): Array | bool {
        return $this->select($table, ["id"], "=", [$id]);
    }

    public function createTable(String $nome_tabela, Array $columns, Array $relacionamentos=[]) {

        $sql = "CREATE TABLE IF NOT EXISTS $nome_tabela ( ";
        foreach ($columns as $key => $coluna) {
            if ($key == 0) {
                $sql .= "$coluna";
            } else {
                $sql .= ", $coluna";
            }
        }

        if ($relacionamentos) {

            foreach ($relacionamentos as $key => $coluna) {
                Registro("DAO_Table", "createTable","Adicionado Relacionamento:<br>$coluna");
                $sql .= ", $coluna";
            }

        }

        $sql .= ")";



        Registro("DAO_DataBase", "createTable","Verificando existência da tabela.");

        if(DAO_DataBase::connect()==null) return Registro("DAO_Database", "createTable","Erro ao conectar");
        
        $selectIfExist = DAO_DataBase::connect()->query("SHOW COLUMNS FROM $nome_tabela");
        if (!$selectIfExist) {
            Registro("DAO_DataBase", "createTable","Tabela $nome_tabela não existe, iniciando criação!:<br>$sql");         
            
            $result = DAO_DataBase::$conn->query($sql);
            $this->close();
    
            Registro("DAO_DataBase", "createTable","Query finalizada! Conexão com banco fechada<br>Query executada:<br><br>$sql");
            
            if ($result != null) {
                Registro("DAO_DataBase", "createTable","Tabela $nome_tabela criada com sucesso!");
                
                return $result;
            } else {
                Registro("DAO_DataBase", "createTable","Erro na query!");
                return false;
            }
        } else {
            Registro("DAO_DataBase", "createTable","Tabela $nome_tabela existe, não é necessário execução da query:<br><br>$sql");   
            return true;
        }

    }

    public function tableExists(String $table) {
        $sql = "SHOW TABLES LIKE $table";
        $result = DAO_DataBase::connect()->query($sql);
        if ($result != null) {
            return true;
        } else {
            return false;
        }
    }

}


?>