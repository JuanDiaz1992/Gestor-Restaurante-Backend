<?php

require_once "APPS/Model/DAO.php";
require_once "Funciones/Responses.php";

class GetController{
    static public function getInventoryForDate($table,$select,$linkTo,$equalTo){
        $response = new DAO();
        $result = $response->get($table,$select,$linkTo,$equalTo);
        Responses::response($result);
    }
}


?>