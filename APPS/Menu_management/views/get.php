<?php
require_once "APPS/Menu_management/controller/get_controler.php";
require_once "utils/Responses.php";
require_once "services/userServices/TokenService.php";
require_once "controller/MenuController.php";

$response = new GetController();

if ($table === "get_menu_index") {
    $table = "items_menu";
    $response ->getItemsMenu($table,$select,$_GET["linkTo"],$_GET["equalTo"], true);
}else if($table === "idMenu" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])){
    $table="menu";
    $response -> getData($table ,$select,$_GET["linkTo"],$_GET["equalTo"]);
}else {
    if(isset($token)){
        $tokenDecode = TokenService::decodeToken($token);
        session_id($tokenDecode->idSesion);
        session_start();
        if($tokenDecode->idSesion){
            if($table == 'items_menu'){
                $response -> getData($table,$select);
            }else if ($table == 'items_menu_soft_driks') {
                $table = "items_menu";
                $response -> getData($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
            }
            else if($table =='items_menu_temp'){
                MenuController::viewMenuTemp();
            }else if($table == 'menu_from_creator_menu'){
                MenuController::getMenuOfDay($_GET["equalTo"],true);
            }else if($table =='items_menu_consult'){
                $table = "items_menu";
                $response ->getItemsNoIncludeOnMenuController($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
            }
            else{
                Responses::responseNoDataWhitStatus(404);
            }
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }
}




?>