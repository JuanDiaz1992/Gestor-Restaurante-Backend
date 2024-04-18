<?php
require_once "controller/BusinessController.php";
require_once "utils/Responses.php";

class BusinessPostRoutes{
    private $authMiddleware;
    public function __construct($authMiddleware) {
        $this->authMiddleware = $authMiddleware;
    }
    public function handleRequest($token = null, $data) {
        if (isset($data["file"])) {
            $data['photo'] = isset($data["file"]['photo']) ? $data["file"]['photo'] : '';
        }
        try {
            $this->authMiddleware->handle($token,1);
            if (isset($data["business_create_info"])) {
                BusinessController::createinfoBusiness($data);
            }else{
                Responses::responseNoDataWhitStatus(404);
            }
        }catch (Exception $e) {
            Responses::responseNoDataWhitStatus(401);
        }
    }
}

?>