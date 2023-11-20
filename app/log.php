<?php

function Registro($nome_objeto, $nome_funcao, $texto, $demais_infos="") {

    $usar_log = false;

    if ($usar_log){

        print("<hr>");
        print("Objeto: $nome_objeto<br>");
        print("Função: $nome_funcao<br>");
        print("Log: $texto");

        if ($demais_infos!=""){
            print("<br>Demais informações:<br>");
            var_dump($demais_infos);
        }

        print("<hr>");
    
    }

}

// olá
?>