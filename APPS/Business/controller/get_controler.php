<?php



require_once "APPS/Model/ModelGet.php";
require_once "APPS/Responses.php";

class GetController{


    static public function getData($table,$select){
        $response = new ModelGet();
        $resultados=$response->getDataSimpleConsult($table,$select);
        Responses::response($resultados);
    }
}


?>