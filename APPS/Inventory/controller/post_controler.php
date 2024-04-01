<?php

require_once "APPS/Model/DAO.php";
require_once "Funciones/Responses.php";


class PostController{


    /************************Metodo para crear usuarios nuevos *********************/
    static public function postRecordInventoryController($table, $purchaseValue, $reason, $observations, $idProfile_user,$date){
            if ($purchaseValue && $reason &&  $idProfile_user) {
                if($observations ===""){
                    $observations = "No hay observaciones";
                }
                $response = DAO::create($table,array("purchaseValue", "reason", "observations", "idProfile_user", "date"),array($purchaseValue, $reason, $observations, $idProfile_user,$date));
                //$response = PostModel::postRecordInventoryModel($table, $purchaseValue, $reason, $observations, $idProfile_user,$date);
                Responses::responseNoDataWhitStatus($response);
            }else{
                Responses::responseNoDataWhitStatus(404);
            }
    }


    static public function deleteItemInvetoryController($table,$id){
        $response = DAO::delete($table,"id",$id);
        Responses::responseNoDataWhitStatus($response);
    }

}


?>
