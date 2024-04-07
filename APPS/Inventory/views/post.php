<?php
//Vista para solicitudes post del user

require_once "APPS/Inventory/controller/post_controler.php";
require_once "utils/Responses.php";
$response = new PostController();

session_id($token);
session_start();
if($token && $_SESSION["type_user"] === 1){

    if(isset($data["record_buys"])){
        $table = "buys";
        $response -> postRecordInventoryController(
            $table,
            $data["purchaseValue"],
            $data["reason"],
            $data["observations"],
            $data["idProfile_user"],
            $data["dateTime"]
        );
    }else if(isset($data["delete_buy_inventory"])){
        $table = "buys";
        $response -> deleteItemInvetoryController($table,$data["idItem"]);
    }
}
else{
    Responses::responseNoDataWhitStatus(404);
}





?>