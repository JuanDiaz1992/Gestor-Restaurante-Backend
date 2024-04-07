<?php
require_once "APPS/User/controller/get_controler.php";
require_once "services/userServices/TokenService.php";
require_once "utils/Responses.php";
$response = new GetController();

if(isset($token)){
    $tokenDecode = TokenService::decodeToken($token);
    if($tokenDecode->data->typeUser == 1){
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