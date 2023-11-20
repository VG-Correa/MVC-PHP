<?php

use App\Controller\Pages\CadastroPaciente;
use App\Http\Response;
use App\Controller\Pages\Home;
use App\Controller\Pages\Login;

$objRouter->get("/",[
    function() {
       return new Response(200,HOME::getHome());
    }
]); 

$objRouter->get("/login",[
    function() {
       return new Response(200,Login::getLogin());
    }
]);


// Rota dinâmica
$objRouter->get("/pagina/{idPaginaa}/{acao}",[
    function($idPagina, $acao) {
       return new Response(200,'Página '.$idPagina. ' - '.$acao);
    }
]); 