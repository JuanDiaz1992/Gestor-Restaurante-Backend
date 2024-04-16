<?php
require_once "midelwares/AuthMiddleware.php";
require_once "utils/Responses.php";
$table = explode("?",$routesArray[2])[0];
$select = $_GET["select"]??"*";
$token = null;
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
    if (strpos($authorizationHeader, 'Token') === 0) {
        // Obtener el valor del token eliminando 'Token ' del encabezado
        $token = substr($authorizationHeader, 6);
    }
    $username = isset($_SERVER['HTTP_X_USERNAME']) ? $_SERVER['HTTP_X_USERNAME'] : "";
}

if (isset($_SERVER['HTTP_MODULE'])) {
    $module = $_SERVER['HTTP_MODULE'];
}else{
    Responses::responseNoDataWhitStatus(404);
}
if($module == 'user'){
    require_once "APPS/User/get.php";
    $userGetRoutes = new UserGetRoutes(new AuthMiddleware());
    $userGetRoutes->handleRequest($table, $token, $_GET);
}else if($module == 'business'){
    require_once "APPS/Business/views/get.php";
}else if($module == 'inventory'){
    require_once "APPS/Inventory/views/get.php";
}else if($module == 'menu_management'){
    require_once "APPS/Menu_management/get.php";
    $menuGetRoutes = new MenuGetRoutes(new AuthMiddleware());
    $menuGetRoutes->handleRequest($table, $token, $_GET);
}

?>