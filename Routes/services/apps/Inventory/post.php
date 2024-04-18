<?php
//Vista para solicitudes post del user

require_once "controller/InventoryController.php";
require_once "utils/Responses.php";

class InventoryPostRoutes{
    private $authMiddleware;
    public function __construct($authMiddleware) {
        $this->authMiddleware = $authMiddleware;
    }
    public function handleRequest($token = null, $data) {
        try {
            $this->authMiddleware->handle($token,1);
            if (isset($data["record_buys"])) {
                InventoryController::recordInventory($data);
            }else if (isset($data["delete_buy_inventory"])) {
                InventoryController::deleteItemInvetory($data["idItem"]);
            }else{
                Responses::responseNoDataWhitStatus(404);
            }
        }catch (Exception $e) {
            Responses::responseNoDataWhitStatus(401);
        }
    }
}



?>