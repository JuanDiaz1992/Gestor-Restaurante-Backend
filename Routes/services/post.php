<?php
require_once "midelwares/AuthMiddleware.php";
require_once "utils/Responses.php";
$data = json_decode(file_get_contents('php://input'), true);
$token = null;
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
    if (strpos($authorizationHeader, 'Token') === 0) {
        // Obtener el valor del token eliminando 'Token ' del encabezado
        $token = substr($authorizationHeader, 6);
    }
}
if (isset($_SERVER['HTTP_MODULE'])) {
    $module = $_SERVER['HTTP_MODULE'];
}else{
    Responses::responseNoDataWhitStatus(404);
}


if ($_POST) {
    $data = $_POST;
}else if($_REQUEST) {
    $data = $_REQUEST;
}
if ($_FILES) {
    $data["file"] = $_FILES;
}


if($module == 'user'){
    require_once "APPS/User/post.php";
    $userPostRoutes = new UserPostRoutes(new AuthMiddleware());
    $userPostRoutes->handleRequest($token, $data);
}else if($module == 'inventory'){
    require_once "APPS/Inventory/views/post.php";
}else if($module == 'menu_management'){
    require_once "APPS/Menu_management/post.php";
    $menuPostRoutes = new MenuPostRoutes(new AuthMiddleware());
    $menuPostRoutes->handleRequest($token, $data);
}

?>