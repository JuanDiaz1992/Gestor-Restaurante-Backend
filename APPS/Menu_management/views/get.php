<?php
require_once "APPS/Menu_management/controller/get_controler.php";
$response = new GetController();

if ($table === "get_menu_index") {
    $table = "items_menu";
    $response ->getDataWithJoinFromIndex($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
}else if($table === "menu" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])){
    $response -> getDataFilter($table ,$select,$_GET["linkTo"],$_GET["equalTo"]);
}else {
    if(isset($token)){
        session_id($token);
        session_start();
        if(isset($_SESSION["estatus"]) == true){
            if($table == 'items_menu'){
                $response -> getData($table,$select);
            }else if ($table == 'items_menu_soft_driks') {
                $table = "items_menu";
                $response -> getDataFilterSimple($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
            }
            else if($table =='items_menu_temp'){
                $response ->getDataBySession();
            }else if($table == 'items_menuJoin'){
                $table = "items_menu";
                $response ->getDataWithJoinFromAdmin("$table",$select,$_GET["linkTo"],$_GET["equalTo"]);
            }
            else{
                //Aqui validamos si la consulta es de tipo where, sino es una consulta a toda la tabla
                if (isset($_GET["linkTo"]) && isset($_GET["equalTo"])) {
                    $response -> getDataFilter($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
                }else{
                    $response->getData($table,$select);
                }
            }
        }else{
            badResponse();
        }
    }
}




?>