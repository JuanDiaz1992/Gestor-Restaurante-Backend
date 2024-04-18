<?php
require_once "middlewares/AuthMiddleware.php";
require_once "utils/Responses.php";

$data = json_decode(file_get_contents('php://input'), true);
if ($_POST) {
    $data = $_POST;
}else if($_REQUEST) {
    $data = $_REQUEST;
}
if ($_FILES) {
    $data["file"] = $_FILES;
}


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

switch ($module) {
    case 'user':
        require_once "routes/services/apps/User/post.php";
        $route = new UserPostRoutes(new AuthMiddleware());
        $route->handleRequest($token, $data);
        break;
    case 'inventory':
        require_once "routes/services/apps/Inventory/post.php";
        $route = new InventoryPostRoutes(new AuthMiddleware());
        $route->handleRequest($token, $data);
        break;
    case 'menu_management':
        require_once "routes/services/apps/Menu_management/post.php";
        $route = new MenuPostRoutes(new AuthMiddleware());
        $route->handleRequest($token, $data);
        break;
    case 'business':
        require_once "routes/services/apps/Business/post.php";
        $route = new BusinessPostRoutes(new AuthMiddleware());
        $route->handleRequest($token, $data);
        break;
    default:
        Responses::responseNoDataWhitStatus(404);
        break;
}

?>