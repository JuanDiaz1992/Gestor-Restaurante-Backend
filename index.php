<?php

require_once "gestionRestauranteSettings/cors.php";
require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$os_info = php_uname('s');
//Manejo de errores
ini_set('display_errors',1);
ini_set('logs_errors',1);
if(strpos($os_info, 'Windows')!== false){
    ini_set('error_log','F:/xampp/htdocs/restaurante/Error/php_error_log');

}else if(strpos($os_info, 'Linux')!== false){
    ini_set('error_log','/opt/lampp/htdocs/restaurante/Error/php_error_log');

}

//Manejo de rutas
require_once "gestionRestauranteSettings/routes_controller.php";
$index = new RoutesController();
$index-> index();






?>