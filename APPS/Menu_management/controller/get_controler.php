<?php


require_once "APPS/Model/ModelGet.php";
require_once "APPS/Responses.php";

class GetController{
    static public function getData($table,$select,$linkTo = null ,$equalTo = null){
        $response = new ModelGet();
        if ($linkTo === null) {
            $result = $response->getDataSimpleConsult($table,$select);
        }else{
            $result = $response->getDataSimpleConsult($table,$select,$linkTo,$equalTo);
        }
        Responses::response($result);
    }
    static public function getDataBySession(){
        $response = $_SESSION["menu_temp"];
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
        $return = new GetController();
        Responses::response($orderedResponse);
    }
    static public function getItemsNoIncludeOnMenuController($table,$select,$linkTo,$equalTo){
        $response = new ModelGet(
            "SELECT $select
            FROM $table
            WHERE NOT EXISTS (
                SELECT 1
                FROM all_menus
                WHERE all_menus.contenido = $table.id
                AND all_menus.$linkTo = :$linkTo)", $linkTo, $equalTo);
        $result = $response->executeWhitAttributes();
        Responses::response($result);
    }
    static public function getItemsMenu($table, $select, $linkTo, $equalTo, $isConsultFromHome){
        $response = new ModelGet(
            "SELECT $select
            FROM $table JOIN all_menus
            ON items_menu.id = all_menus.contenido
            WHERE all_menus.$linkTo = :$linkTo", ":date", $equalTo);
        $resultado = $response->executeWhitAttributes();
        $resultado2 = array();
        if ($isConsultFromHome) {
            //Esta validación se hace ya que
            //cuando se hace la consulta en el home,
            //si se pueden visualizar las gaseosas
            $secondResponse = new ModelGet();
            $resultado2 = $secondResponse->getDataSimpleConsult("items_menu","*","menu_item_type","soft_drinks");
        }
        $arrayResultado = array_merge($resultado, $resultado2);
        Responses::response($arrayResultado);
    }

}


?>