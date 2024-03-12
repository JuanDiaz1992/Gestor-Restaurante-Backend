<?php

class ModelPost{
    private $sentenceSql;
    private $params;
    private $binParams;
    private $returnId;
    public function __construct($sentenceSql = null ,$binParams = null, $params = null, $returnId = null) {
        $this->sentenceSql = $sentenceSql;
        $this->params = $params;
        $this->binnParams = $binParams;
        $this->returnId = $returnId;
    }

    public static function simplePost($table,$binParams = null,$param = null, $returnId = null){
        if(is_array($binParams)){
            $keys = implode(", ", $binParams);
            $values = ":" . implode(", :", $binParams);
            $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        }else{
            $sql = "INSERT INTO $table ($binParams) VALUES (:$binParams)";
        }
        $consutl = new ModelPost($sql, $binParams, $param, $returnId);
        return $consutl->postWhitAttributes();
    }

    public function postWhitAttributes(){
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

}





?>