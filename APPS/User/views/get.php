<?php
require_once "controller/UserController.php";
require_once "utils/Responses.php";
$response = new UserController();

if(isset($token)){
    if($table === "profile_user"){
            $response->getAllUsers();
        }
    }else{
        Responses::responseNoDataWhitStatus(404);
    }
?>