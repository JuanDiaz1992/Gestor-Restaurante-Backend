<?php

require_once "CrudModel.php";
require_once "gestionRestauranteSettings/Connection.php";


class ModelGet extends CrudModel{
    public function __construct($sentenceSql = null ,$binParams = null, $params = null) {
        parent::__construct($sentenceSql, $binParams, $params);
    }

    public static function getDataSimpleConsult($table,$select,$binParams = null,$param = null){
        if ($binParams === null) {
            $sql = "SELECT $select FROM $table";
            $consutl = new ModelGet($sql);
        }else{
            $sql = "SELECT $select FROM $table WHERE $binParams = :$binParams";
            $consutl = new ModelGet($sql, $binParams , $param);
        }
        return $consutl->executeWhitAttributes();
    }


    public function executeWhitAttributes($binParam = null){
        $conectionBd = Connection::connect();
        $stmt = $conectionBd->prepare($this->sentenceSql);
        if ($this->binnParams != null) {
            if(is_array($binParam)){
                $countArray = count($this->binnParams);
                for ($i=0; $i < $countArray ; $i++) {
                    $stmt->bindParam($this->binnParams[$i], $this->params[$i]);
                }
            } else {
                $stmt->bindParam($this->binnParams, $this->params);
            }
        }
        $stmt-> execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS); //Este método devuelve un array de objetos, esto lo especifica Fetch_class
    }

    static public function getDataFilter($table,$select,$linkTo,$equalTo){
        $linkToArray = explode(",",$linkTo);
        $equalToArray = explode("_",$equalTo);
        $linkToText = "";

        if (count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";                }
            }
        }
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";
        $stmt = Connection::connect()->prepare($sql);
        foreach($linkToArray as $key => $value){
            $stmt -> bindParam(":".$value,$equalToArray[$key],PDO::PARAM_STR);
        }

        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_CLASS);

    }
}


?>