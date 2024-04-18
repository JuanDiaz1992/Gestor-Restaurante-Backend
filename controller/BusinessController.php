<?php
require_once "services/businessServices/BusinessServices.php";
require_once "utils/Responses.php";
class BusinessController{
    public static function getBusiness($idBusiness){
        $response = BusinessServices::getBusinessServices($idBusiness);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }
    public static function createinfoBusiness($data){
        $response = BusinessServices::createinfoBusinessService($data);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

}


?>