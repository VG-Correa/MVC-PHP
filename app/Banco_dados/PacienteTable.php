<?php

include_once("DAO_Table.php");
include_once("resources_db/ColumnsModel.php");
include_once("./app/Model/Entity/Paciente.php");

use App\Models\Paciente;



class PacienteTable extends DAO_Table {

    private Column $id;
    private Column $nome;
    private Column $cpf;
    private Column $rg;
    private Column $data_nascimento;
    private Column $email;
    private Column $senha;
    private Column $consentimento;
    private Column $status;
    
    private Column $id_sexo;
    public static $supernome = "Pacientes";

    public function __construct() {
        parent::__construct(PacienteTable::$supernome);

        $this->colunas[] = $this->id = new Column("id", "int",0, true, true,true, true);
        $this->colunas[] = $this->nome = new Column("nome","VARCHAR",100,true);
        $this->colunas[] = $this->cpf = new Column("cpf","VARCHAR",11,true,true);
        $this->colunas[] = $this->rg = new Column("rg","VARCHAR",9,true,true);
        $this->colunas[] = $this->data_nascimento = new Column("data_nascimento","DATE",0,true);
        $this->colunas[] = $this->email = new Column("email","VARCHAR",100,true, true);
        $this->colunas[] = $this->senha = new Column("senha","VARCHAR",100,true);
        $this->colunas[] = $this->consentimento = new Column("consentimento","BOOLEAN",10,true);
        $this->colunas[] = $this->status = new Column("status","BOOLEAN",10,true);
        $this->colunas[] = $this->id_sexo = new Column("id_sexo","int",0,true);

        $this->SetRelacionamentos(new Relacionamento($this->id_sexo, "Sexo"));

        $this->initTable();
        $this->CreateRelacionamentos();

    }

    public function addPaciente(Paciente $paciente, $senha) {

        $result = $this->Insert([
            "nome" => $paciente->getNome(),
            "cpf"=> $paciente->getCPF(),
            "rg"=> $paciente->getRG(),
            "data_nascimento"=> $paciente->getData_nascimento(),
            "email"=> $paciente->getEmail(),
            "senha"=> $senha,
            "consentimento"=> $paciente->getConsentimento(),
            "status" => $paciente->getStatus(),
            "id_sexo"=> $paciente->getSexo()->getId(),
        ]);

        return $result;

    }

    public function EmailExist($email) {
        $result = $this->Query("SELECT email from Pacientes where email = '$email'");
        return $result;
    }
    
    public function cpfExist($cpf) {
        $result = $this->Query("SELECT cpf from Pacientes where cpf = '$cpf'");
        return $result;
    }
    
    public function RgExist($rg) {
        $result = $this->Query("SELECT rg from Pacientes where rg = '$rg'");
        return $result;
    }

    public function Login($email, $senha) {
        
        $result = $this->Query("SELECT * from ".PacienteTable::$supernome." where email = '$email' and senha = '$senha'");
        
        

        if ($result) {
            $result = $result[0];
            $usuario = new Paciente([
                "id" => $result[0],
                'nome'=> $result[1],
                'cpf'=> $result[2],
                'rg'=> $result[3],
                'data_nascimento'=> $result[4],
                'email'=> $result[5],
                'consentimento'=> $result[7],
                'status'=> $result[8],
                'id_sexo'=> $result[9]
            ]);
            #TODO: Continuar aqui;
            $result = $usuario;
        }

        return $result;

    }

}