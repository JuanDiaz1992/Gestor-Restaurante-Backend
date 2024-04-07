<?php
//Vista para solicitudes post del user
require_once "APPS/Menu_management/controller/post_controler.php";
require_once "utils/Responses.php";
$response = new PostController();
session_id($token);
session_start();

if($token && $_SESSION["type_user"] === 1){
    if (isset($data["menu_temp"])) {
        $response -> createMenuTemp($data["item"]);
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
        $response -> createMenu($data["date"]);
    }else if(isset($data["add_to_menu"])){
        $table = "all_menus";
        $response -> addToMenu($table,$data["id"],$data["idMEnu"],$data["dateTime"]);
    }else if(isset($data["supend_item_menu"])){
        $table = "all_menus";
        $response -> changeState($table,$data["idMenu"],$data["id"],$data["state"]);
    }
    else if(isset($_POST["edit_item_menu"])){
        $response ->editItemMenuController($_POST,$_FILES);
    }


    //Solicitudes delete
    else if(isset($data["delete_item_data"])){
        $response -> deleteItemTemporal($data["idItemMenu"]);
    }
    else if(isset($data["delete_item_bd_from_menu"])) {
        $table = "items_menu";
        $response -> deleteItemFromBd($table, $data["item"],$data["picture"]);
    }else if(isset($data["delete_item_menu_bd"]) && $data["delete_item_menu_bd"] == 1){
        $table = "all_menus";
        $response -> deleteItemFromBd($table, $data["id"]);
    }else if(isset($data["delete_all_menu"])&& $data["delete_all_menu"] == 1){
        $table = "menu";
        $response -> deleteItemFromBd($table,$data["idMenu"]);
    }else if(isset($data["delete_menu"])){
        $table = "menu";
        $response -> deleteItemFromBd($table,$data["idMenu"]);
    }
}else{
    Responses::responseNoDataWhitStatus(404);
}




?>