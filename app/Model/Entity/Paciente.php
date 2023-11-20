<?php

namespace App\Models;

class Paciente
{

    private $id;
    private $nome;
    private $cpf;
    private $rg;
    private $data_nascimento;
    private $email;
    private $consentimento;
    private $sexo;
    private $status;

    public function __construct(
        $postVars
    ) {
        $this->id = isset($postVars["id"]) ? $postVars["id"] : '';
        $this->nome = $postVars["nome"];
        $this->cpf = $postVars["cpf"];
        $this->rg = $postVars["rg"];
        $this->data_nascimento = $postVars["data_nascimento"];
        $this->email = $postVars["email"];
        $this->consentimento = $postVars["consentimento"];
        $this->status = $postVars["status"];
        $this->sexo = $postVars["sexo"];
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function getCPF() {
        return $this->cpf;
    }
    public function setCPF($cpf) {
        $this->cpf = $cpf;
    }
    public function getRG() {
        return $this->rg;
    }
    public function setRG($rg) {
        $this->rg = $rg;
    }
    public function getData_nascimento() {
        return $this->data_nascimento;
    }
    public function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }
    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function getConsentimento(){
        return $this->consentimento;
    }
    public function setConsentimento($consentimento) {
        $this->consentimento = $consentimento;
    }
    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }
    public function getSexo() {
        return $this->sexo;
    }

    

}
