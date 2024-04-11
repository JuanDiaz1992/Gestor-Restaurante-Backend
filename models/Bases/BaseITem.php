<?php
require_once "services/crudDbMysql/DAO.php";
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
    //En proceso de codificación
    public static function all(){
        $response = new DAO();
        $result = $response->get(static::$tableStatic,"*");
        if (!empty($result)) {
            $items = array();
            foreach($result as $key => $value){
                $user = array(
                    'id' => $value->id,
                    'username' => $value->username,
                    'name' => $value->name,
                    'photo' => $value->photo,
                    'type_user' => $value->type_user,
                );
                array_push($users, $user);
            }
            return $items;
        }else{
            return null;
        }
    }


}




?>