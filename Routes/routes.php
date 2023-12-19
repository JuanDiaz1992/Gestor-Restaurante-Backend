<?php
$routesArray = explode("/",$_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

if (count($routesArray) == 0) {
    //Cuando no se hacen peticiones a la Api
    $json = array(
        'status' => 404,
        'result' => 'Not found'
    );
}
else if(count($routesArray) >= 1 && isset($_SERVER['REQUEST_METHOD'])){
    //Cuando se hacen peticiones a la Api

    //GET
    if ($_SERVER['REQUEST_METHOD'] == "GET"){
        include "services/get.php";
    }
    //POST
    else if ($_SERVER['REQUEST_METHOD'] == "POST") {
        include "services/post.php";
    }
    //DELETE
    else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
        include "services/delete.php";
    }
}
return;
?>