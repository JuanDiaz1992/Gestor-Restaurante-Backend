<?php
require_once "services/crudDbMysql/DAO.php";
//Para extender de esta base en la declaración de atributos
//El id siempre debe estar de último
//el resto de datos deben ir en el mismo orden que en las columnas de la bd
class BaseITem{
    protected $table = "";
    protected static $tableStatic = "";
    protected static $columnBd = [];
    protected $columnBdNoStatic = [];


    public static function get($identifier, $especificData = null) {
        $itemsData = DAO::get(static::$tableStatic, "*", $especificData, $identifier);
        if (count($itemsData) > 0) {
            foreach ($itemsData as $itemData) {
                $args = [];
                foreach (static::$columnBd as $column) {
                    $args[] = $itemData->$column;
                }
                $item = new static(...$args);
            }
            return $item;
        }
        return null;
    }

    public function save(){
        $attributes = get_object_vars($this);
        $dataArray = array_values(array_slice($attributes, 2));
        if ($this->id != null) {
            $itemsData = DAO::update($this->table,$this->columnBdNoStatic,$dataArray);
        }else{
            $columnBdWithoutLast = array_slice($this->columnBdNoStatic, 0, -1);
            $atributesWithoutLast = array_slice($dataArray, 0, -1);
            $userBd = DAO::create($this->table,$columnBdWithoutLast,$atributesWithoutLast,true
            );
            $this->id = $userBd['id'];
            return true;
        }
    }

    public function delete(){
        $response = DAO::delete($this->table,"id",$this->id);
        if($response == 200){
            return true;
        }else{
            return false;
        }
    }

    public static function all(){
        $response = new DAO();
        $result = $response->get(static::$tableStatic,"*");
        if (!empty($result)) {
            $items = array();
            foreach ($result as $itemData) {
                $args = [];
                foreach (static::$columnBd as $column) {
                    $args[] = is_object($itemData) ? $itemData->$column : (is_array($itemData) ? $itemData[$column] : null);
                }
                $item = new static(...$args);
                $items[] = $item;
            }
            return $items;
        }else{
            return null;
        }
    }

    public static function filter($firstcondition, $secondCondition, $searchColumn = null, $searchValue = null, $skipData = null){

        $tableStatic = self::$tableStatic;
        $result = null;
        if($searchColumn != null && $searchValue != null){
            $response = new DAO(
                "SELECT *
                FROM items_menu JOIN items_in_menu_of_day
                ON items_menu.id = items_in_menu_of_day.contenido
                WHERE items_in_menu_of_day.date = :date", ":date", $searchValue);
            $items = $response->getWhitAttributes();
        }else{
            $response = new DAO();
            $result = $response->get($tableStatic,"*",$firstcondition,$secondCondition);
            if (!empty($result)) {
                $items = array();
                foreach ($result as $itemData) {
                    $args = [];
                    foreach (static::$columnBd as $column) {
                        $args[] = is_object($itemData) ? $itemData->$column : (is_array($itemData) ? $itemData[$column] : null);
                    }
                    $item = new static(...$args);
                    $items[] = $item;
                }
            }else{
                return null;
        }
            }
            return $items;



    }

    public function toArray(){
        $attributes = get_object_vars($this);
        $dataArray = array_values(array_slice($attributes, 2));
        $arrayResult = [];
        for ($i = 0; $i < count($this->columnBdNoStatic); $i++) {
            $arrayResult[$this->columnBdNoStatic[$i]] = $dataArray[$i];
        }
        return $arrayResult;
    }


}




?>