<?php

require_once "services/crudDbMysql/DAO.php";
require_once "utils/Responses.php";

class GetController{
    static public function getInventoryForDate($table,$select,$linkTo,$equalTo){
        $response = new DAO();
        $result = $response->get($table,$select,$linkTo,$equalTo);
        Responses::response($result);
    }
}


?>