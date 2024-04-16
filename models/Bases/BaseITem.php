<?php
require_once "services/crudDbMysql/DAO.php";
//Para extender de esta base en la declaración de atributos
//El id siempre debe estar de último
//el resto de datos deben ir en el mismo orden que en las columnas de la bd
//el atributo id de cada clase siempre debe llamarse id, independientemente del nombre en la bd

class BaseITem{
    protected $table = "";
    protected static $tableStatic = "";
    protected static $columnBd = [];
    protected $columnBdNoStatic = [];

    //Get devuelve un objeto en especifico
    public static function get($identifier, $especificData = null) {
        if ($especificData == null) {
            $especificData == "id";
        }
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
        try {
            $attributes = get_object_vars($this);
            $dataArray = array_values(array_slice($attributes, 2));
            if ($this->id != null) {
                $itemsData = DAO::update($this->table,$this->columnBdNoStatic,$dataArray);
            }else{
                $columnBdWithoutLast = array_slice($this->columnBdNoStatic, 0, -1);
                $atributesWithoutLast = array_slice($dataArray, 0, -1);
                $item = DAO::create($this->table,$columnBdWithoutLast,$atributesWithoutLast,true
                );
                $this->id = $item['id'];
                return true;
            }
        } catch (\Throwable $th) {
            return false;
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

    //all devuelve un array de objetos
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

    //Metodo para buscar relaciones entre tablas
    public static function select_related($firstcondition, $secondCondition, $searchColumn = null, $searchValue = null, $skipData = null){
        $tableStatic = static::$tableStatic;
        if($searchColumn != null && $searchValue != null){
            $response = new DAO(
                "SELECT *
                FROM $tableStatic JOIN $firstcondition
                ON $tableStatic.id = $firstcondition.$secondCondition
                WHERE $firstcondition.$searchColumn = :$searchColumn", ":".$searchColumn, $searchValue);
            $items = $response->getWhitAttributes();
            return $items;
        }
    }

    public static function select_no_related($firstcondition, $secondCondition, $searchColumn = null, $searchValue = null){
        $tableStatic = static::$tableStatic;
        if($searchColumn != null && $searchValue != null){
            $response = new DAO(
                "SELECT *
                FROM $firstcondition
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM $tableStatic
                    WHERE $tableStatic.$secondCondition = $firstcondition.id
                    AND $tableStatic.$searchColumn = :$searchColumn)", $searchColumn, $searchValue);
            $result = $response->getWhitAttributes();
            return $result;
        }
    }

    //all devuelve un array de objetos
    public static function filter($conditions){
        $tableStatic = static::$tableStatic;
        $bindParams = [];
        $params = [];
        foreach ($conditions as $key => $value) {
            $bindParams[] = $key;
            $params [] = $value;
        }
        $response = new DAO();
        $result = $response->get($tableStatic,"*",$bindParams,$params);
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
        }}

    
        public function toArray(){
        $attributes = get_object_vars($this);
        $dataArray = array_values(array_slice($attributes, 2));
        $arrayResult = [];
        for ($i = 0; $i < count($this->columnBdNoStatic); $i++) {
            $arrayResult[$this->columnBdNoStatic[$i]] = $dataArray[$i];
        }
        return $arrayResult;
    }

    public static function getTableStatic() {
        return static::$tableStatic;
    }

    public static function getColumnsBd($position) {
        return static::$columnBd[$position];
    }



}




?>