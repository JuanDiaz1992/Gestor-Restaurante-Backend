<?php
require_once "services/menuServices/MenuOfDayService.php";
require_once "services/menuServices/ItemsMenuService.php";
require_once "utils/Responses.php";

class MenuController {
    public static function getAllItemsMenu(){
        $response = ItemsMenuService::getAllItemsMenuService();
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }
    public static function createMenuTemp($item){
        $response = MenuOfDayService::addToMenuTempService($item);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }

    public static function viewMenuTemp(){
        $response = MenuOfDayService::viewMenuTempService();
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function createMenu($date){
        $response = MenuOfDayService::createMenuService($date);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function getMenuOfDay($date,$isConsultFromHome){
        $response = MenuOfDayService::getMenuOfDayService($date,$isConsultFromHome);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function updateItemFromMenuOfDay($id,$state){
        $response = MenuOfDayService::updateItemFromMenuOfDayService($id,$state);
        if ($response == true) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function updateItemMenu($data){
        $response = ItemsMenuService::updateItemMenuService($data);
        if ($response == true) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function deleteItemFromMenuOfDay($id){
        $response = MenuOfDayService::deleteItemFromMenuOfDayService($id);
        if ($response == true) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }


}



?>