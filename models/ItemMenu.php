<?php
require_once "models/Bases/BaseITem.php";
require_once "services/crudDbMysql/DAO.php";

class ItemMenu extends BaseITem{
    protected $table = "items_menu";
    protected static $tableStatic = "items_menu";
    protected static $columnBd = [
        'id',
        'name',
        'description',
        'price',
        'picture',
        'menuItemType',
        'amount',
        'date',
    ];
    protected $id;
    private $name;
    private $description;
    private $price;
    private $picture;
    private $menuItemType;
    private $amount;
    private $date;
    public function __construct($id = null, $name, $description, $price, $picture, $menuItemType, $amount, $date = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->picture = $picture;
        $this->menuItemType = $menuItemType;
        $this->amount = $amount;
        $this->date = $date;
    }

    public static function get($identifier = null,$especificData = null){
        if ($identifier !== null) {
            $itemMenuBD = null;
            if(is_numeric($identifier)){
                $itemMenuBD = DAO::get(self::$tableStatic,"*","id",$identifier);
            }else if($especificData){
                $itemMenuBD = DAO::get(self::$tableStatic,"*",$especificData,$identifier);
            } else {
                $itemMenuBD = DAO::get(self::$tableStatic,"*","name",$identifier);
            }
            if (count($itemMenuBD)>=0) {
                $itemMenuBD = new ItemMenu(
                    $itemMenuBD[0]->id,
                    $itemMenuBD[0]->name,
                    $itemMenuBD[0]->description,
                    $itemMenuBD[0]->price,
                    $itemMenuBD[0]->picture,
                    $itemMenuBD[0]->menu_item_type,
                    $itemMenuBD[0]->amount,
                );
                return $itemMenuBD;
            }
        }
        return null;
    }
    

    public function save(){
        if ($this->id != null) {
            $itemMenuBD = DAO::update($this->table,array(
                "name",
                "password",
                "photo",
                "type_user",
                "id"),array(
                $this->name,
                $this->password,
                $this->photo,
                $this->typeUser,
                $this->id));
        }else{
            $userBd = DAO::create($this->table,array(
                "username",
                "password",
                "name",
                "photo",
                "type_user",
                "id_negocio"),
                array(
                    $this->userName,
                    $this->password,
                    $this->name,
                    $this->photo,
                    $this->typeUser,
                    $this->idNegocio
                ),true
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


    public static function all(){
        $response = new DAO();
        $result = $response->get(self::$tableStatic,"*");
        if ($result) {
            return $result;
        }else{
            return $null;
        }
    }

    public static function filter($firstcondition, $secondCondition, $searchColumn = null, $searchValue = null, $skipData = null){
        $tableStatic = self::$tableStatic;
        $result = null;
        if($searchColumn != null && $searchColumn != null && $searchValue != null){
            $response = new DAO(
                "SELECT *
                FROM $tableStatic JOIN $firstcondition
                ON $tableStatic.id = $firstcondition.$secondCondition
                WHERE $firstcondition.$searchColumn = :$searchColumn", ":".$searchColumn, $searchValue);
            $result = $response->getWhitAttributes();
        }else{
            $response = new DAO();
            $result = $response->get($tableStatic,"*",$firstcondition,$secondCondition);
            $querySet = array();
            }
        foreach($result as $element){
            $item = new ItemMenu(
                $element->id,
                $element->name,
                $element->description,
                $element->price,
                $element->picture,
                $element->menu_item_type,
                $element->amount,
                $element->date
            );
            $querySet[] = $item;
            return $querySet;
        }

    }


    //*Getters and setters */
    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function getMenuItemType() {
        return $this->menuItemType;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getDate() {
        return $this->date;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setPicture($picture) {
        $this->picture = $picture;
    }

    public function setMenuItemType($menuItemType) {
        $this->menuItemType = $menuItemType;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}


?>