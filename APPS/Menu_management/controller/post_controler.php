<?php


require_once "Funciones/Responses.php";
require_once "utils/Responses.php";



class PostController{
    //Esta parte es la encargada de gestionar el menú
    static public function createMenuTemp($item){
        if (!isset($_SESSION["menu_temp"])){
            $_SESSION["menu_temp"] = array();
        }
        $itemExists = 0;
        foreach ($_SESSION["menu_temp"] as $existingItem) {
            if ($existingItem["id"] === $item["id"]) {
                $itemExists = 1;
                break;
            }
        }
        if ($itemExists === 0) {
            array_push($_SESSION["menu_temp"], $item);
            $response = $_SESSION["menu_temp"];
            Responses::response($response);
        } else {
            $response = "";
            Responses::response($response);
        }
    }


    static public function createItemMenu($table,$name,$description,$price,$photo,$menu_item_type,$idProfile_user,$amount){
        if(isset($photo['name'])){ //Si el formulario incluye una imagen, la agrega, sino se pone la img por defecto
            $carpetaDestino = "files/images/MenuItems";
            $nombreArchivo = $photo['name'];
            $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }
            $rutaArchivoRelativa = 'files/images/MenuItems/' . $nombreArchivo;
            move_uploaded_file($photo['tmp_name'], $rutaArchivo);
        }else{//Si no hay una foto, se incluye la foto por defecto
            $rutaArchivoRelativa = "files/images/sin_imagen.webp";
        }
        if(!isset($price)){
            $price = 0;
        }
        $data = array($name,$description,$price,$rutaArchivoRelativa,$menu_item_type,$idProfile_user,$amount);
        $binParams = array("name", "description", "price", "picture", "menu_item_type", "idProfile_user", "amount");
        $response = DAO::create($table,$binParams,$data);
        Responses::responseNoDataWhitStatus($response);
    }


    static public function createMenu($date){
        $menuTemp = $_SESSION["menu_temp"];
        $response = DAO::create("menu","date",$date, true);
        $return = new PostController();
        $allElementsSaved = true; // Variable de registro
        if ($response["response"] === 200 ) {
            foreach ($menuTemp as $element){
                $binParams = array("menu","contenido","date","state");
                $data = array($response["id"],$element["id"],$date,1);
                $responseItem = DAO::create("all_menus", $binParams, $data);
                if($responseItem !== 200){
                    $allElementsSaved = false;
                    break;
                }
            }
            if ($allElementsSaved) {
                $_SESSION["menu_temp"] = [];
                // Todos los elementos se guardaron correctamente, enviar respuesta exitosa
                Responses::responseNoDataWhitStatus(200);
            } else {
                // Al menos un elemento falló, enviar respuesta de error
                Responses::responseNoDataWhitStatus(404);
            }
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }


    static public function addToMenu($table,$id,$idMEnu,$dateTime){
        $response = DAO::create($table,array("menu","contenido", "date", "state"),array($idMEnu,$id,$dateTime,1));
        $return = new PostController();
        if($response === 200){
            Responses::responseNoDataWhitStatus(200);
        }else {
            Responses::responseNoDataWhitStatus(404);
        }
    }
    static public function editItemMenuController($POST,$FILES = ""){
        $rutaArchivoRelativa = "";
        if ($POST["name"] ==="" || $POST["amount"] ==="" || $POST["price"] ==="") {
            $return->fncResponse("",400,"Datos incompletos");
        }else{
            if (isset($POST["beforePicture"])) {
                if($POST["beforePicture"] !== "files/images/sin_imagen.webp"){
                    unlink($POST["beforePicture"]); //Elimina el archivo anterior de la imagen
                }
                $photo = $FILES['photo'];
                $carpetaDestino = "files/images/MenuItems";
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $rutaArchivoRelativa = 'files/images/MenuItems/' . $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
            }
            $table="items_menu";
            if ($rutaArchivoRelativa!=="") {
                $binParams = array("name","description","price","amount","picture","id");
                $data = array($POST["name"],$POST["description"],$POST["price"],$POST["amount"],$rutaArchivoRelativa,$POST["idItem"]);
            }else{
                $binParams = array("name","description","price","amount","id");
                $data = array($POST["name"],$POST["description"],$POST["price"],$POST["amount"],$POST["idItem"]);
            }
            $response = DAO::update($table,$binParams,$data);
            Responses::responseNoDataWhitStatus($response);
        }
    }

    static public function changeState($table,$idMEnu,$id,$state){
        $response = DAO::update($table,array("state","menu","id"),array($state,$idMEnu,$id),2);
        $return = new PostController();
        Responses::responseNoDataWhitStatus($response);
    }


    //Solicitudes delete
    static public function deleteItemFromBd($table,$id,$picture = ""){
        $response = DAO::delete($table,"id",$id);
        if($picture !== "files/images/sin_imagen.webp" && $picture !=="" ){
            unlink($picture); //Elimina el archivo anterior de la imagen
        }
        Responses::responseNoDataWhitStatus($response);
    }
    static public function deleteItemTemporal($id){
        $tempArray = array();
        foreach($_SESSION["menu_temp"] as  $key => $existingItem){
            if($existingItem["id"]!=$id){
                array_push($tempArray, $existingItem);
            }
        }
        unset($_SESSION["menu_temp"]);
        $_SESSION["menu_temp"] = $tempArray;
        Responses::responseNoDataWhitStatus(200);
    }

}


?>
