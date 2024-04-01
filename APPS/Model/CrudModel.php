<?php
require_once "Settings/Connection.php";

abstract class CrudModel{
    protected $sentenceSql;
    protected $params;
    protected $binParams;
    public function __construct($sentenceSql = null ,$binParams = null, $params = null) {
        $this->sentenceSql = $sentenceSql;
        $this->params = $params;
        $this->binnParams = $binParams;
    }


    abstract static public function create($table,$binParams = null,$param = null, $returnId = null);
    abstract static public function get($table,$select,$binParams = null,$param = null);
    abstract static public function delete($table,$binParams,$param);
    abstract static public function update($table,$binParams,$param, $cantConditions = 1);


    public function executeWhitAttributes(){
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
            return 200;
        }else{
            return 400;
        }
    }


}



?>