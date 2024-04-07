<?php
//Vista para solicitudes post del user

require_once "controller/UserController.php";
require_once "utils/Responses.php";


$userController = new UserController();

if (isset($data["login_request"])) {
    $userController->login($data["username"],$data["password"]);

}elseif (isset($_POST["newUser_request"])) {
    $data = $_POST;
    $data['photo'] = isset($_FILES['photo']) ? $_FILES['photo'] : '';
    $userController->createUser($data);

}elseif (isset($_REQUEST["edit_user_request"])) {
    $data = $_POST;
    $data['photo'] = isset($_FILES['photo']) ? $_FILES['photo'] : '';
    $userController->updateUser($data);

}elseif (isset($data['changePasswordUser'])) {
    $userController->updatePassword($data);

}elseif (isset($data["logout_request"])) {
    $userController->logout($token);

}elseif (isset($data["delete_user"])) {
    $userController->deleteUser($data);

}else{
    Responses::responseNoDataWhitStatus(404);
}



?>