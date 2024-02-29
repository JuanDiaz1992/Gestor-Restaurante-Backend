<?php


require_once "APPS/Model/ModelSentences.php";
require_once "APPS/Responses.php";

class GetController{
    static public function getInventoryForDate($table,$select,$linkTo,$equalTo){
        $response = new ModelSentences();
        $result = $response->getDataSimpleConsult($table,$select,$linkTo,$equalTo);
        Responses::response($result);
    }
}


?>