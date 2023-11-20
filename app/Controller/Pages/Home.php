<?php

namespace app\Controller\Pages;

require_once __DIR__ ."./../../Utils/View.php";
require_once __DIR__ ."./Page.php";
use \app\Utils\View;

class Home extends Page{

    /**
     * Método responsável por retornar conteúdo {view} da nossa home;
     * @return string
     */
    public static function getHome() {
        
        $content = View::render("pages/home", [
            "name"=> 'SPEC',
            "site" => 'spec.com.br',
            "descricao" => "TESTEEEEE"
        ]);
        
        return parent::getPage("SPEC", 'home', $content);

    }


}