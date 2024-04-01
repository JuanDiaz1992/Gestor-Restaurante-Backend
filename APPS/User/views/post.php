<?php
//Vista para solicitudes post del user

require_once "APPS/User/controller/post_controler.php";
require_once "Funciones/Responses.php";
require_once ("Funciones/TokenGenerate.php");

$response = new PostController();
$table = "profile_user";

$requestType = null;
if(isset($data["login_request"])) {
    $requestType = "login_request";
} elseif(isset($_POST["newUser_request"])) {
    $requestType = "newUser_request";
} elseif(isset($_REQUEST["edit_user_request"])) {
    $requestType = "edit_user_request";
} elseif(isset($data['changePasswordUser'])) {
    $requestType = 'changePasswordUser';
} elseif(isset($data["logout_request"])) {
    $requestType = "logout_request";
} elseif(isset($data["delete_user"])) {
    $requestType = "delete_user";
}

switch ($requestType) {
    case "login_request":
        $response->postDataconsultUser($table, $data);
        break;
    case "newUser_request":
        if($token) {
            $photo = isset($_FILES['photo']) ? $_FILES['photo'] : '';
            $response->postControllerCreateUser($table, $_POST, $photo);
        }
        break;
    case "edit_user_request":
        if($token) {
            $tokenDecode = Token::decodeToken($token);
            $photo = isset($_FILES['photo']) ? $_FILES['photo'] : '';
            $response->postControllerModify($table, $_POST, $photo);
        }
        break;
    case "changePasswordUser":
        $response->changePassword($table, $data);
        break;
    case "logout_request":
        $response->logout($token);
        break;
    case "delete_user":
        if($token) {
            $response->deleteUserController($table, $data);
        }
        break;
    default:
        Responses::responseNoDataWhitStatus(404);
}



?>