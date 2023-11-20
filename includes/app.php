<?php

require __DIR__ ."/../vendor/autoload.php";
require_once __DIR__ ."/../app/http/Router.php";
include_once __DIR__ ."/../app/Banco_dados/DAO_DataBase.php";


use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;


DAO_DataBase::setDB(
    $dbUser = 'root',
    $dbPass = 'root',
    $dbHost = 'localhost',
    $dbName = 'poo',
);

$bd = new DAO_Database();

Environment::load(__DIR__.'/../');

define ('URL', getenv('URL'));

View::init([
    'URL' => URL
]);