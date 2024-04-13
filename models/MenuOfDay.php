<?php
require_once "models/Bases/BaseITem.php";

class MenuOfDay extends BaseITem{
    protected $table = "items_in_menu_of_day";
    protected static $tableStatic = "items_in_menu_of_day";
    protected static $columnBd = [
        "contenido",
        "state",
        "date",
        "id"
    ];
    protected $columnBdNoStatic = [
        "contenido",
        "state",
        "date",
        "id"
    ];
    protected $idItemInMenu;
    protected $state;
    protected $date;
    protected $id;

    public function __construct($idItemInMenu,$state,$date,$id = null) {
        $this->idItemInMenu = $idItemInMenu;
        $this->state = $state;
        $this->date = $date;
        $this->id = $id;
    }

    public function getIdItemInMenu() {
        return $this->idItemInMenu;
    }

    public function setIdItemInMenu($idItemInMenu) {
        $this->idItemInMenu = $idItemInMenu;
    }
    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }
    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function getId() {
        return $this->date;
    }


}


?>