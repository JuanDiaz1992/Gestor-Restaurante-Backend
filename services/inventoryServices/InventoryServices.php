<?php
require_once "models/Inventory.php";

class InventoryServices{
    public static function recordInventoryService($data){
        try {
            $purchaseValue = $data["purchaseValue"];
            $reason = $data["reason"];
            $observations = $data["observations"];
            $date = $data["dateTime"];
            if ($purchaseValue && $reason) {
                if($observations ===""){
                    $observations = "No hay observaciones";
                }
                $inventory = new Inventory($purchaseValue,$reason,$observations,$date);
                $inventory->save();
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }


    static public function deleteItemInvetoryService($id){
        try {
            $inventory = Inventory::get($id,"id");
            $inventory->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }


    static public function getInventoryService($date){
        try {
            $inventory = Inventory::filter(["date"=>$date]);
            $data = array_map(function($itemInventory) {
                return $itemInventory->toArray();
            }, $inventory);
            return $data;
        } catch (\Throwable $th) {
            return null;
        }
    }
}

?>