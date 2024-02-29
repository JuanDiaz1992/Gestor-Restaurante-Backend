<?php
require_once "APPS/Menu_management/controller/get_controler.php";
$response = new GetController();

if ($table === "get_menu_index") {
    $table = "items_menu";
    $response ->getItemsMenu($table,$select,$_GET["linkTo"],$_GET["equalTo"], true);
}else if($table === "idMenu" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])){
    $table="menu";
    $response -> getData($table ,$select,$_GET["linkTo"],$_GET["equalTo"]);
}else {
    if(isset($token)){
        session_id($token);
        session_start();
        if(isset($_SESSION["estatus"]) == true){
            if($table == 'items_menu'){
                $response -> getData($table,$select);
            }else if ($table == 'items_menu_soft_driks') {
                $table = "items_menu";
                $response -> getData($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
            }
            else if($table =='items_menu_temp'){
                $response ->getDataBySession();
            }else if($table == 'menu_from_creator_menu'){
                $table = "items_menu";
                $response ->getItemsMenu($table,$select,$_GET["linkTo"],$_GET["equalTo"], false);
            }else if($table =='items_menu_consult'){
                $table = "items_menu";
                $response ->getItemsNoIncludeOnMenuController($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
            }
            else{
                badResponse();
            }
        }else{
            badResponse();
        }
    }
}




?>