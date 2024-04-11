<?php


require_once "services/crudDbMysql/DAO.php";
require_once "utils/Responses.php";

class GetController{
    static public function getData($table,$select,$linkTo = null ,$equalTo = null){
        $response = new DAO();
        if ($linkTo === null) {
            $result = $response->get($table,$select);
        }else{
            $result = $response->get($table,$select,$linkTo,$equalTo);
        }
        Responses::response($result);
    }
    static public function getDataBySession(){
        try {
            if (isset($_SESSION["menu_temp"])) {
                $response = $_SESSION["menu_temp"];
            }else{
                Responses::responseNoDataWhitStatus(404);
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
            Responses::response($orderedResponse);
        } catch (\Throwable $th) {
            Responses::responseNoDataWhitStatus(404);
        }

    }
    static public function getItemsNoIncludeOnMenuController($table,$select,$linkTo,$equalTo){
        $response = new DAO(
            "SELECT $select
            FROM $table
            WHERE NOT EXISTS (
                SELECT 1
                FROM items_in_menu_of_day
                WHERE items_in_menu_of_day.contenido = $table.id
                AND items_in_menu_of_day.$linkTo = :$linkTo)", $linkTo, $equalTo);
        $result = $response->getWhitAttributes();
        Responses::response($result);
    }
    static public function getItemsMenu($table, $select, $linkTo, $equalTo, $isConsultFromHome){
        $response = new DAO(
            "SELECT $select
            FROM $table JOIN items_in_menu_of_day
            ON items_menu.id = items_in_menu_of_day.contenido
            WHERE items_in_menu_of_day.$linkTo = :$linkTo", ":date", $equalTo);
        $resultado = $response->getWhitAttributes();
        $resultado2 = array();
        if ($isConsultFromHome) {
            //Esta validación se hace ya que
            //cuando se hace la consulta en el home,
            //si se pueden visualizar las gaseosas
            $secondResponse = new DAO();
            $resultado2 = $secondResponse->get("items_menu","*","menu_item_type","soft_drinks");
        }
        $arrayResultado = array_merge($resultado, $resultado2);
        Responses::response($arrayResultado);
    }

}


?>