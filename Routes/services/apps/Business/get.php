<?php
require_once "controller/BusinessController.php";


class BusinessGetRoutes{
    private $authMiddleware;
    public function __construct($authMiddleware) {
        $this->authMiddleware = $authMiddleware;
    }

    public function handleRequest($table, $token = null, $params) {
        if ($table == "getInfoBusiness") {
            BusinessController::getBusiness($params["equalTo"]);
        }
    }
}
?>