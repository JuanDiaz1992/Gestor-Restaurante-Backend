<?php



require_once "APPS/Model/ModelSentences.php";
require_once "APPS/Responses.php";

class GetController{


    static public function getData($table,$select){
        $response = new ModelSentences();
        $resultados=$response->getDataSimpleConsult($table,$select);
        Responses::response($resultados);
    }
}


?>