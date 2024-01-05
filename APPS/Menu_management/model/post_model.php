<?php


require_once "gestionRestauranteSettings/Connection.php";
class PostModel{
    //Creación de Usuario nuevo
    static public function postRecordMenuyModel($table,$date){
        $sql = "INSERT INTO $table (date) VALUES (:date)";
        $stmt = Connection::connect();
        $stmtFull =  $stmt->prepare($sql);
        $stmtFull->bindParam(':date', $date);
        $stmtFull->execute();
        $rowCount = $stmtFull->rowCount();
        $lastInsertId = $stmt->lastInsertId(); //Retorna el último id creado
        if(isset($rowCount)){
            $data = [
                "id"=>$lastInsertId,
                "response"=>200
            ];
            return $data;
        }else{
            $data = [
                "response"=>400
            ];
            return $data;
        }


    }
    static public function postRecordAllMenusModel($table,$idMenu,$idItem,$date){
        $sql = "INSERT INTO $table (menu,contenido, date, state) VALUES (:menu,:contenido,:date, :state)";
        $stateNumber = 1;
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':menu', $idMenu);
        $stmt->bindParam(':contenido', $idItem);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':state', $stateNumber);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if(isset($rowCount)){
            return 200;
        }else{
            return 400;
        }
    }


    static public function createItemMenuModel($table,$name,$description,$price,$rutaArchivoRelativa,$menu_item_type,$idProfile_user,$amount){
        $sql = "INSERT INTO $table (name, description, price, picture, menu_item_type, idProfile_user, amount) VALUES (:name, :description, :price, :picture, :menu_item_type, :idProfile_user, :amount)";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':picture', $rutaArchivoRelativa);
        $stmt->bindParam(':menu_item_type', $menu_item_type);
        $stmt->bindParam(':idProfile_user', $idProfile_user);
        $stmt->bindParam(':amount', $amount);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if(isset($rowCount)){
            return 200;
        }else{
            return 400;
        }
    }


    static public function changeStateModel($table,$idMenu,$id,$state){
        $sql = "UPDATE $table SET state = :state WHERE menu = :menu AND id = :id";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':state',$state,  PDO::PARAM_STR);
        $stmt->bindParam(':menu', $idMenu,  PDO::PARAM_STR);
        $stmt->bindParam(':id', $id,  PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0){
            return 200;
        }else{
            return 404;
        }
    }
    static public function editItemMenuModel($POST,$table,$rutaArchivoRelativa){
        error_log(print_r($POST,true));
        if ($rutaArchivoRelativa!=="") {
            $sql = "UPDATE $table SET name = :name, description = :description, price = :price, picture = :picture, amount = :amount  WHERE id = :id";
        }
        else{
            $sql = "UPDATE $table SET name = :name, description = :description, price = :price, amount = :amount  WHERE id = :id";
        }
        $stmt = Connection::connect()->prepare($sql);
        if ($rutaArchivoRelativa!=="") {
            $stmt->bindParam(':picture',$rutaArchivoRelativa,  PDO::PARAM_STR);
        }
        $stmt->bindParam(':name',$POST["name"],  PDO::PARAM_STR);
        $stmt->bindParam(':description',$POST["description"],  PDO::PARAM_STR);
        $stmt->bindParam(':price',$POST["price"],  PDO::PARAM_STR);
        $stmt->bindParam(':amount',$POST["amount"],  PDO::PARAM_STR);
        $stmt->bindParam(':id', $POST["idItem"],  PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0){
            return 200;
        }else{
            return 404;
        }
    }

    //Solicitudes delete
    static public function deleteItemFromMenuBdModel($table,$id){
        $sql = "DELETE FROM $table WHERE id = :id ";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return 200;
        } else {
            return 404;
        }
    }


    static public function deleteMenufromBdModel($table,$idMenu){
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $idMenu, PDO::PARAM_INT);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return 200;
        } else {
            return 404;
        }
    }

}



?>