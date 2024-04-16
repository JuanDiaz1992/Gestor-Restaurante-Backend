<?php
require_once "utils/Responses.php";
require_once "controller/MenuController.php";

class MenuGetRoutes{
    private $authMiddleware;

    public function __construct($authMiddleware) {
        $this->authMiddleware = $authMiddleware;
    }

    public function handleRequest($table, $token = null, $params) {
        if ($table === "get_menu_index") {
            MenuController::getMenuOfDay($_GET["equalTo"], true);
        } else {
            try {
                // Autenticar primero
                $this->authMiddleware->handle($token,null);
                switch ($table) {
                    case 'items_menu_from_creator':
                        MenuController::getAllItemsMenu();
                        break;
                    case 'items_menu_soft_driks':
                        MenuController::getEspecificItems($params["equalTo"]);
                        break;
                    case 'items_menu_temp':
                        MenuController::viewMenuTemp();
                        break;
                    case 'menu_from_creator_menu':
                        MenuController::getMenuOfDay($params["equalTo"], false);
                        break;
                    case 'items_menu_is_not_in_menu':
                        MenuController::getItemsNoIncludeOnMenuController($params["equalTo"]);
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
}

?>