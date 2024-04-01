<?php
require_once "APPS/Model/DAO.php";
require_once "Funciones/Responses.php";

class GetController{
    static public function getData($table,$select){
        $response = new DAO();
        $resultados=$response->get($table,$select);
        Responses::response($resultados);
    }
}


?>