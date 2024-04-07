<?php
require_once "utils/Responses.php";
class RoutesController{
    public static function index(){
        $routesArray = explode("/",$_SERVER['REQUEST_URI']);
        $routesArray = array_filter($routesArray);
        if (count($routesArray) == 0) {
            //Cuando no se hacen peticiones a la Api
            Responses::responseNoDataWhitStatus(404);
        }
        else if(count($routesArray) >= 1 && isset($_SERVER['REQUEST_METHOD'])){
            //Cuando se hacen peticiones a la Api
            //GET
            if ($_SERVER['REQUEST_METHOD'] == "GET"){
                include "Routes/services/get.php";
            }
            //POST
            else if ($_SERVER['REQUEST_METHOD'] == "POST") {
                include "Routes/services/post.php";
            }
        }
        return;
    }
}
?>