<?php
require_once "models/ItemMenu.php";
require_once "models/MenuOfDay.php";
class MenuOfDayService{

    static public function addToMenuTempService($item){
        if (!isset($_SESSION["menu_temp"])){
            $_SESSION["menu_temp"] = array();
        }
        $itemExists = 0;
        foreach ($_SESSION["menu_temp"] as $existingItem) {
            if ($existingItem["id"] === $item["id"]) {
                $itemExists = 1;
                break;
            }
        }
        if ($itemExists === 0) {
            array_push($_SESSION["menu_temp"], $item);
            $response = $_SESSION["menu_temp"];
            return $response;
        } else {
            return null;
        }
    }

    static public function viewMenuTempService(){
        try {
            if (isset($_SESSION["menu_temp"])) {
                $response = $_SESSION["menu_temp"];
            }else{
                return false;
            }
            // Arreglos para cada tipo de menú
            $especialities = [];
            $soups = [];
            $beginning = [];
            $meats = [];
            $drinks = [];
            // Clasifica los elementos en los arreglos correspondientes
            foreach ($response as $element) {
                switch ($element['menu_item_type']) {
                    case 'especialities':
                        $especialities[] = $element;
                        break;
                    case 'soups':
                        $soups[] = $element;
                        break;
                    case 'beginning':
                        $beginning[] = $element;
                        break;
                    case 'meats':
                        $meats[] = $element;
                        break;
                    case 'drinks':
                        $drinks[] = $element;
                        break;
                }
            }
            // Combina los arreglos en el orden deseado
            $orderedResponse = array_merge($especialities, $soups, $beginning, $meats, $drinks);
            $_SESSION["menu_temp"] = $orderedResponse;
            // Registra el array ordenado en el archivo de registro de errores
            return $orderedResponse;
        } catch (\Throwable $th) {
            return false;
        }
    }


    static public function createMenuService($date){
        try {
            error_log($date);
            $menuTemp = $_SESSION["menu_temp"];
            $state = 1;
            if (count($menuTemp)>0) {
                foreach ($menuTemp as $element){
                    error_log($element["id"]);
                    $menuOfDay = new MenuOfDay($element["id"],$state,$date);
                    $menuOfDay->save();
                }
                $_SESSION["menu_temp"] = [];
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    static public function getMenuOfDayService($searchValue,$isConsultFromHome){
        try {
            $itemsMenuOfDay = ItemMenu::filter("items_in_menu_of_day", "contenido", "date", $searchValue);
            if (count($itemsMenuOfDay)>0) {
                $arrayResultado = $itemsMenuOfDay;
                //Esta validación se hace ya que cuando se hace la consulta en el home, ahí si se pueden visualizar las gaseosas
                if($isConsultFromHome){
                    try {
                        $itemsSofDrintkMEnu = ItemMenu::filter("menu_item_type","soft_drinks");
                        $data2 = array_map(function($itemSofDrintkMEnu) {
                            return $itemSofDrintkMEnu->toArray();
                        }, $itemsSofDrintkMEnu);
                        $arrayResultado = array_merge($data1, $data2);
                    } catch (\Throwable $th) {
                        $data2 = null;
                    }
                }
            }
            error_log(print_r($arrayResultado,true));
            return $arrayResultado;
        } catch (\Throwable $th) {
            return null;
        }

    }
}




?>