<?php

require_once "gestionRestauranteSettings/Connection.php";
require_once "CrudModel.php";


class ModelUpdate extends CrudModel{

    public function __construct($sentenceSql = null ,$binParams = null, $params = null) {
        parent::__construct($sentenceSql,$binParams, $params);
    }

    public static function simpleUpdate($table,$binParams,$param, $cantConditions = 1){
        $condition = array_slice($binParams, -$cantConditions, $cantConditions);
        $setConditions = [];
        for ($i=0; $i < $cantConditions; $i++) {
            if ($i == $cantConditions - 1) {
                $setConditions[$i] = "$condition[$i] = :$condition[$i]";
            }else{
                $setConditions[$i] = "$condition[$i] = :$condition[$i] AND ";
            }
        }
        $setStrConditions = implode($setConditions);
        $setParams = [];
        foreach ($binParams as $key) {
            $setParams[] = "$key = :$key";
        }
        $setClause = implode(", ", array_slice($setParams,0,-$cantConditions));
        $sql = "UPDATE $table SET $setClause WHERE $setStrConditions";
        $consutl = new ModelUpdate($sql, $binParams, $param);
        return $consutl->executeWhitAttributes();
    }


}





?>