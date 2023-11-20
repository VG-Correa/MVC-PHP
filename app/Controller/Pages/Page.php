<?php

namespace app\Controller\Pages;

require_once __DIR__ ."./../../Utils/View.php";
use \app\Utils\View;


class Page {


    private static function getHeader($header) {
        return View::render("pages/headers/$header");
    }

    private static function getFooter($footer) {
        return View::render("pages/footers/$footer");
    }   

    /**
     * Método responsável por retornar conteúdo {view} da nossa home;
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title, $pagina, $content) {
        
        return View::render("pages/page", [
            "title"=> $title,
            "header" => self::getHeader($pagina),
            "content"=> $content,
            "footer"=> self::getFooter($pagina)
        ]);
    
    }


}