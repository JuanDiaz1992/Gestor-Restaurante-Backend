<?php
require_once "services/crudDbMysql/DAO.php";
require_once "utils/Responses.php";

class GetController{
    static public function getData($table,$select){
        $response = new DAO();
        $resultados=$response->get($table,$select);
        Responses::response($resultados);
    }
}


?>