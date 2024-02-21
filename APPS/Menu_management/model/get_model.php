<?php

require_once "gestionRestauranteSettings/Connection.php";
$response = new GetController();
class GetModel{
    //Peticiones get sin filtro
    static public function getData($table, $select){
        $sql = "SELECT $select FROM $table";
        $stmt = Connection::connect()->prepare($sql);
        $stmt-> execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS);
    }
    //Peticiones get con filtro
    static public function getDataFilter($table,$select,$linkTo,$equalTo){
        $linkToArray = explode(",",$linkTo);
        $equalToArray = explode("_",$equalTo);
        $linkToText = "";

        if (count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";                }
            }
        }
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";
        $stmt = Connection::connect()->prepare($sql);
        foreach($linkToArray as $key => $value){
            $stmt -> bindParam(":".$value,$equalToArray[$key],PDO::PARAM_STR);
        }

        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_CLASS);

    }
    //Peticiones get con filtro
    static public function getDataFilterSimpleModel($table, $select, $linkTo, $equalTo){
        $sql = "SELECT $select FROM $table WHERE $linkTo = :$linkTo";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":$linkTo", $equalTo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    static public function getItemsNoIncludeOnMenuModel($table, $select, $linkTo, $equalTo){
        $sql = "SELECT *
                FROM $table
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM all_menus
                    WHERE all_menus.contenido = $table.id
                    AND all_menus.menu = :menu
                )";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':menu', $equalTo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    

    static public function getDataWithJoin($table, $select, $linkTo, $equalTo){
        $sql = "SELECT * FROM $table JOIN all_menus ON items_menu.id = all_menus.contenido WHERE all_menus.menu = :menu";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':menu', $equalTo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    
    

    static public function getInventoryForDateModel($table,$linkTo,$equalTo){
        $linkToArray = explode(",",$linkTo);
        $equalToArray = explode("_",$equalTo);
        $linkToText = "";
        if (count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";                }
            }
        }                
        $sql = "SELECT * FROM $table WHERE DATE($linkToArray[0]) = :$linkToArray[0] $linkToText";
        $stmt = Connection::connect()->prepare($sql);
        foreach($linkToArray as $key => $value){
            $stmt -> bindParam(":".$value,$equalToArray[$key],PDO::PARAM_STR);
        }

        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_CLASS);
    }



}
    



?>