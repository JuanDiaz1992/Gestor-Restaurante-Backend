<?php
require_once "APPS/User/controller/delete_controler.php";
$response = new DeleteController();

if(isset($data["logout_request"])){
    $response -> logout($data["token"]);
}
elseif(isset($data["delete_user"])){
    session_id($token);
    session_start();
    if($token && $_SESSION["type_user"] === 1){
        $response -> deleteUserController($data["id"],);
    }else{
        badResponse();
    }
}


?>