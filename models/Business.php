<?php
require_once "models/Bases/BaseItem.php";
class Business extends BaseItem{
    protected $table = "business";
    protected static $tableStatic = "business";
    protected static $columnBd = ["nameBusiness","documentBusiness","logo","description","officeHours","address","numberPhone","id"];
    protected $columnBdNoStatic = ["nameBusiness","documentBusiness","logo","description","officeHours","address","numberPhone","id"];
    protected $nameBusiness;
    protected $documentBusiness;
    protected $logo;
    protected $description;
    protected $officeHours;
    protected $address;
    protected $numberPhone;
    protected $id;
    public function __construct($nameBusiness,$documentBusiness,$logo,$description,$officeHours,$address,$numberPhone,$id=null ) {
        $this->nameBusiness = $nameBusiness;
        $this->documentBusiness = $documentBusiness;
        $this->logo = $logo;
        $this->description = $description;
        $this->officeHours = $officeHours;
        $this->address = $address;
        $this->numberPhone = $numberPhone;
        $this->id = $id;
    }

    //Setters
    public function setNameBusiness($nameBusiness){
        $this->nameBusiness = $nameBusiness;
    }
    public function setDocumentBusiness($documentBusiness){
        $this->documentBusiness = $documentBusiness;
    }
    public function setLogo($logo){
        $this->logo = $logo;
    }
    public function setDescription($description){
        $this->description = $description;
    }
    public function setOfficeHours($officeHours){
        $this->officeHours = $officeHours;
    }
    public function setAddress($address){
        $this->address = $address;
    }
    public function setNumberPhone($numberPhone){
        $this->numberPhone = $numberPhone;
    }

    //Getters
    public function getNameBusiness(){
        return $this->nameBusiness;
    }
    public function getDocumentBusiness(){
        return $this->documentBusiness;
    }
    public function getLogo(){
        return$this->logo;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getOfficeHours(){
        return $this->officeHours;
    }
    public function getAddress(){
        return $this->address;
    }
    public function getNumberPhone(){
        return $this->numberPhone;
    }
}


?>