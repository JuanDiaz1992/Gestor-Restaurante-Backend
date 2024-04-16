<?php
require_once "services/menuServices/MenuOfDayService.php";
require_once "services/menuServices/ItemsMenuService.php";
require_once "utils/Responses.php";

class MenuController {
    //Obtiene todos los elementos para crear el menú
    public static function getAllItemsMenu(){
        $response = ItemsMenuService::getAllItemsMenuService();
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Crea un nuevo elemento de tipo itemMenu
    public static function createNewItem($data){
        $response = ItemsMenuService::createItemMenuService($data);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }

    //Edita las propiedades de un item
    public static function updateItemMenu($data){
        $response = ItemsMenuService::updateItemMenuService($data);
        if ($response == true) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Elimina un elemento de tipo itemMenu
    public static function deleteItemMenu($id){
        $response = ItemsMenuService::deleteItemMenuService($id);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Añade un elemento al menú temporal para el creador de menú
    public static function addToMenuTemp($item){
        $response = MenuOfDayService::addToMenuTempService($item);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }

    //Muestra el menú temp
    public static function viewMenuTemp(){
        $response = MenuOfDayService::viewMenuTempService();
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Elimina un elemento del menú temp
    public static function deleteItemTemporal($id){
        $response = MenuOfDayService::deleteItemTemporalService($id);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }

    }

    //Toma todos los elementos del menú temp y los añade al menú del día
    public static function createMenu($date){
        $response = MenuOfDayService::createMenuService($date);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Muestra el menú del día
    public static function getMenuOfDay($date,$isConsultFromHome){
        $response = MenuOfDayService::getMenuOfDayService($date,$isConsultFromHome);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Añade un nuevo item al menú del día después de creado
    public static function addToMenu($data){
        $response = MenuOfDayService::addToMenuService($data);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Actualiza el estado de un item en el menú del dia
    public static function updateItemFromMenuOfDay($id,$state){
        $response = MenuOfDayService::updateItemFromMenuOfDayService($id,$state);
        if ($response == true) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Borra un elemento del menú del día
    public static function deleteItemFromMenuOfDay($id){
        $response = MenuOfDayService::deleteItemFromMenuOfDayService($id);
        if ($response == true) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    //Borra todo el menú del día
    public static function deleteMenu($date){
        $response = MenuOfDayService::deleteItemService($date);
        if ($response == true) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function getItemsNoIncludeOnMenuController($date){
        $response = MenuOfDayService::ItemsNoIncludeOnMenuService($date);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }

    public static function getEspecificItems($param){
        $response = ItemsMenuService::getEspecificItemsService($param);
        if ($response != null) {
            Responses::response($response);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }



}



?>