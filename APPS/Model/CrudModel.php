<?php
require_once "gestionRestauranteSettings/Connection.php";

class CrudModel{
    protected $sentenceSql;
    protected $params;
    protected $binParams;
    public function __construct($sentenceSql = null ,$binParams = null, $params = null) {
        $this->sentenceSql = $sentenceSql;
        $this->params = $params;
        $this->binnParams = $binParams;
    }

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