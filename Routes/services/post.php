<?php
$data = json_decode(file_get_contents('php://input'), true);
function badResponse(){
    $json = array(
        'status' => 404,
        'results' => 'Not Found'
    );
    echo json_encode($json,http_response_code($json['status']));
}
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
    badResponse();
}
if($module == 'user'){
    require_once "APPS/User/views/post.php";
}else if($module == 'inventory'){
    require_once "APPS/Inventory/views/post.php";
}else if($module == 'menu_management'){
    require_once "APPS/Menu_management/views/post.php";
}













?>