<?php
require_once "controller/InventoryController.php";
require_once "utils/Responses.php";

class InventoryGetRoutes{
    private $authMiddleware;
    public function __construct($authMiddleware) {
        $this->authMiddleware = $authMiddleware;
    }
    public function handleRequest($table, $token, $params) {
        try {
            // Autenticar primero
            $this->authMiddleware->handle($token,1);
            switch ($table) {
                case 'inventoryBuysForDate':
                    InventoryController::getInventory($params["equalTo"]);
                    break;
                default:
                    Responses::responseNoDataWhitStatus(404);
                    break;
            }
        } catch (Exception $e) {
            Responses::responseNoDataWhitStatus(401);
        }
    }
}



?>