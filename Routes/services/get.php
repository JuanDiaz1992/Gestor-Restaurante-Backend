<?php
require_once "middlewares/AuthMiddleware.php";
require_once "utils/Responses.php";
$table = explode("?",$routesArray[2])[0];
$select = $_GET["select"]??"*";
$token = null;
$module = null;

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

switch ($module) {
    case 'user':
        require_once "routes/services/apps/User/get.php";
        $route = new UserGetRoutes(new AuthMiddleware());
        $route->handleRequest($table, $token, $_GET);
        break;
    case 'business':
        require_once "routes/services/apps/Business/get.php";
        $route = new BusinessGetRoutes(new AuthMiddleware());
        $route->handleRequest($table, $token, $_GET);
        break;
    case 'inventory':
        require_once "routes/services/apps/Inventory/get.php";
        $route = new InventoryGetRoutes(new AuthMiddleware());
        $route->handleRequest($table, $token, $_GET);
        break;
    case 'menu_management':
        require_once "routes/services/apps/Menu_management/get.php";
        $route = new MenuGetRoutes(new AuthMiddleware());
        $route->handleRequest($table, $token, $_GET);
        break;
    default:
        Responses::responseNoDataWhitStatus(404);
        break;
}


?>