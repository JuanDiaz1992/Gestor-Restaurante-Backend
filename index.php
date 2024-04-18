<?php
require_once "Settings/cors.php";
require "vendor/autoload.php";
require_once "routes/Routes.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$os_info = php_uname('s');
//Manejo de errores
ini_set('display_errors',1);
ini_set('logs_errors',1);
if(strpos($os_info, 'Windows')!== false){
    ini_set('error_log','F:/xampp/htdocs/restaurante/services/php_error_log');
}else if(strpos($os_info, 'Linux')!== false){
    ini_set('error_log','/opt/lampp/htdocs/restaurante/services/php_error_log');
}

Routes::main();


?>