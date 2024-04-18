<?php
require_once "models/Business.php";

class BusinessServices{
    static public function getBusinessServices($idBusiness){
        try {
            $bussines = Business::filter(["id"=>$idBusiness]);
            $data = array_map(function($bussine){
                return $bussine->toArray();
            },$bussines);
            return $data;
        } catch (\Throwable $th) {
            return null;
        }
    }

    static public function createinfoBusinessService($data){
        try {
            $nameBusiness = $data["nameBusiness"];
            $documentBusiness = $data["documentBusiness"];
            $logo = $data["photo"];
            $description = $data["description"];
            $officeHours = $data["officeHours"];
            $address = $data["address"];
            $numberPhone = $data["numberPhone"];
            $bussines = new Business($nameBusiness,$documentBusiness,$logo,$description,$officeHours,$address,$numberPhone);
            $bussines->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}



?>