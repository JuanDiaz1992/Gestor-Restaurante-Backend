<?php
//Vista para solicitudes post del user

require_once "APPS/Inventory/controller/post_controler.php";
$response = new PostController();

if(isset($data["record_buys"])){
    session_id($token);
    session_start();
    if($token && $_SESSION["type_user"] === 1){
        $table = "buys";
        $response -> postRecordInventoryController(
            $table,
            $data["purchaseValue"],
            $data["reason"],
            $data["observations"],
            $data["idProfile_user"],
            $data["dateTime"]
        );
    }else{
        badResponse();
    }

}else{
    badResponse();
}





?>