<?php

require_once "utils/Responses.php";
require_once "controller/MenuController.php";

class MenuPostRoutes{
    private $authMiddleware;
    public function __construct($authMiddleware) {
        $this->authMiddleware = $authMiddleware;
    }
    public function handleRequest($token,$data) {
        if (isset($data["file"])) {
            $data['photo'] = isset($data["file"]['photo']) ? $data["file"]['photo'] : '';
        }
        try {
                // Autenticar primero
            $this->authMiddleware->handle($token,1);
            if (isset($data["menu_temp"])) {
                MenuController::addToMenuTemp($data["item"]);
            }else if(isset($data["new_item_menu"])){
                MenuController::createNewItem($data);
            }else if(isset($data["create_menu"])){
                MenuController::createMenu($data["date"]);
            }else if(isset($data["add_to_menu"])){
                MenuController::addToMenu($data);
            }else if(isset($data["supend_item_menu"])){
                MenuController::updateItemFromMenuOfDay($data["id"],$data["state"]);
            }else if(isset($data["edit_item_menu"])){
                MenuController::updateItemMenu($data);
            }

            //Solicitudes delete
            else if(isset($data["delete_item_data"])){
                MenuController::deleteItemTemporal($data["idItemMenu"]);
            }else if(isset($data["delete_item_bd_from_menu"])) {
                MenuController::deleteItemMenu($data["id"]);
            }else if(isset($data["delete_item_menu_bd"]) && $data["delete_item_menu_bd"] == 1){
                MenuController::deleteItemFromMenuOfDay($data["id"]);
            }else if(isset($data["delete_menu"])){
                MenuController::deleteMenu($data["date"]);
            }
        }catch (Exception $e) {
            Responses::responseNoDataWhitStatus(401);
        }
    }
}


?>