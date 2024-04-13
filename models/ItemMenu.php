<?php
require_once "models/Bases/BaseITem.php";
require_once "services/crudDbMysql/DAO.php";

class ItemMenu extends BaseITem{
    protected $table = "items_menu";
    protected static $tableStatic = "items_menu";
    protected static $columnBd = [
        'name',
        'description',
        'price',
        'picture',
        'menu_item_type',
        'amount',
        'date',
        'id',
    ];
        protected $columnBdNoStatic = [
        'name',
        'description',
        'price',
        'picture',
        'menu_item_type',
        'amount',
        'date',
        'id',
    ];

    protected $name;
    protected $description;
    protected $price;
    protected $picture;
    protected $menuItemType;
    protected $amount;
    protected $date;
    protected $id;
    public function __construct($name, $description, $price, $picture, $menuItemType, $amount, $date = null,$id = null) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->picture = $picture;
        $this->menuItemType = $menuItemType;
        $this->amount = $amount;
        $this->date = $date;
        $this->id = $id;
    }

    // public static function filter($firstcondition, $secondCondition, $searchColumn = null, $searchValue = null, $skipData = null){
    //     $tableStatic = self::$tableStatic;
    //     $result = null;
    //     if($searchColumn != null && $searchColumn != null && $searchValue != null){
    //         $response = new DAO(
    //             "SELECT *
    //             FROM $tableStatic JOIN $firstcondition
    //             ON $tableStatic.id = $firstcondition.$secondCondition
    //             WHERE $firstcondition.$searchColumn = :$searchColumn", ":".$searchColumn, $searchValue);
    //         $result = $response->getWhitAttributes();
    //     }else{
    //         $response = new DAO();
    //         $result = $response->get($tableStatic,"*",$firstcondition,$secondCondition);
    //         $querySet = array();
    //         }
    //     foreach($result as $element){
    //         $item = new ItemMenu(
    //             $element->id,
    //             $element->name,
    //             $element->description,
    //             $element->price,
    //             $element->picture,
    //             $element->menu_item_type,
    //             $element->amount,
    //             $element->date
    //         );
    //         $querySet[] = $item;
    //         return $querySet;
    //     }

    // }


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