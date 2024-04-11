<?php

class MenuOfDay{
    private $table = "items_in_menu_of_day";
    private static $tableStatic = "items_in_menu_of_day";
    private static $id;
    private $idItemInMenu;
    private $date;
    private $state;

    public function __construct($id = null, $idItemInMenu, $date, $state) {
        $this->id = $id;
        $this->idItemInMenu = $idItemInMenu;
        $this->state = $state;
        $this->date = $date;
    }

    public function save(){
        if ($this->id != null) {
            $itemMenuBD = DAO::update($this->table,array(
                "state",
                "date",
                "id"
                ),array(
                $this->state,
                $this->date,
                $this->id),2);
        }else{
            $userBd = DAO::create($this->table,array(
                "contenido",
                "state",
                "date"),
                array(
                    $this->idItemInMenu,
                    $this->state,
                    $this->date
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
}


?>