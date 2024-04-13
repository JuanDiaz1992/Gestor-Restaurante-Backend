<?php
//Vista para solicitudes post del user
require_once "APPS/Menu_management/controller/post_controler.php";
require_once "utils/Responses.php";
require_once "services/userServices/TokenService.php";
require_once "controller/MenuController.php";
$response = new PostController();
$tokenDecode = TokenService::decodeToken($token);
session_id($tokenDecode->idSesion);
session_start();

if($tokenDecode->idSesion){
    if (isset($data["menu_temp"])) {
        MenuController::createMenuTemp($data["item"]);
        //$response -> createMenuTemp($data["item"]);
    }else if(isset($_POST["new_item_menu"])){
        $table = "items_menu";
        $img = isset($_FILES['photo'])? $_FILES['photo'] : '';
        $amount = isset($_POST["amount"])? $_POST["amount"] : 0;
        $response -> createItemMenu(
            $table,
            $_POST["name"],
            $_POST["description"],
            $_POST["price"],
            $img,
            $_POST["menu_item_type"],
            $_POST["idProfile_user"],
            $amount,
        );
    }else if(isset($data["create_menu"])){
        MenuController::createMenu($data["date"]);
    }else if(isset($data["add_to_menu"])){
        $table = "all_menus";
        $response -> addToMenu($table,$data["id"],$data["idMEnu"],$data["dateTime"]);
    }else if(isset($data["supend_item_menu"])){
        MenuController::updateItemFromMenuOfDay($data["id"],$data["state"]);
    }
    else if(isset($_POST["edit_item_menu"])){
        $data = $_POST;
        $data['photo'] = isset($_FILES['photo']) ? $_FILES['photo'] : '';
        MenuController::updateItemMenu($data);
    }


    //Solicitudes delete
    else if(isset($data["delete_item_data"])){
        $response -> deleteItemTemporal($data["idItemMenu"]);
    }
    else if(isset($data["delete_item_bd_from_menu"])) {
        $table = "items_menu";
        $response -> deleteItemFromBd($table, $data["item"],$data["picture"]);
    }else if(isset($data["delete_item_menu_bd"]) && $data["delete_item_menu_bd"] == 1){
        MenuController::deleteItemFromMenuOfDay($data["id"]);
    }else if(isset($data["delete_menu"])){
        $table = "menu";
        $response -> deleteItemFromBd($table,$data["idMenu"]);
    }
}else{
    Responses::responseNoDataWhitStatus(404);
}




?>