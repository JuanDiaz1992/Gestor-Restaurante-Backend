<?php
require_once "APPS/User/controller/get_controler.php";
require_once "Funciones/TokenGenerate.php";
require_once "Funciones/Responses.php";
$response = new GetController();

if(isset($token)){
    $tokenDecode = Token::decodeToken($token);
    session_id($tokenDecode->id);
    session_start();
    if(isset($_SESSION["estatus"]) == true){
        //Se valida si la solicitud get es a la tabla user, si es así se bloquea el acceso
        if(!isset($tokenDecode)){
            Responses::responseNoDataWhitStatus(401);
        }elseif($table === "profile_user"){
            $response->getAllUsers($table,$select);
        }
        elseif($table == 'validateSession' ) {
            $response -> validateUSer($_SESSION["username"]);
        }
    }else{
        Responses::responseNoDataWhitStatus(404);
    }
}




?>