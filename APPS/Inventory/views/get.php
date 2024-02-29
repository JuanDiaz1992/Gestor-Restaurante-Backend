<?php
require_once "APPS/Inventory/controller/get_controler.php";
$response = new GetController();

if(isset($token)){
    session_id($token);
    session_start();
    if(isset($_SESSION["estatus"]) == true){
        if($table == 'inventoryBuysForDate'){
            $table = "buys";
            $response -> getInventoryForDate($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
        }
        else{
            badResponse();
        }
    }else{
        badResponse();
    }
}




?>