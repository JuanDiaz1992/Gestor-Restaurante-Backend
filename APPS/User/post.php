<?php
//Vista para solicitudes post del user

require_once "controller/UserController.php";
require_once "utils/Responses.php";

class UserPostRoutes{
    private $authMiddleware;
    public function __construct($authMiddleware) {
        $this->authMiddleware = $authMiddleware;
    }
    public function handleRequest($token = null, $data) {
        if (isset($data["file"])) {
            $data['photo'] = isset($data["file"]['photo']) ? $data["file"]['photo'] : '';
        }
        try {
            if (isset($data["login_request"])) {
                UserController::login($data["username"],$data["password"]);
                exit;
            }
            // Autenticar primero
            $this->authMiddleware->handle($token,1);
            if (isset($data["newUser_request"])) {
                UserController::createUser($data);
            }elseif (isset($data["edit_user_request"])) {
                UserController::updateUser($data);
            }elseif (isset($data['changePasswordUser'])) {
                UserController::updatePassword($data);
            }elseif (isset($data["logout_request"])) {
                UserController::logout($token);
            }elseif (isset($data["delete_user"])) {
                UserController::deleteUser($data);
            }else{
                Responses::responseNoDataWhitStatus(404);
            }
        }catch (Exception $e) {
            Responses::responseNoDataWhitStatus(401);
        }
    }
}


?>