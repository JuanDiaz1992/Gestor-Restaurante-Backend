<?php
require_once "models/Bases/BaseITem.php";

class Inventory extends BaseITem{
    protected $table = "inventory";
    protected static $tableStatic = "inventory";
    protected static $columnBd = ["purchaseValue","reason","observations","date","id"];
    protected $columnBdNoStatic = ["purchaseValue","reason","observations","date","id"];

    protected $purchaseValue;
    protected $reason;
    protected $observations;
    protected $date;
    protected $id;

    public function __construct($purchaseValue,$reason,$observations,$date,$id = null ) {
        $this->purchaseValue = $purchaseValue;
        $this->reason = $reason;
        $this->observations = $observations;
        $this->date = $date;
        $this->id = $id;
    }

    //Setters
    public function setPurchaseValue($purchaseValue){
        $this->purchaseValue = $purchaseValue;
    }
    public function setReason($reason){
        $this->reason = $reason;
    }
    public function setObservations($observations){
        $this->observations = $observations;
    }
    public function setDate($date){
        $this->date = $date;
    }

    //Getters
    public function getPurchaseValue(){
        return $this->purchaseValue;
    }
    public function getReason(){
        return $this->reason;
    }
    public function getObservations(){
        return $this->observations;
    }
    public function getDate(){
        return $this->date;
    }
}

?>