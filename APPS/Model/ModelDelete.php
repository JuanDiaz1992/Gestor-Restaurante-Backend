<?php

require_once "gestionRestauranteSettings/Connection.php";
require_once "CrudModel.php";

class ModelDelete extends CrudModel{

    public function __construct($sentenceSql = null ,$binParams = null, $params = null) {
        parent::__construct($sentenceSql,$binParams,$params);
    }

    public static function simpleDelete($table,$binParams,$param){
        $sql = "DELETE FROM $table WHERE $binParams = :$binParams";
        $consutl = new ModelDelete($sql, $binParams, $param);
        return $consutl->executeWhitAttributes();
    }

}





?>