<?php

namespace app\Controller\Pages;

require_once __DIR__ ."./../../Utils/View.php";
require_once __DIR__ ."./Page.php";
require_once __DIR__ ."./../../Banco_dados/PacienteTable.php";
use \app\Utils\View;
use PacienteTable;

class Login extends Page{

    /**
     * Método responsável por retornar conteúdo {view} da nossa home;
     * @return string
     */
    public static function getLogin($mensagem="") {
    
        $content = View::render("pages/login", [ 
            "mensagem"=> $mensagem,
        ]);
        
        return parent::getPage("SPEC", 'home', $content);

    }

}