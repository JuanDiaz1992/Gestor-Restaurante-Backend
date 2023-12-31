<?php
//Vista para solicitudes post del user

require_once "APPS/User/controller/post_controler.php";
$response = new PostController();
if(isset($data["login_request"])){
    $table = "profile_user";
    $response -> postDataconsultUser($table, $data["username"], $data["password"]);
}else if(isset($_POST["newUser_request"])){
    session_id($token);
    session_start();
    if($token && $_SESSION["type_user"] === 1){
        $img = isset($_FILES['photo'])? $_FILES['photo'] : '';
        $response ->postControllerCreateUser(
            $_POST['id_business'],
            $_POST['userName'],
            $_POST['password'],
            $_POST['confirmPassword'],
            $_POST['name'],
            $img,
            $_POST['type_user'],
            );
    }
}else if(isset($_REQUEST["edit_user_request"])){
    session_id($token);
    session_start();
    if($token && $_SESSION["type_user"] === 1){
        $img = isset($_FILES['photo'])? $_FILES['photo'] : '';
        $response ->postControllerModify(
            $_POST['id'],
            $_POST['name'],
            $img,
            $_POST['type_user'],
            $_POST['username']
            );
    }
}else if(isset($data['changePasswordUser'])){
    $response ->changePassword(
        $data['id'],
        $data['password'],
        $data['confirmPassword'],
    );
}
//Solicitudes delete
else if(isset($data["logout_request"])){
    $response -> logout($token);
}
else if(isset($data["delete_user"])){
    session_id($token);
    session_start();
    if($token && $_SESSION["type_user"] === 1){
        $response -> deleteUserController($data["id"],);
    }
}
else{
    badResponse();
}





?>