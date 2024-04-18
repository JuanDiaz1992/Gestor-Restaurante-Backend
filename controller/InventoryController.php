<?php

require_once "services/inventoryServices/InventoryServices.php";
require_once "utils/Responses.php";

class InventoryController{
    public static function recordInventory($data){
        $response = InventoryServices::recordInventoryService($data);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function deleteItemInvetory($id){
        $response = InventoryServices::deleteItemInvetoryService($id);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function getInventory($date){
        $response = InventoryServices::getInventoryService($date);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }
}

?>