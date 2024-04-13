<?php

require_once "Settings/Connection.php";
require_once "services/crudDbMysql/CrudModel.php";
//Data Access Object
class DAO extends CrudModel{
    private $returnId;
    public function __construct($sentenceSql = null ,$binParams = null, $params = null, $returnId = null) {
        parent::__construct($sentenceSql, $binParams, $params);
        $this->returnId = $returnId;
    }
    //POST
    public static function create($table,$binParams = null,$param = null, $returnId = null){
        if(is_array($binParams)){
            $keys = implode(", ", $binParams);
            $values = ":" . implode(", :", $binParams);
            $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        }else{
            $sql = "INSERT INTO $table ($binParams) VALUES (:$binParams)";
        }
        $consutl = new DAO($sql, $binParams, $param, $returnId);
        return $consutl->createWhitAttributes();
    }
    public function createWhitAttributes(){
        $conectionBd = Connection::connect();
        $stmt = $conectionBd->prepare($this->sentenceSql);
        if ($this->binnParams != null) {
            if(is_array($this->binnParams)){
                $countArray = count($this->binnParams);
                for ($i=0; $i < $countArray ; $i++) {
                    $stmt->bindParam($this->binnParams[$i], $this->params[$i]);
                }
            } else {
                $stmt->bindParam($this->binnParams, $this->params);
            }
        }
        $stmt-> execute();
        $rowCount = $stmt->rowCount();
        if(isset($rowCount)){
            if ($this->returnId != null) { //Esta validación para cuando se requiera retornar  el id que se creo.
                $lastInsertId = $conectionBd->lastInsertId();
                $data = [
                    "id"=>$lastInsertId,
                    "response"=>200
                ];
                return $data;
            }else{
                return 200;
            }
        }else{
            return 400;
        }
    }

    //GET
    public static function get($table,$select,$binParams = null,$param = null){

        if ($binParams === null) {
            $sql = "SELECT $select FROM $table";
            $consult = new DAO($sql);
        }else{
            if(is_array($param) && is_array($binParams) && count($param) == count($binParams)){
                $conditions = [];
                $paramsToBind = [];
                // Crear las condiciones de la consulta WHERE y los parámetros a enlazar
                foreach ($binParams as $index => $binParam) {
                    $conditions[] = "$binParam = :$binParam";
                    $paramsToBind[":$binParam"] = $param[$index];
                }
                $conditionStr = implode(" AND ", $conditions);
                $sql = "SELECT $select FROM $table WHERE $conditionStr";
                $consult = new DAO($sql,$binParams,$param);

            }else{
                $sql = "SELECT $select FROM $table WHERE $binParams = :$binParams";
                $consult = new DAO($sql, $binParams , $param);
            }

        }
        return $consult->getWhitAttributes();
    }
    public function getWhitAttributes(){
        $conectionBd = Connection::connect();
        $stmt = $conectionBd->prepare($this->sentenceSql);
        if ($this->binnParams != null) {
            if(is_array($this->binnParams)){
                for ($i=0; $i < count($this->binnParams) ; $i++) {
                    $stmt->bindParam($this->binnParams[$i], $this->params[$i]);
                }
            } else {
                $stmt->bindParam($this->binnParams, $this->params);
            }
        }
        $stmt-> execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS); //Este método devuelve un array de objetos, esto lo especifica Fetch_class
    }

    //DELETE
    public static function delete($table,$binParams,$param){
        $sql = "DELETE FROM $table WHERE $binParams = :$binParams";
        $consutl = new DAO($sql, $binParams, $param);
        return $consutl->executeWhitAttributes();
    }

    //UPDATE
    public static function update($table,$binParams,$param, $cantConditions = 1){
        //El ultimo dato del array resivido en params
        //es el dato con el que se hace la consulta
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
        $consutl = new DAO($sql, $binParams, $param);
        return $consutl->executeWhitAttributes();
    }



}




?>